<?php

namespace App\Interfaces;

interface UserRepositoryInterface 
{
    public function generateUserID();
    public function checkEmail($email,$user_id=null);
    public function getDepartment();
    public function getGender();
    public function getTownship();
}