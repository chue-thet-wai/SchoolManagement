<?php

namespace App\Http\Controllers\Admin\CreateInformation;

use App\Http\Controllers\Controller;
use App\Models\StudentInfo;
use Illuminate\Http\Request;
use App\Interfaces\UserRepositoryInterface;

class StudentInfoController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository) 
    {
        $this->userRepository     = $userRepository;
    }

    public function studentInfoList() {
        $res = StudentInfo::paginate(10);
        $gender        = $this->userRepository->getGender();
        return view('admin.createinformation.studentinfo.studentlist',['list_result' => $res,'gender' => $gender]);
    }
}
