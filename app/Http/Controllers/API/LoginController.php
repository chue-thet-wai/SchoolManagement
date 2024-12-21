<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginRequest;
use App\Http\Requests\API\SchoolDetailRequest;
use App\Http\Resources\API\SchoolDetailResource;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\StudentGuardian;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    
    public function login(LoginRequest $request)
    {
        try {
            $phone    = $request->phone;
            $password = $request->password;
            $guardian = StudentGuardian::where('phone', $phone)->first();

            if ($guardian && Hash::check($password, $guardian->password)) {
                $token = $guardian->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'status'  => 200,
                    'message' => 'Login successful',
                    'token'   => $token,
                ]);
            } else {
                return response()->json([
                    'status'  => 404,
                    'message' => 'Invalid credentials',
                ], 404);
            }

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function getSchoolDetail(SchoolDetailRequest $request)
    {
        try {
            $code       = $request->code;
            $schoolData = School::where('code', $code)
                            ->where('status','1') //active
                            ->first();

            if ($schoolData) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data found!',
                    'data'    => new SchoolDetailResource($schoolData),
                ]);
            } else {
                return response()->json([
                    'status'  => 404,
                    'message' => 'Invalid School Code !'
                ],404);
            }

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function getConfig(Request $request)
    {
        try {
            $data = [];
            $setting_data  = DB::table('setting')
                            ->where('id','1')
                            ->whereNull('deleted_at')
                            ->first();
            if ($setting_data)  {
                $data['minimum_supported_version'] = $setting_data->app_minimum_supported_version;
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data found!',
                    'data'    => $data,
                ]);
            } else {
                return response()->json([
                    'status'  => 404,
                    'message' => 'Setting Data Not Found!'
                ],404);
            }
           

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }
}
