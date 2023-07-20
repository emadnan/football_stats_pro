<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [ 
            [
                'first_name' => 'muhammad',
                'last_name' => 'adnan',
                'email' => 'adnan.klassen786@gmail.com',
                'phone_no' => '03441346002',
                'password' => Hash::make('12345678')
                
            ],
            
    ];
    DB::table('users')->insert($users);
}
}
