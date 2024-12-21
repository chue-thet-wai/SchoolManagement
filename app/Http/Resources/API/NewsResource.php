<?php

namespace App\Http\Resources\API;

use App\Models\StudentGuardian;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class NewsResource extends JsonResource
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
                $created_name = '';
            
                $created_name = User::where('user_id', $data['request_by_school'])->value('name');

                $date = new DateTime($data['created_at']);
                $formattedDate = $date->format('d M y h:i A');

                $comments = DB::table('news_comment')
                            ->where('news_id',$data['id'])
                            //->orderBy('created_at', 'desc')
                            ->whereNull('deleted_at')
                            ->get();

                $responsedata[] = [
                    'id'              => $data['id'],
                    'title'           => $data['title'],
                    'description'     => $data['description'], 
                    'image'           => asset('assets/news_images/' . $data['image']),
                    'created_by'      => $created_name,
                    'created_at'      => $formattedDate,
                    'comments'        => new CommentResource($comments)
                ];
            }            
        }

        return $responsedata;
    }
}
