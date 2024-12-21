<?php

namespace App\Http\Resources\API;

use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class ExamDateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $responsedata = [];
        $currentDate = new DateTime();

        foreach ($this as $alldata) {
            foreach ($alldata as $data) {
                $endDate = new DateTime($data->exam_date);
                $interval = $currentDate->diff($endDate);
                $daysLeft = $interval->days;
                $responsedata[] = [
                    'id'              => $data['id'],
                    'subject_name'    => $data['subject_name'],
                    'exam_date'       => date('d M Y',strtotime($data['exam_date'])),
                    'day_left'        => $daysLeft.'',
                    'subject_image'   => asset('assets/subject_images/'.$data['subject_image'])
                ];
            }            
        }

        return $responsedata;
    }
}
