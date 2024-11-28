<?php

namespace App\Filament\App\Resources\GoalResource\Pages;

use App\Filament\App\Resources\GoalResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGoal extends CreateRecord
{
    protected static string $resource = GoalResource::class;
}
