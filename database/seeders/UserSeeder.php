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
        $nowDate  = date('Y-m-d H:i:s', time());
        $userData = array(
            'user_id'     =>'10001',
            'name'        =>'Admin',
            'email'       =>'admin@email.com',
            'password'    =>bcrypt('admin123'),
            'role'        =>1,
            'created_by'  =>'10001',
            'updated_by'  =>'10001',
            'created_at'  =>$nowDate,
            'updated_at'  =>$nowDate
        );
        $res=DB::table('users')->get()->toArray();
        if (empty($res)) {
            DB::table('users')->insert($userData);

            //add superadmin role
            $roleres=DB::table('role')->get()->toArray();
            if (empty($roleres)) {
                $roleNames = array('1'=>"Super Admin",'2'=>'Branch Admin','3'=>'Teacher','4'=>'Driver');
                foreach ($roleNames as $key=>$value) {
                    $addRole = array(
                        'id'          =>$key,
                        'name'        =>$value,
                        'created_by'  =>'10001',
                        'updated_by'  =>'10001',
                        'created_at'  =>$nowDate,
                        'updated_at'  =>$nowDate
                    );
                    $res=DB::table('role')->insert($addRole);
                }
            }   
        }    
    }
}
