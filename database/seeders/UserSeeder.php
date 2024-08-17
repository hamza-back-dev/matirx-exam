<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=0; $i <= 10 ; $i++) { 
            User::create([
                'prefixname' => 'Mr',
                'firstname' => 'First Name '.$i,
                'middlename' => 'Middle Name '.$i,
                'lastname' => 'Last Name '.$i,
                'suffixname' => 'Us-'.$i,
                'username' => 'user-'.$i,
                'email' => 'user-'.$i. '@gmail.com',
                'photo' => null,
                'type' => 'user',
                'password' => Hash::make('00000000'),
            ]);
        }
    }
}
