<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserRepository implements UserRepositoryInterface 
{
    public function generateUserID() 
    {
        $maxUserID = DB::table('users')->select('user_id')->max('user_id');
        $newUserId = $maxUserID + 1;
        return $newUserId;
    }

    public function checkEmail($email,$user_id=null){
        $userEmail = User::where('email',$email)
                    ->select('email','user_id')
                    ->first();
       
        if (!empty($userEmail)) {
            if ($userEmail['user_id'] == $user_id){
                return true;
            }
            return false;
        } else {
            return true;
        }
        
    }

    public function getDepartment()
    {
        $deptArr = array('Head Office','Branch');
        $dept = [];
        for ($i=0;$i< count($deptArr);$i++) {
            $deptOne['id'] = $i+1;
            $deptOne['name']= $deptArr[$i];
            $dept[] = $deptOne;
        }
        
       return $dept;
    }
   
}