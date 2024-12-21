<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class SchoolDetailResource extends JsonResource
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
        $data['id']                = $this->id;
        $data['name']              = $this->name;
        $data['code']              = $this->code;
        $data['url']               = $this->url;
        if ($this->logo != null && $this->logo != '') {
            $data['photo']  = asset('assets/school_logo/'.$this->logo);
        } else {
            $data['photo']  = null;
        }
        $data['start_date']        = date('Y-m-d',strtotime($this->start_date));
        $data['note']              = $this->node;
       
        return $data;
    }
}
