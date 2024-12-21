<?php

namespace App\Http\Resources\API;

use App\Models\StudentGuardian;
use App\Models\User;
use DateInterval;
use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class CheckNewSpecialRequestResource extends JsonResource
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
        $hasNewComment = false;

        foreach ($this as $alldata) {
            foreach ($alldata as $data) {
                if ($hasNewComment == false && $data['has_new_comment'] == '1') {
                    $hasNewComment = true;
                }
            }        
        }
        $responsedata['has_new_comment'] = $hasNewComment;
        return $responsedata;
    }
}
