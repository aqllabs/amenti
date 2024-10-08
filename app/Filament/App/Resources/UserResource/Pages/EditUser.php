<?php

namespace App\Filament\App\Resources\UserResource\Pages;

use App\Filament\App\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $team = Filament::getTenant();
        $role = User::findOrFail($data['id'])->teamRole($team);

        $data['role'] = $role->key;
        return $data;
    }

    protected function afterSave()
    {

        $user = $this->getRecord();
        $data = $this->data;
        $team = Filament::getTenant();
        $team->users()->updateExistingPivot($user->id, ['role' => $data['role']]);
    }
}
