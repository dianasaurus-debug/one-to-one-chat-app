<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create(
            [
                'name'  => 'leon',
                'email' => 'leon@gmail.com',
                'password'  => bcrypt('password')
            ],
        );
        User::factory()->create(
            [
                'name'  => 'ada wong',
                'email' => 'ada@gmail.com',
                'password'  => bcrypt('password')
            ]
        );
        User::factory()->create(
            [
                'name'  => 'helena harper',
                'email' => 'helena@gmail.com',
                'password'  => bcrypt('password')
            ]
        );
        User::factory()->create(
            [
                'name'  => 'jill valentine',
                'email' => 'jill@gmail.com',
                'password'  => bcrypt('password')
            ]
        );
        User::factory()->create(
            [
                'name'  => 'chris redfield',
                'email' => 'chris@gmail.com',
                'password'  => bcrypt('password')
            ]
        );
    }
}
