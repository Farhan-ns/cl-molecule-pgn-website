<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin 1',
            'email' => 'admin1@pgn.cyberlabs.co.id',
            'password' => 'PassAdmin1',
        ]);

        User::create([
            'name' => 'Admin 2',
            'email' => 'admin2@pgn.cyberlabs.co.id',
            'password' => 'PassAdmin2',
        ]);
        User::create([
            'name' => 'Admin 3',
            'email' => 'admin3@pgn.cyberlabs.co.id',
            'password' => 'PassAdmin3',
        ]);
        User::create([
            'name' => 'Admin 4',
            'email' => 'admin4@pgn.cyberlabs.co.id',
            'password' => 'PassAdmin4',
        ]);
        User::create([
            'name' => 'Admin 5',
            'email' => 'admin5@pgn.cyberlabs.co.id',
            'password' => 'PassAdmin5',
        ]);
        User::create([
            'name' => 'Admin 6',
            'email' => 'admin6@pgn.cyberlabs.co.id',
            'password' => 'PassAdmin6',
        ]);
        User::create([
            'name' => 'Admin 7',
            'email' => 'admin7@pgn.cyberlabs.co.id',
            'password' => 'PassAdmin7',
        ]);
        User::create([
            'name' => 'Admin 8',
            'email' => 'admin8@pgn.cyberlabs.co.id',
            'password' => 'PassAdmin8',
        ]);
    }
}
