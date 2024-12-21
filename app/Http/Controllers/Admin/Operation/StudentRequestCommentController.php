<?php

namespace App\Http\Controllers\Admin\Operation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\StudentRequest;

class StudentRequestCommentController extends Controller
{
    
    public function __construct() 
    {
        
    }

    
    public function StudentRequestCommentList($student_request_id)
    {  
        $student_request = StudentRequest::where('student_request.id',$student_request_id)
                        ->first()->toArray();
        
        $student_request_data = [];
        if ($student_request) {
            $student_request_data['id']            = $student_request_id;
            $student_request_data['message']       = $student_request['message'];
            $student_request_data['photo']         = $student_request['photo'];
        }
        $res = DB::table('student_request_comment')->where('student_request_id',$student_request_id)->select('student_request_comment.*');
        $res = $res->paginate(20);
        
        
        return view('admin.operation.studentrequestcomment.index',[
            'student_request_data'  =>$student_request_data,
            'list_result'   => $res]);
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
            'comment'         =>'required'
        ]); 
                
        DB::beginTransaction();
        try{
            $insertData = array(
                'student_request_id' =>$request->student_request_id,
                'comment_by_school'  =>$login_id,
                'comment'            =>$request->comment,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $result=DB::table('student_request_comment')->insert($insertData);
                        
            if($result){         
                DB::commit();
                return redirect(url('admin/student_request/comment/list/'.$request->student_request_id))->with('success','Comment Added Successfully!');
            }else{
                return redirect()->back()->with('danger','Comment Added Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Comment Added Fail !');
        }       
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try{
            $checkData = DB::table('student_request_comment')->where('id',$id)->first();

            if (!empty($checkData)) {
                
                $res = DB::table('student_request_comment')->where('id',$id)->delete();
                if($res){
                    DB::commit();
                    //To return list
                    return redirect(url('admin/student_request/comment/list/'.$checkData['student_request_id']))->with('success','Comment deleted Successfully!');        
                }
            }else{
                return redirect()->back()->with('danger','There is no result with this comment.');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Comment Deleted Failed!');
        }
    }

}
