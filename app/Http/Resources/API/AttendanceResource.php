<?php

namespace App\Http\Resources\API;

use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class AttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $attendance = array(
            '2' => 'Leave',
            '1' => 'Present',
            '0' => 'Absent'
        );

        return [
            'id'                => $this->id,
            'attendance_date'   => date('Y-m-d', strtotime($this->attendance_date)),
            'attendance_status' => $attendance[$this->attendance_status],
            'leave_id'          => $this->leave_id,
            'teacher_remark'    => $this->teacher_remark,
            'remark'            => $this->remark,
            'status'            => $this->status == 0 ? "Pending" : "Confirm"
        ];
    }
}
