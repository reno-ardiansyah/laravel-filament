<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use Filament\Actions;
use Illuminate\Support\Str;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\TeacherResource;

class CreateTeacher extends CreateRecord
{
    protected static string $resource = TeacherResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        info('Teacher Form Data:', $data); // Cek di log storage/logs/laravel.log
        // Pastikan jika 'user' tidak ada, maka form group user memang belum terisi.

        // Jika data user ada, buat user terlebih dahulu
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
}
