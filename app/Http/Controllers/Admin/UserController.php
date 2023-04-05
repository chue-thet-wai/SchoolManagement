<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function show_profile()
    {
        $user_id=Auth::id();
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
        

        $user_res = DB::select('select * from users where id="'.$user_id.'"');
       
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
}
