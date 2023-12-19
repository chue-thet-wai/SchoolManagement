<?php

namespace App\Imports;

use App\Models\Township;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ImportTownship implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $data=([
            'code'           => $row['code'],
            'name'           => $row['name'],
            'created_by'     => $login_id,
            'updated_by'     => $login_id,
            'created_at'     => $nowDate,
            'updated_at'     => $nowDate 
        ]);
        $checkTownship = Township::where('code',$row['code'])->first();
        if (empty($checkTownship)) {
            $result=Township::insert($data);
        }

    }
}
