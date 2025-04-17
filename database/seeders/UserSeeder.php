<?php

namespace Database\Seeders;

use Silber\Bouncer\BouncerFacade as Bouncer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('adminadmin'),
        ]);

        $employe = User::create([
            'first_name' => 'Employe',
            'last_name' => 'Employe',
            'email' => 'Employe@gmail.com',
            'password' => Hash::make('Employe'),
        ]);

        User::create([
            'first_name' => 'User',
            'last_name' => 'User',
            'email' => 'user@gmail.com',
            'password' => Hash::make('useruser'),
        ]);

        // Bouncer::allow('admin')->to([
        //     'absence-restore',
        // ]);

        // Bouncer::allow('employe')->to([
        //     'absence-restore',
        // ]);

        // Bouncer::assign('admin')->to($admin);
        // Bouncer::assign('employe')->to($employe);
    }
}
