<?php

namespace App\Http\Controllers\Admin\Operation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\RegistrationRepositoryInterface;
use App\Repositories\CategoryRepository;
use App\Interfaces\CategoryRepositoryInterface;
use App\Models\StudentInfo;
use App\Models\StudentRegistration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentAttendanceRegController extends Controller
{
    private RegistrationRepositoryInterface $regRepository;
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(RegistrationRepositoryInterface $regRepository,CategoryRepository $categoryRepository) 
    {
        $this->regRepository     = $regRepository;
        $this->categoryRepository = $categoryRepository;
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grade_list    = $this->categoryRepository->getGrade();
        $grade=[];
        foreach($grade_list as $a) {
            $grade[$a->id] = $a->name;
        }
        $section_list    = $this->categoryRepository->getSection();
        $section=[];
        foreach($section_list as $a) {
            $section[$a->id] = $a->name;
        }
        
        return view('admin.operation.studentattendance.index',[
                'section_list'=> $section,
                'grade_list'  => $grade
            ]);  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'attendance_studentdate'  =>'required',
        ]); 
        $res = StudentRegistration::join('student_info','student_info.student_id','student_registration.student_id')
                ->join('class_setup','class_setup.id','student_registration.new_class_id');
        $res->where('class_setup.grade_id', $request->attendance_grade);
        $res->where('class_setup.section_id', $request->attendance_section);
        $res=$res->select('student_info.*','student_registration.registration_no')->get();

        $sresult = $res;
        $sresult = $res->toArray();
        $sresultId = array();
        if (!empty($sresult)) {
            $sresultId = array_column($sresult,'registration_no');
        }

        //check attendance list
        $checkAttendance = DB::table('student_attendance')
                        ->where('attendance_date', $request->attendance_studentdate)
                        ->whereIn('registration_no', $sresultId)
                        ->get()
                        ->keyBy('registration_no')
                        ->toArray();

        $listRes = [];
        foreach ($res as $data) {
            $tData = [];
            if (array_key_exists($data->registration_no,$checkAttendance)) {
                $tData['is_check']          = true;
                $tData['student_id']        = $data->student_id;
                $tData['registration_no']   = $data->registration_no;
                $tData['name']              = $data->name;
                $tData['attendance_status'] = $checkAttendance[$data->registration_no]->attendance_status;
                $tData['remark']            = $checkAttendance[$data->registration_no]->remark;
            } else {
                $tData['is_check']          = false;
                $tData['student_id']        = $data->student_id;
                $tData['registration_no']   = $data->registration_no;
                $tData['name']              = $data->name;
                $tData['attendance_status'] = 1;
                $tData['remark']            = '';
            }
            $listRes[] = $tData;
        }

        $attendance = array(
            '1'=>'Present',
            '2'=>'Leave',
            '0'=>'Absent'
        );

        $grade_list    = $this->categoryRepository->getGrade();
        $grade=[];
        foreach($grade_list as $a) {
            $grade[$a->id] = $a->name;
        }
        $section_list    = $this->categoryRepository->getSection();
        $section=[];
        foreach($section_list as $a) {
            $section[$a->id] = $a->name;
        }
        
        return view('admin.operation.studentattendance.create',[
            'list_result'       => $listRes,
            'selected_grade'    => $request->attendance_grade,
            'selected_section'  => $request->attendance_section,
            'date_time'         => $request->attendance_studentdate,
            'attendance'        => $attendance,
            'section_list'      => $section,
            'grade_list'        => $grade
        ]);   
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'attendance_studentdate'            =>'required',
        ]); 
        $checkAttendance = $request->checkStudentAttendance;
        if (empty($checkAttendance)) {
            return redirect()->back()->with('danger','Please check attendance !');
        }
     
        DB::beginTransaction();
        try{
            for ($i=0;$i<count($checkAttendance);$i++) {
                $reg_no     = $checkAttendance[$i];
                $attendance = $reg_no.'-attendance';
                $remark     = $reg_no.'-remark';
                $studentID  = $reg_no.'-studentid';
                $insertData = array(
                    'student_id'        => $request[$studentID],
                    'registration_no'   => $reg_no,
                    'attendance_date'   => $request['attendance_studentdate'],
                    'attendance_status' => $request[$attendance],
                    'status'            => '1',
                    'remark'            => $request[$remark],
                    'created_by'        =>$login_id,
                    'updated_by'        =>$login_id,
                    'created_at'        =>$nowDate,
                    'updated_at'        =>$nowDate
                );
                $res = DB::table('student_attendance')
                    ->updateOrInsert(
                        ['student_id'     => $request[$studentID],
                        'registration_no' => $reg_no, 
                        'attendance_date' => $request['attendance_studentdate']],
                        $insertData
                    );
                if (!$res) {
                    return redirect()->back()->with('danger','Student Attendance Registration Fail !');
                }
            }
            DB::commit();
            return redirect(route('student_attendance.index'))->with('success','Student Attendance Registration Successfully!');
      
        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Student Attendance Registration Fail !');
        }       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
