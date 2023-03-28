<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userData = array(
            'user_id'     =>'10001',
            'name'        =>'Admin',
            'email'       =>'admin@email.com',
            'password'    =>bcrypt('admin123'),
            'role'        =>1,
            'created_by'  =>'10001',
            'updated_by'  =>'10001'
        );

        DB::table('users')->insert($userData);
    }
}
