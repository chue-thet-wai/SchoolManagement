<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class AnnoucementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [];

        foreach ($this as $annoucements) {
            foreach ($annoucements as $annoucement) {
                $data[] = [
                    'id'                => $annoucement['id'],
                    'title'             => $annoucement['title'],
                    'description'       => $annoucement['description'], 
                    'created_at'        => date('Y-m-d',strtotime($annoucement['created_at'])), 
                    'remark'            => $annoucement['remark']   
                ];
            }            
        }

        return $data;
    }
}
