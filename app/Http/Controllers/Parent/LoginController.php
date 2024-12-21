<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\StudentGuardian;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function parentLogin() {
        return view('parent.parent_login');
    }
    
    public function parentLoginSubmit(Request $request) {
        $phone    = $request->phone;
        $password = $request->password;
        $guardian = StudentGuardian::where('phone', $phone)->first();

        if ($guardian && Hash::check($password, $guardian->password)) {
            $guardianId = $guardian->id;
            session(['guardian_id' => $guardianId]);
            return redirect(url('parent/home'));
        } else {
            return redirect()->back()->with('danger','Invalid Login!');
        }
        
    }
}
