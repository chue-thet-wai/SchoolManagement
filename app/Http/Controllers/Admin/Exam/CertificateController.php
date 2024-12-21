<?php

namespace App\Http\Controllers\Admin\Exam;

use App\Http\Controllers\Controller;
use App\Interfaces\CreateInfoRepositoryInterface;
use App\Models\AcademicYear;
use App\Models\Certificate;
use App\Models\StudentRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CertificateController extends Controller
{
    private CreateInfoRepositoryInterface $createInfoRepository;
    private $academicId;

    public function __construct(CreateInfoRepositoryInterface $createInfoRepository) 
    {
        $this->createInfoRepository = $createInfoRepository;
        $currentDate = date("Y-m-d");

         //current academic year
         $currentAcademic = AcademicYear::where('start_date', '<=', $currentDate)
                    ->where('end_date', '>=', $currentDate)
                    ->first();
        $this->academicId = $currentAcademic ? $currentAcademic->id : null;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function CertificateList(Request $request)
    {
        $res = Certificate::select('certificate.*');
        if ($request['action'] == 'search') {
            if (request()->has('certificate_studentid') && request()->input('certificate_studentid') != '') {
                $res->where('student_id',request()->input('certificate_studentid'));
            }
            if (request()->has('certificate_classid') && request()->input('certificate_classid') != '') {
                $res->where('class_id', request()->input('certificate_classid'));
            }
        }else {
            request()->merge([
                'certificate_studentid'   => null,
                'certificate_classid'     => null
            ]);
        }  
        $res=$res->paginate(20);

        $class_list       = $this->createInfoRepository->getClassSetup();
        $classes = [];
        foreach($class_list as $a) {
            $classes[$a->id] = $a->name;
        }

        return view('admin.exam.certificate.index',[
            'list_result'      => $res,
            'class_list'       => $classes
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

        foreach($class_list as $a) {
            $classes[$a->id] = $a->name;
        }
       
        return view('admin.exam.certificate.create',[
            'class_list'        =>$classes
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
            'class_id'       =>'required',
            'student_id'     =>'required'
        ]); 
        $regData = StudentRegistration::where('student_id',$request->student_id)
                        ->latest('student_registration.created_at')
                        ->first();
        $registration_id = '';
        if ($regData) {
            $registration_id = $regData->registration_no;
        }

        $latestId = Certificate::latest()->value('id');
        if($request->hasFile('image')){
            $image=$request->file('image');
            $extension = $image->extension();
            $image_name = (intval($latestId === null ? 0 : $latestId) + 1). "_" . time() . "." . $extension;
        }else{
            $image_name="";
        } 
        
        if ($request->hasFile('certificate_file')) {
            $certificate_file = $request->file('certificate_file');
            $extension = $certificate_file->extension();
            $certificate_file_name = (intval($latestId ?? 0) + 1) . "_" . time() . "." . $extension;
        } else {
            $certificate_file_name = "";
        }
       
        DB::beginTransaction();
        try{
            $insertData = array(
                'class_id'          =>$request->class_id,
                'student_id'        =>$request->student_id,
                'registration_id'   =>$registration_id,
                'title'             =>$request->title,
                'description'       =>$request->description,
                'image'             =>$image_name,
                'certificate_file'  =>$certificate_file_name,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $result=Certificate::insert($insertData);
                        
            if($result){
                if ($image_name != "") {
                    $image->move(public_path('assets/certificate_images'),$image_name);   
                } 
                if ($certificate_file_name != "") {
                    $certificate_file->move(public_path('assets/certificate_files'),$certificate_file_name);   
                }              
                DB::commit();
                return redirect(url('admin/certificate/list'))->with('success','Certificate Created Successfully!');
            }else{
                return redirect()->back()->with('danger','Certificate Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Certificate Created Fail !');
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
        $res = Certificate::where('id',$id)->get();
        $class_id = $res[0]->class_id;
        
        $class_list = $this->createInfoRepository->getClassSetup();

        foreach($class_list as $a) {
            $classes[$a->id] = $a->name;
        }

        //student list
        $studentRes = DB::table('student_registration')
                            ->join('student_info','student_info.student_id','student_registration.student_id')
                            ->select('student_info.*')
                            ->where('new_class_id','=',$class_id)
                            ->get();
        $studentList = [];
        foreach ($studentRes as $student) {
            $studentList[$student->student_id] = $student->name;
        }

        return view('admin.exam.certificate.update',[
            'result'           => $res,
            'class_list'       => $classes,
            'student_list'     => $studentList,
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
            'class_id'       =>'required',
            'student_id'     =>'required'
        ]); 
        $regData = StudentRegistration::where('student_id',$request->student_id)
                        ->latest('student_registration.created_at')
                        ->first();
        $registration_id = '';
        if ($regData) {
            $registration_id = $regData->registration_no;
        }

        if($request->hasFile('image')){

            $previous_img=$request->previous_image;
            @unlink(public_path('/assets/certificate_images/'. $previous_img));

            $image=$request->file('image');
            $extension = $image->extension();
            $image_name = $id. "_" . time() . "." . $extension;
        }else{
            $image_name="";
        } 

        if ($request->hasFile('certificate_file')) {

            $previous_certificate_file = $request->previous_certificate_file;
            @unlink(public_path('/assets/certificate_files/' . $previous_certificate_file));

            $certificate_file = $request->file('certificate_file');
            $extension = $certificate_file->extension();
            $certificate_file_name = $id . "_" . time() . "." . $extension;
        } else {
            $certificate_file_name = "";
        }
       
        DB::beginTransaction();
        try{
            $updateData = array(
                'class_id'          =>$request->class_id,
                'student_id'        =>$request->student_id,
                'registration_id'   =>$registration_id,
                'title'             =>$request->title,
                'description'       =>$request->description,
                'image'             =>$image_name,
                'certificate_file'  =>$certificate_file_name,
                'updated_by'        =>$login_id,
                'updated_at'        =>$nowDate
            );
            
            if ($image_name != "") {
                $updateData['image'] = $image_name;
            }
            
            $result=Certificate::where('id',$id)->update($updateData);
                      
            if($result){
                if ($image_name != "") {
                    $image->move(public_path('assets/certificate_images'),$image_name);  
                } 
                if ($certificate_file_name != "") {
                    $certificate_file->move(public_path('assets/certificate_files'),$certificate_file_name);  
                } 
                 
                DB::commit();               
                return redirect(url('admin/certificate/list/'))->with('success','Certificate Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Certificate Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Certificate Updared Fail !');
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
        try{
            $checkData = Certificate::where('id',$id)->first();

            if (!empty($checkData)) {
                
                $res = Certificate::where('id',$id)->delete();
                if($res){
                    //To delete image 
                    $image=$checkData['image'];
                    if($image !=''){
                        @unlink(public_path('/assets/certificate_images/'. $image));
                    } 
                    //To delete qualification file
                    $file = $checkData['certificate_file'];
                    @unlink(public_path('/assets/certificate_files/' . $file));           
                }
            }else{
                return redirect()->back()->with('danger','There is no result with this certificate.');
            }
            DB::commit();
            //To return list
            return redirect(url('admin/certificate/list/'))->with('success','Certificate Deleted Successfully!');

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Certificate Deleted Failed!');
        }
    }

    public function studentSearch(Request $request) {
        $studentList = DB::table('student_registration')
                            ->join('student_info','student_info.student_id','student_registration.student_id')
                            ->select('student_info.*')
                            ->where('new_class_id','=',$request->class_id)
                            ->get();
        if ($studentList) {
            return response()->json(array(
                'msg'             => 'found',
                'student_data'    => $studentList
            ), 200);
        } else {
            return response()->json(array(
                'msg'             => 'notfound',
            ), 200);
        }
    }
    
}
