<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class ContactListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $contact_data = [];
        $contact ['name']         = $this->father_name;
        $contact['relationship']  = 'Father';
        $contact['phone']         = $this->father_phone;
        $contact_data[] = $contact;
        $contact ['name']         = $this->mother_name;
        $contact['relationship']  = 'Mother';
        $contact['phone']         = $this->mother_phone;
        $contact_data[] = $contact;

        return $contact_data;
    }
}
