<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\Township;
use App\Models\User;
use App\Models\Role;
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
        $leftRole = array('Teacher','Driver');
        $dept = Role::whereNotIn('name',$leftRole)->get();
        
        return $dept;
    }

    public function getGender()
    {
        $gender = [
            "1" => 'Male',
            "2" => 'Female'
        ];        
       return $gender;
    }
    public function getTownship()
    {
        $township = [];
        $res = Township::all();
        foreach ($res as $t) {
            $township[$t->code] = $t->name;
        }
           
       return $township;
    }
   
}