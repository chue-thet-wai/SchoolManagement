<?php

namespace App\Repositories;

use App\Interfaces\RegistrationRepositoryInterface;
use App\Models\ClassSetup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegistrationRepository implements RegistrationRepositoryInterface
{
    public function generateStudentID() 
    {
        $maxStudentID = DB::table('student_info')->select('student_id')->orderBy('student_id', 'desc')->first();
        if (!empty($maxStudentID)) {
            $lastNum  = $maxStudentID->student_id;
            
            return ($lastNum+1);
        }
        return '100001';

    }

    public function getClass() 
    {
        $classRes = ClassSetup::all();
        return $classRes;

    }
}