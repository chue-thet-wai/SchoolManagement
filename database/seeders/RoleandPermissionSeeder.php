<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;

class RoleandPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nowDate  = date('Y-m-d H:i:s', time());
        $del=DB::table('role_permission')->where('role_id','1')->delete();
        //get permission
        $permission_list = Permission::all();
        //add role and permission
        foreach ($permission_list as $permission) {
            $addRoleandPermission = array(
                'role_id'       => '1',
                'permission_id' => $permission->id,
                'created_by'    =>'10001',
                'updated_by'    =>'10001',
                'created_at'  =>$nowDate,
                'updated_at'  =>$nowDate
            );
            $res=DB::table('role_permission')->insert($addRoleandPermission);
        }    
    }
}
