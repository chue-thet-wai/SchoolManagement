<?php

namespace App\Http\Controllers\Admin\Registration;

use App\Http\Controllers\Controller;
use App\Models\ClassSetup;
use App\Models\DriverInfo;
use App\Models\StudentGuardian;
use App\Models\StudentInfo;
use App\Models\StudentRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegistrationSearchController extends Controller
{
    public function guardianSearch(Request $request)
    {
        
        $guardianSearch = StudentGuardian::where('phone',$request->phone)->first();
        if (!empty($guardianSearch)) {
            return response()->json(array(
                'msg'             => 'found',
                'guardian_id'     => $guardianSearch->id,
                'guardian_name'   => $guardianSearch->name,
                'guardian_address'=> $guardianSearch->address
            ), 200);
        } else {
            return response()->json(array(
                'msg'             => 'notfound',
            ), 200);
        }
    }

    public function classSearch(Request $request)
    {
        
        $classSearch = ClassSetup::join('academic_year','class_setup.academic_year_id','=','academic_year.id')
                        ->join('room','class_setup.room_id','=','room.id')
                        ->where('class_setup.id',$request->class_id)
                        ->select('academic_year.name as academic_year_name','room.capacity')->first();

        if (!empty($classSearch)) {
            $count = DB::table('student_registration')->where('new_class_id','=',$request->class_id)->count();
            return response()->json(array(
                'msg'             => 'found',
                'academic_year'   => $classSearch->academic_year_name,
                'student_limit'   => $classSearch->capacity,
                'current_student_limit' => $count+1
            ), 200);
        } else {
            return response()->json(array(
                'msg'             => 'notfound',
            ), 200);
        }
    }

    public function studentSearch(Request $request)
    {
        
        $studentSearch = StudentInfo::where('student_id',$request->student_id)->first();
        if (!empty($studentSearch)) {
            return response()->json(array(
                'msg'             => 'found',
                'name'            => $studentSearch->name,
                'date_of_birth'   => $studentSearch->date_of_birth,
                'father_name'      => $studentSearch->father_name,
                'mother_name'     => $studentSearch->mother_name
            ), 200);
        } else {
            return response()->json(array(
                'msg'             => 'notfound',
            ), 200);
        }
    }

    public function driverSearch(Request $request)
    {
        
        $driverSearch = DriverInfo::where('driver_id',$request->driver_id)->first();
        if (!empty($driverSearch)) {
            return response()->json(array(
                'msg'             => 'found',
                'name'            => $driverSearch->name,
                'phone'           => $driverSearch->phone,
            ), 200);
        } else {
            return response()->json(array(
                'msg'             => 'notfound',
            ), 200);
        }
    }

    public function studentRegistrationSearch(Request $request)
    {
        
        $studentRegSearch = StudentRegistration::join('student_info','student_info.student_id','=','student_registration.student_id')
                        ->where('student_registration.registration_no',$request->registration_no)
                        ->select('student_registration.*','student_info.name as student_name')->first();

        if (!empty($studentRegSearch)) {
            $grade = ClassSetup::join('grade','grade.id','=','class_setup.grade_id')
                        ->where('class_setup.id',$studentRegSearch->new_class_id)
                        ->select('grade.name as grade_name')->first();
            return response()->json(array(
                'msg'             => 'found',
                'student_id'      => $studentRegSearch->student_id,
                'student_name'    => $studentRegSearch->student_name,
                'grade'           => $grade->grade_name
            ), 200);
        } else {
            return response()->json(array(
                'msg'             => 'notfound',
            ), 200);
        }
    }

    public function paymentRegistrationSearch(Request $request)
    {
        
        $studentRegSearch = StudentRegistration::leftjoin('class_setup','class_setup.id','=','student_registration.new_class_id')
                        ->leftjoin('grade_level_fee','grade_level_fee.grade_id','=','class_setup.grade_id')
                        ->leftjoin('grade','grade.id','=','grade_level_fee.grade_id')
                        ->where('student_registration.student_id',$request->student_id)
                        ->select('student_registration.*',
                        'grade_level_fee.grade_level_amount as grade_level_amount',
                        'grade.name as grade_level'
                        )->first();

        if (!empty($studentRegSearch)) {
            return response()->json(array(
                'msg'             => 'found',
                'grade_level'     => $studentRegSearch->grade_level,
                'grade_level_fee' => $studentRegSearch->grade_level_amount
            ), 200);
        } else {
            return response()->json(array(
                'msg'             => 'notfound',
            ), 200);
        }
    }

    public function cardDataSearch(Request $request)
    {
        
        $studentSearch = StudentInfo::leftjoin('wallet','wallet.card_id','=','student_info.card_id')
                            ->where('student_info.card_id',$request->card_id)
                            ->select('student_info.*','wallet.total_amount as total_amount')
                            ->first();
        if (!empty($studentSearch)) {
            return response()->json(array(
                'msg'             => 'found',
                'student_id'      => $studentSearch->student_id,
                'student_name'    => $studentSearch->name,
                'card_amount'     => $studentSearch->total_amount,
            ), 200);
        } else {
            return response()->json(array(
                'msg'             => 'notfound',
            ), 200);
        }
    }
}
