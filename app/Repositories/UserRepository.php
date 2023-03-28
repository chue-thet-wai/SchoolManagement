<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface 
{
    public function generateUserID() 
    {
        $maxUserID = User::select('user_id')->max('user_id');
        $newUserId = $maxUserID + 1;
        return $newUserId;
    }

    public function checkEmail($email){
        $userEmail = User::where('email',$email)
                    ->select('email')
                    ->get()->toArray();
        if (!empty($userEmail)) {
            return false;
        } else {
            return true;
        }
        
    }
   
}