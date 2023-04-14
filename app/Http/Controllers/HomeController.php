<?php

namespace App\Http\Controllers;

use App\Models\DriverInfo;
use App\Models\StudentInfo;
use App\Models\TeacherInfo;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $studentCount = StudentInfo::count();;
        $teacherCount = TeacherInfo::count();
        $driverCount  = DriverInfo::count();
        return view('home',['student_count'=>$studentCount,'teacher_count' => $teacherCount,'driver_count' => $driverCount]);
    }
}
