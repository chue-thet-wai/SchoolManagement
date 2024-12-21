<?php

namespace App\Http\Resources\API;

use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class EventResource extends JsonResource
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
        $colorArray=array('#9FCDFF','#93E4D1','#E1DFB1','#CFAC8C',
                            '#DC75E5','#90D797','#A4A3EB','#CAE895'.
                            '#479EFF','#FB8D8D');

        foreach ($this as $alldata) {
            foreach ($alldata as $data) {
                
                $event_from_date = new DateTime($data['event_from_date']);
                $event_to_date   = new DateTime($data['event_to_date']);
                
                $event_start_date = clone $event_from_date;

                // Loop through dates
                while ($event_start_date <= $event_to_date) {
                    $randomColorKey = array_rand($colorArray);
                    $randomColor = $colorArray[$randomColorKey];
                    $responsedata[] = [
                        'id'                => $data['id'],
                        'title'             => $data['title'],
                        'description'       => $data['description'], 
                        'event_from_date'   => $event_start_date->format('Y-m-d'), 
                        'event_to_date'     => date('Y-m-d',strtotime($data['event_to_date'])), 
                        'color_code'        => $randomColor,
                        'remark'            => $data['remark'] 
                    ];
                    $event_start_date->modify('+1 day'); 
                }
                
            }            
        }

        return $responsedata;
    }
}
