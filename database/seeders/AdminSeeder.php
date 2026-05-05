<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    \App\Models\User::create([
        'first_name' => 'BU-ETEEAP',
        'last_name' => 'Admin',
        'email' => 'admin@bu-eteeap.edu.ph',
        'password' => \Illuminate\Support\Facades\Hash::make('bu_eteeap@4dmin'), 
        'role' => 'admin', 
    ]);
}
}
