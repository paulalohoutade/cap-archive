<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@archivexa.cap'],
            [
                'name'     => 'Administrateur CAP',
                'password' => Hash::make('ArchiveXA@2026'),
            ]
        );

        $this->command->info('✅ Utilisateur admin créé : admin@archivexa.cap / ArchiveXA@2026');
    }
}