<?php

namespace App\Http\Resources\API;

use App\Models\StudentGuardian;
use App\Models\User;
use DateInterval;
use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class CommentResource extends JsonResource
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

        foreach ($this as $alldata) {
            foreach ($alldata as $data) {
                $commentBy = 'Admin';
            
                if ($data->comment_by_parent != null) {
                    $commentBy = "Parent";
                }
            
                $date = new DateTime($data->created_at);
                $date->add(new DateInterval('PT6H30M'));
                $formattedDate = $date->format('d M y h:i A');

                $responsedata[] = [
                    'comment_by' => $commentBy,
                    'comment'    => $data->comment,
                    'created_at' => $formattedDate
                ];
            }        
        }
        return $responsedata;
    }
}
