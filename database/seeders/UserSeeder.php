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
        {
            $admin = User::create([
                'last_name' => 'Admin',
                'first_name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('adminadmin'),
            ]);

            Bouncer::allow('admin')->to([
                'salle-restore',
            ]);

            Bouncer::assign('admin')->to($admin);

            User::factory()
                ->count(15)
                ->create();
        }
    }
}
