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
    protected function afterCreate(): void
    {
        $user = $this->getRecord();
        $team = Filament::getTenant();
        $team->users()->updateExistingPivot($user->id, ['role' => $this->data['role'] ?? 'mentee']);
        //update the user type in the user table
        $user->user_type = $this->data['role'] ?? 'mentee';
        $user->save();
    }
}
