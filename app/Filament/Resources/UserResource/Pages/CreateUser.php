<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        $user = $this->getRecord();
        $team = Filament::getTenant();
        $team->users()->updateExistingPivot($user->id, ['role' => 'member']);
        $user->switchTeam($team);
    }
}
