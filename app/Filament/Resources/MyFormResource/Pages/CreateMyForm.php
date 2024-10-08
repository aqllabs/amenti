<?php

namespace App\Filament\Resources\MyFormResource\Pages;

use App\Filament\Resources\MyFormResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMyForm extends CreateRecord
{
    protected static string $resource = MyFormResource::class;

    protected $casts = [
        'structure' => 'array',
    ];


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        return $data;
    }
}
