<?php

namespace App\Filament\Imports;

use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Hash;
use Carbon\CarbonInterface;
use App\Models\Team;

class UserImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),

            ImportColumn::make('email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255']),
            ImportColumn::make('phone')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),

            ImportColumn::make('username')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),

            ImportColumn::make('password')
                ->requiredMapping()
                ->rules(['required', 'string', 'min:3'])
                ->castStateUsing(function ($state) {
                    info("Processing password: " . ($state ?? 'null'));
                    if (is_null($state)) {
                        info('Password is null');
                        return null;
                    }
                    $hashed = Hash::make((string) $state);
                    info("Password processed - original: $state");
                    return $hashed;
                }),

            ImportColumn::make('user_type')
                ->rules(['nullable', 'string', 'in:admin,user,mentor,mentee,parent'])
        ];
    }

    public function resolveRecord(): ?User
    {
        info('=== Starting resolveRecord ===');

        $team_id = $this->getOptions()['tenant_id'] ?? null;
        if (!$team_id) {
            info('No team_id provided in import options');
            return null;
        }

        $team = Team::find($team_id);
        if (!$team) {
            info("Team with ID {$team_id} not found");
            return null;
        }

        info("Found team: {$team->id}");

        $user = new User($this->data);

        $user->save();

        $team->users()->attach(
            $user->id,
            ['role' => $user->user_type]
        );

        info('=== Ending resolveRecord ===');

        return $user;
    }


    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your user import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }


    public function getJobRetryUntil(): ?CarbonInterface
    {
        return now()->addSeconds(5);
    }
}
