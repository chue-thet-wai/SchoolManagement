<?php

namespace App\Http\Controllers\Admin\CreateInformation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\TeacherClass;
use App\Models\TeacherInfo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Interfaces\CreateInfoRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;

class TeacherClassController extends Controller
{
    private CreateInfoRepositoryInterface $createInfoRepository;
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(CreateInfoRepositoryInterface $createInfoRepository,CategoryRepositoryInterface $categoryRepository)
    {
        $this->createInfoRepository = $createInfoRepository;
        $this->categoryRepository   = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function teacherClassList(Request $request)
    {
        $res = TeacherClass::leftJoin("class_setup", "class_setup.id", "=", "teacher_class.class_id");
        if ($request['action'] == 'search') {
            if (request()->has('teacherclass_teacher') && request()->input('teacherclass_teacher') != '') {
                $res->where('teacher_class.teacher_id', 'Like', '%' . request()->input('teacherclass_teacher') . '%');
            }
            if (request()->has('teacherclass_class') && request()->input('teacherclass_class') != '') {
                $res->where('teacher_class.class_id', request()->input('teacherclass_class'));
            }
            if (request()->has('teacherclass_academic') && request()->input('teacherclass_academic') != '') {
                $res->where('class_setup.academic_year_id', request()->input('teacherclass_academic'));
            }
        } else {
            request()->merge([
                'teacherclass_teacher' => null,
                'teacherclass_class'   => null,
                'teacherclass_academic'   => null,
            ]);
        }
        $res->select('teacher_class.*');
        $res = $res->paginate(20);

        $class_list = $this->createInfoRepository->getClassSetup();
        $classList=[];
        foreach ($class_list as $t) {
            $classList[$t->id] = $t->name;
        }
        $teacher_list  = $this->getTeacher();

        $academic_list = $this->categoryRepository->getAcademicYear();
        $academic=[];
        foreach($academic_list as $a) {
            $academic[$a->id] = $a->name;
        }

        return view('admin.createinformation.teacherclass.index', [
            'class_list' => $classList, 
            'teacher_list'=>$teacher_list,
            'academic_list'=>$academic,
            'list_result' => $res
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $class_list = $this->createInfoRepository->getClassSetup();
        $teacher_list  = $this->getTeacher();
        return view('admin.createinformation.teacherclass.create', ['class_list' => $class_list, 'teacher_list' => $teacher_list]);
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
            'class_id'            => 'required',
            'teacher_id'          => 'required',
        ]);

        $errmsg =array();
        if ($request->teacher_id == '99') {
            array_push($errmsg,'Teacher');
        } 
        if ($request->class_id == '99') {
            array_push($errmsg,'Class');
        } 
       
        if (!empty($errmsg)) {
            $errmsg = implode(',',$errmsg);
            return redirect()->back()->with('danger','Please select '.$errmsg.'!');
        }
        
        DB::beginTransaction();
        try {
                $insertData = array(
                    'teacher_id'  => $request->teacher_id,
                    'class_id'    => $request->class_id,
                    'remark'      => $request->remark,
                    'created_by'  => $login_id,
                    'updated_by'  => $login_id,
                    'created_at'  => $nowDate,
                    'updated_at'  => $nowDate
                );

                $result = TeacherClass::insert($insertData);

                if ($result) {
                    DB::commit();
                    return redirect(url('admin/teacher_class/list'))->with('success', 'Teacher Class Created Successfully!');
                } else {
                    return redirect()->back()->with('danger', 'Teacher Class Created Fail !');
                }
        } catch (\Exception $e) {
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger', 'Teacher Class Created Fail !');
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
        $res = TeacherClass::where('teacher_class.id', $id)
            ->select('teacher_class.*')
            ->get();

        $class_list = $this->createInfoRepository->getClassSetup();
        $classList=[];
        foreach ($class_list as $t) {
            $classList[$t->id] = $t->name;
        }
        $teacher_list  = $this->getTeacher();
        return view('admin.createinformation.teacherclass.update', [
            'class_list' => $classList, 
            'teacher_list' => $teacher_list,
            'result' => $res
        ]);
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
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'class_id'            => 'required',
            'teacher_id'          => 'required',
        ]);

        $errmsg =array();
        if ($request->teacher_id == '99') {
            array_push($errmsg,'Teacher');
        } 
        if ($request->class_id == '99') {
            array_push($errmsg,'Class');
        } 
       
        if (!empty($errmsg)) {
            $errmsg = implode(',',$errmsg);
            return redirect()->back()->with('danger','Please select '.$errmsg.'!');
        }
       
        DB::beginTransaction();
        try {

            $infoData = array(
                'teacher_id'  => $request->teacher_id,
                'class_id'    => $request->class_id,
                'remark'      => $request->remark,
                'updated_by'  => $login_id,
                'updated_at'  => $nowDate
            );

            $result = TeacherClass::where('id', $id)->update($infoData);

            if ($result) {
                DB::commit();
                return redirect(url('admin/teacher_class/list'))->with('success', 'Teacher Class Updated Successfully!');
            } else {
                return redirect()->back()->with('danger', 'Teacher Class Updated Fail !');
            }           
        } catch (\Exception $e) {
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger', 'Teacher Class Updared Fail !');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $checkData = TeacherClass::where('id', $id)->first();

            if (!empty($checkData)) {
                $res = TeacherClass::where('id', $id)->delete();                
            } else {
                return redirect()->back()->with('danger', 'There is no result with this teacher information.');
            }
            DB::commit();
            //To return list
            return redirect(url('admin/teacher_class/list'))->with('success', 'Teacher Class Deleted Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger', 'Teacher Class Deleted Failed!');
        }
    }

    public function getTeacher() {
        $teacherlist = TeacherInfo::where('resign_status','1');
        $teacherlist=$teacherlist->select('teacher_info.*')->get();

        $teacherList=[];
        foreach ($teacherlist as $t) {
            $teacherList[$t->user_id] = $t->name;
        }
        return $teacherList;
    }
}
