<?php

namespace App\Http\Resources\API;

use App\Models\AcademicYear;
use App\Models\ClassSetup;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class PocketMoneyRequestResource extends JsonResource
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
        $data['card_id']           = $this->card_id;
        $data['student_id']        = $this->student_id;
        $data['total_amount']      = $this->total_amount;
       
        return $data;
    }
}
