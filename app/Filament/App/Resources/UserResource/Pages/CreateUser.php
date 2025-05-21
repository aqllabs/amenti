<?php

namespace App\Filament\App\Resources\UserResource\Pages;

use App\Filament\App\Resources\UserResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }

    protected function afterCreate(): void
    {
        $user = $this->getRecord();
        $team = Filament::getTenant();
        $team->users()->updateExistingPivot($user->id, ['role' => $this->data['role'] ?? 'mentee']);
        // update the user type in the user table
        $user->user_type = $this->data['role'] ?? 'mentee';
        $user->save();
    }
}
