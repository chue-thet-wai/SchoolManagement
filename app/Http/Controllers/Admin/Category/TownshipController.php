<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Imports\ImportTownship;
use App\Models\Township;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TownshipController extends Controller
{
    public function townshipList() {
        $res = Township::paginate(10);
        return view('admin.category.township_index',['list_result' => $res]);
    }

    public function importTownship(Request $request) {
        $request->validate([ 
            'file' => 'required',  
         ]);
        Excel::import(new ImportTownship,request()->file('file'));               
        return redirect(url('admin/township/list'))->with('success','Township Uploaded Successfully!');
    }
}
