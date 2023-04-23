<?php

namespace App\Http\Controllers\Admin\Registration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\RegistrationRepositoryInterface;
use App\Models\TeacherInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeacherAttendanceRegController extends Controller
{
    private RegistrationRepositoryInterface $regRepository;

    public function __construct(RegistrationRepositoryInterface $regRepository) 
    {
        $this->regRepository     = $regRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teacherlist = TeacherInfo::where('resign_status','1');
        $teacherlist=$teacherlist->select('teacher_info.*')->get();

        $teacherList[1] = 'All';
        foreach ($teacherlist as $t) {
            $teacherList[$t->user_id] = $t->name;
        }
        
        return view('admin.registration.teacherattendance.index',[
                'teacher_list'=> $teacherList
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
            'attendance_teacherdate'  =>'required',
        ]); 

        $teacherlist = TeacherInfo::where('resign_status','1');
        $teacherlist=$teacherlist->select('teacher_info.*')->get();

        $teacherList[1] = 'All';
        foreach ($teacherlist as $t) {
            $teacherList[$t->user_id] = $t->name;
        }

        $res = TeacherInfo::where('resign_status','1');
        if ($request->attendance_teacher != 1) {
            $res->where('user_id', $request->attendance_teacher);
        } 
        $res=$res->select('teacher_info.*')->get();

        $attendance = array(
            '1'=>'Present',
            '0'=>'Absent'
        );
        
        return view('admin.registration.teacherattendance.create',[
            'list_result'=> $res,
            'teacher_list'=> $teacherList,
            'selected_teacher'  => $request->attendance_teacher,
            'date_time'         => $request->attendance_teacherdate,
            'attendance'        => $attendance
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
            'attendance_teacherdate'            =>'required',
        ]); 
        $checkAttendance = $request->checkAttendance;
        if (empty($checkAttendance)) {
            return redirect()->back()->with('danger','Please check attendance !');
        }
     
        DB::beginTransaction();
        try{
            for ($i=0;$i<count($checkAttendance);$i++) {
                $user_id = $checkAttendance[$i];
                $attendance = $user_id.'-attendance';
                $remark     = $user_id.'-remark';
                $insertData = array(
                    'user_id'           => $checkAttendance[$i],
                    'attendance_date'   => $request['attendance_teacherdate'],
                    'attendance_status' => $request[$attendance],
                    'remark'            => $request[$remark],
                    'created_by'        =>$login_id,
                    'updated_by'        =>$login_id,
                    'created_at'        =>$nowDate,
                    'updated_at'        =>$nowDate
                );
                $res=DB::table('teacher_attendance')->insert($insertData);
                if (!$res) {
                    return redirect()->back()->with('danger','Teacher Attendance Registration Fail !');
                }
            }
            DB::commit();
            return redirect(route('teacher_attendance.index'))->with('success','Teacher Attendance Registration Successfully!');
      
        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Teacher Attendance Registration Fail !');
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
