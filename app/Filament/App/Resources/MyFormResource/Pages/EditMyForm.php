<?php

namespace App\Filament\App\Resources\MyFormResource\Pages;

use App\Filament\App\Resources\MyFormResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMyForm extends EditRecord
{
    protected static string $resource = MyFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
