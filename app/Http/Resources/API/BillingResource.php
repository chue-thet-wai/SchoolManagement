<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class BillingResource extends JsonResource
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
                $responsedata[] = [
                    'id'              => $data['id'],
                    'invoice_id'      => $data['invoice_id'],
                    'payment_type'    => $data['payment_type'] == 1 ? "Yearly" : "Monthly",
                    'due_date'        => date('Y-m-d',strtotime($data['updated_at'])), 
                    'amount'          => $data['net_total']
                ];
            }            
        }

        return $responsedata;
    }
}
