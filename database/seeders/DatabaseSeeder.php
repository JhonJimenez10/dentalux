<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@dentalux.com'],
            [
                'name'      => 'Administrador',
                'password'  => Hash::make('password'),
                'role'      => 'admin',
                'active'    => true,
            ]
        );

        // Dentista
        User::updateOrCreate(
            ['email' => 'dentist@dentalux.com'],
            [
                'name'      => 'Dr. Diego Polo',
                'password'  => Hash::make('password'),
                'role'      => 'dentist',
                'specialty' => 'Odontología General',
                'active'    => true,
            ]
        );

        // Recepcionista
        User::updateOrCreate(
            ['email' => 'recepcion@dentalux.com'],
            [
                'name'      => 'María Recepción',
                'password'  => Hash::make('password'),
                'role'      => 'receptionist',
                'active'    => true,
            ]
        );
    }
}