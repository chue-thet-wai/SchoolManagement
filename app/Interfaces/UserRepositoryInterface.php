<?php

namespace App\Interfaces;

interface UserRepositoryInterface 
{
    public function generateUserID();
    public function checkEmail($email);
}