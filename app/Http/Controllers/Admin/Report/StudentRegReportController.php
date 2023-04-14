<?php

namespace App\Http\Controllers\Admin\Report;

use App\Exports\ExportStudentReg;
use App\Http\Controllers\Controller;
use App\Models\StudentRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Interfaces\RegistrationRepositoryInterface;

class StudentRegReportController extends Controller
{
    private RegistrationRepositoryInterface $regRepository;

    public function __construct(RegistrationRepositoryInterface $regRepository) 
    {
        $this->regRepository     = $regRepository;
    }

    public function studentRegReport(Request $request) {

        $class = $this->regRepository->getClass(); 
        $class_list=[];
        foreach($class as $c) {
            $class_list[$c->id] = $c->name;
        }

        $res = StudentRegistration::leftJoin('student_info','student_info.student_id','=','student_registration.student_id');
        if ($request['action'] == 'search') {
            if (request()->has('studentreg_regno') && request()->input('studentreg_regno') != '') {
                $res->where('registration_no', request()->input('studentreg_regno'));
            }
            if (request()->has('studentreg_studentId') && request()->input('studentreg_studentId') != '') {
                $res->where('student_registration.student_id', request()->input('studentreg_studentId'));
            }
            if (request()->has('studentreg_name') && request()->input('studentreg_name') != '') {
                $res->where('name', 'Like', '%' . request()->input('studentreg_name') . '%');
            }
        } else if ($request['action'] == 'export') {

            if (request()->has('studentreg_regno') && request()->input('studentreg_regno') != '') {
                $res->where('registration_no', request()->input('studentreg_regno'));
            }
            if (request()->has('studentreg_studentId') && request()->input('studentreg_studentId') != '') {
                $res->where('student_registration.student_id', request()->input('studentreg_studentId'));
            }
            if (request()->has('studentreg_name') && request()->input('studentreg_name') != '') {
                $res->where('name', 'Like', '%' . request()->input('studentreg_name') . '%');
            }
            
            $res->select('student_registration.*','student_info.name');
            $regRes = $res->get();

            $regData = [];
            foreach ($regRes as $res) {
                $resArr['registration_no']= $res->registration_no;
                $resArr['student_id']     = $res->student_id;
                $resArr['name']           = $res->name;
                if (array_key_exists($res->old_class_id,$class_list)) {
                    $resArr['old_class']   = $class_list[$res->old_class_id];
                } else {
                    $resArr['old_class']   = '';
                }
                if (array_key_exists($res->new_class_id,$class_list)) {
                    $resArr['new_class']   = $class_list[$res->new_class_id];
                } else {
                    $resArr['new_class']   = '';
                }
                if ($res->registration_date != '') {
                    $resArr['registration_date'] = date('Y-m-d',strtotime($res->registration_date));
                } else {
                    $resArr['registration_date'] = $res->registration_date;
                }
                
                
                $regData[] = $resArr;
            }
    
            return Excel::download(new ExportStudentReg($regData), 'studentRegistration_export.csv');
        } else {
            request()->merge([
                'studentreg_regno'     => null,
                'studentreg_studentId' => null,
                'studentreg_name'      => null,
            ]);
        } 

        $res->select('student_registration.*','student_info.name');
        $res = $res->paginate(5);
        return view('admin.report.studentregreport',['list_result' => $res,'class_list'=>$class_list]);
    }

}
