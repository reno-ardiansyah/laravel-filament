<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['user']) && !empty($data['user'])) {
            $userData = $data['user'];
            $user = \App\Models\User::create($userData);
            $data['user_id'] = $user->id;
            unset($data['user']);
        }

        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['user'] = $this->record->user->toArray();

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->user?->assignRole('Student');
    }
}
