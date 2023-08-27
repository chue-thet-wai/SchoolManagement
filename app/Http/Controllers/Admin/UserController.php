<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Role;

class UserController extends Controller
{
    public function show_profile()
    {
        $user_id= Auth::user()->user_id;
        $role   = Auth::user()->role;
        $returnProfileImg ='assets/images/profile.jpg';
        
        if ($role == 3) {
            $profileImg = DB::select('select profile_image from teacher_info where user_id="'.$user_id.'"');
            if (!empty($profileImg)) {
                $returnProfileImg = 'assets/teacher_images/'.$profileImg[0]->profile_image;
            } 
        } else {
            $profileImg = DB::select('select profile_image from staff_info where user_id="'.$user_id.'"');
            if (!empty($profileImg)) {
                $returnProfileImg = 'assets/user_images/'.$profileImg[0]->profile_image;
            } 
        }      
        

        $user_res = DB::select('select * from users where user_id="'.$user_id.'"');
        
       
        return view('admin.user.profile',['user_res' => $user_res,'profile_image'=>$returnProfileImg]);
    }

    public function logout()
    {        
        Auth::logout();
        return redirect('/');
    }

    public static function getProfileImage() {
        $user_id=Auth::user()->user_id;
        $role   = Auth::user()->role;
        
        $returnProfileImg ='';
        
        if ($role == 3) {
            $profileImg = DB::select('select profile_image from teacher_info where user_id="'.$user_id.'"');
            if (!empty($profileImg)) {
                $returnProfileImg = 'assets/teacher_images/'.$profileImg[0]->profile_image;
            } 
        } else {
            $profileImg = DB::select('select profile_image from staff_info where user_id="'.$user_id.'"');
            if (!empty($profileImg)) {
                $returnProfileImg = 'assets/user_images/'.$profileImg[0]->profile_image;
            } 
        }

        return $returnProfileImg;        
    }

    public static function getDepartment() {
        $dept = Role::all();        
        return $dept;
    }

    public static function getDashboardPermission() {
        $user_id= Auth::user()->user_id;
        $role   = Auth::user()->role;

        $permission_res = DB::table('role_permission')
                        ->leftjoin('permission','permission.id','=','role_permission.permission_id')
                        ->where('role_id',$role)
                        ->whereNull('role_permission.deleted_at')
                        ->select('permission.*')->get();
    
        //to prepare return array   
        $returnArray = [];
        foreach ($permission_res as $res) {
            $menu['sub_menu']   = $res->sub_menu;
            $menu['menu_route'] = $res->menu_route;
            $menu['type']       = $res->type;
            $returnArray[$res->main_menu][] = $menu;
        }
        return $returnArray;
    }
}
