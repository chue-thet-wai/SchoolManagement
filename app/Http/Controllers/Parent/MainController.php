<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MainController extends Controller
{
    public function home() {
        return view('parent.parent_profile');
    }
    public function parentLogin() {
        return view('parent.parent_login');
    }
    public function parentStudentProfile() {
        return view('parent.parent_studentprofile');        
    }
    
}
