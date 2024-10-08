<?php

namespace App\Filament\App\Resources\UserResource\Pages;

use App\Actions\Jetstream\AddTeamMember;
use App\Actions\Jetstream\InviteTeamMember;
use App\Filament\App\Resources\UserResource;
use App\Models\TeamInvitation;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Jetstream\Contracts\InvitesTeamMembers;
use Laravel\Jetstream\Jetstream;

class CreateUser extends CreateRecord
{

    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
            return $data;
    }
    protected function afterCreate()
    {
        $createdUser = $this->getRecord();
        $data = $this->data;
        $team = Filament::getTenant();
        $team->users()->updateExistingPivot($createdUser->id, ['role' => $data['role']]);

    }
}
