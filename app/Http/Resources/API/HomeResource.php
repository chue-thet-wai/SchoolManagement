<?php

namespace App\Http\Resources\API;

use App\Models\AcademicYear;
use App\Models\ClassSetup;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class HomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $currentDate = date("Y-m-d");
        //current academic year
        $currentAcademic = AcademicYear::where('start_date', '<=', $currentDate)
                        ->where('end_date', '>=', $currentDate)
                        ->first();
        $academicId = $currentAcademic ? $currentAcademic->id : null;

        //current academic year class
        $currentClassIds = ClassSetup::where('academic_year_id', $academicId)
                        ->pluck('id')
                        ->toArray();

        //To get student id
        $student_data_query  = DB::table('student_registration')
                        ->leftjoin('student_info','student_registration.student_id','student_info.student_id')
                        ->leftjoin('class_setup','class_setup.id','student_registration.new_class_id')
                        ->leftjoin('grade','grade.id','class_setup.grade_id')
                        ->where('guardian_id',$this->id)
                        ->whereIn('new_class_id',$currentClassIds)
                        ->whereNull('student_registration.deleted_at')
                        ->select('student_registration.id','student_info.name',
                        'student_registration.student_id','grade.name as grade_name',
                        'class_setup.name as class_name','student_info.student_profile as student_profile')
                        ->get();
        //student data
        $student_data_list = [];
        foreach ($student_data_query as $student) {
            $student_data = [];
            $student_data['id']             = $student->id;
            $student_data['name']           = $student->name;
            $student_data['student_id']     = $student->student_id;
            $student_data['grade_name']     = $student->grade_name;
            $student_data['class_name']     = $student->class_name;
            if ($student->student_profile != null && $student->student_profile != '') {
                $student_data['student_profile']  = asset('assets/student_images/'.$student->student_profile);
            } else {
                $student_data['student_profile']  = null;
            }
            $student_data_list[] = $student_data;
        }
       

        //guardian data
        $guardian_data = [];
        $guardian_data['id']                = $this->id;
        $guardian_data['name']              = $this->name;
        $guardian_data['email']             = $this->email;
        $guardian_data['primary_contact']   = $this->phone;
        $guardian_data['secondary_contact'] = $this->secondary_phone;
        if ($this->photo != null && $this->photo != '') {
            $guardian_data['photo']  = asset('assets/guardian_images/'.$this->photo);
        } else {
            $guardian_data['photo']  = null;
        }
        //$guardian_data['photo']  = $this->photo;     
        $guardian_data['nrc']               = $this->nrc;
        $guardian_data['email']             = $this->email;
        $guardian_data['address']           = $this->address;

        return [
            'guardian_info' => $guardian_data,
            'children'     => $student_data_list,            
        ];
    }
}
