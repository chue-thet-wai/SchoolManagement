<?php

namespace App\Repositories;

use App\Interfaces\RegistrationRepositoryInterface;
use App\Models\ClassSetup;
use App\Models\Message;
use App\Models\StudentInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegistrationRepository implements RegistrationRepositoryInterface
{
    public function generateStudentID() 
    {
        $maxStudentID = DB::table('student_info')->select('student_id')->orderBy('student_id', 'desc')->first();
        if (!empty($maxStudentID)) {
            $lastNum  = $maxStudentID->student_id;
            
            return ($lastNum+1);
        }
        return '100001';

    }

    public function getClass() 
    {
        $classRes = ClassSetup::all();
        return $classRes;

    }

    public function generateRegistrationNo() 
    {
        $maxRegNo = DB::table('student_registration')->select('registration_no')->orderBy('registration_no', 'desc')->first();
        if (!empty($maxRegNo)) {
            $lastNum  = substr($maxRegNo->registration_no,1);
            $currentDriverID = 'R'.($lastNum+1);
            return $currentDriverID;
        }
        return 'R10001';

    }

    public function generatePaymentInvoiceID() 
    {
        $found = true;  
        $characters = '1234567890';
        $length = 6;
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        while ($found) {
            $result = DB::table('invoice')->select('invoice_id')->where('invoice_id',$randomString)->first();    
            if ($result) {
                $found=true;
            }else{
                $found=false;
            }    
        }
        return $randomString;  
    }
    public function getStudentInfo() 
    {
        return StudentInfo::all();
    }

    public function sendMessage($data) {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        DB::beginTransaction();
        try{
            $insertData = array(
                'student_id'         =>$data['student_id'],
                'title'              =>$data['title'],
                'description'        =>$data['description'],
                'remark'             =>$data['remark'],
                'created_by'         =>$login_id,
                'updated_by'         =>$login_id,
                'created_at'         =>$nowDate,
                'updated_at'         =>$nowDate
            );
            $result=Message::insert($insertData);
                        
            if($result){      
                DB::commit();
                return true;
            }else{
                return false;
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return false;
        }    
    }

    public function getHomeworkStatus(){
        return [
            "1" => "Not Yet",
            "2" => "Complete",
            "3" => "Incompleted"
        ];
    }

    public function getDailyActivity(){
        return [
            "1" => "Class Participation",
            "2" => "Singing Rhyme",
            "3" => "Writing",
            "4" => "Playing with Friends",
            "5" => "Parent Participation",
            "6" => "Homework completion",
            "7" => "Story Telling",
        ];
    }
    public function getStudentRequestTypes(){
        return [
            "1" => "Special Request",
            "2" => "Health",
        ];
    }
}