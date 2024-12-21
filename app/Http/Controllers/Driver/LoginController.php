<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\DriverInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\StudentGuardian;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function driverLogin() {
        return view('driver.driver_login');
    }
    
    public function driverLoginSubmit(Request $request) {
        $phone    = $request->phone;
        $password = $request->password;
        $driver = DriverInfo::where('phone', $phone)->first();

        if ($driver && Hash::check($password, $driver->password)) {
            $driverId = $driver->driver_id;
            session(['driver_id' => $driverId]);
            return redirect(url('driver/home'));
        } else {
            return redirect()->back()->with('danger','Invalid Login!');
        }
        
    }

    public function logout(Request $request) {
        // Remove a specific session variable
        Session::forget('driver_id');

        // If you want to remove all session data
        Session::flush();
        return redirect('/driver/login');
    }
}
