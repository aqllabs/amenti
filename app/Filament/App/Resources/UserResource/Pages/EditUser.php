<?php

namespace App\Filament\App\Resources\UserResource\Pages;

use App\Filament\App\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Gate;

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
        $user = User::findOrFail($data['id']);
        $role = $user->teamRole($team);

        if (! Gate::check('updateTeamMember', $team)) {
            $this->notify('error', 'You do not have permission to edit team member roles.');
            $this->redirect($this->getResource()::getUrl('index'));
        }

        $data['role'] = $role?->key;
        return $data;
    }

    protected function afterSave()
    {

        $user = $this->getRecord();
        $data = $this->data;
        $team = Filament::getTenant();
        $team->users()->updateExistingPivot($user->id, ['role' => $data['role']]);
        //update the user type in the user table
        $user->user_type = $data['role'] ?? 'mentee';
        $user->save();
    }
}
