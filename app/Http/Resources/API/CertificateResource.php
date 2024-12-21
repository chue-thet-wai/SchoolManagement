<?php

namespace App\Http\Resources\API;

use App\Models\StudentGuardian;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class CertificateResource extends JsonResource
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
                $certificate_file_url = '';
                $filename = '';
                $file_extension = '';
                if ($data['certificate_file'] != null || $data['certificate_file'] != '') {
                    $certificate_file_url = asset('assets/certificate_files/' . $data['certificate_file']);
                    // Get the filename
                    $filename = pathinfo($certificate_file_url, PATHINFO_FILENAME);

                    // Get the file extension
                    $file_extension = pathinfo($certificate_file_url, PATHINFO_EXTENSION);
                }

                $responsedata[] = [
                    'id'              => $data['id'],
                    'title'           => $data['title'],
                    'description'     => $data['description'], 
                    'image'           => asset('assets/certificate_images/' . $data['image']),
                    'certificate_file'=> $certificate_file_url,
                    'filename'        => $filename,
                    'file_extension'  => $file_extension,
                ];
            }            
        }

        return $responsedata;
    }
}
