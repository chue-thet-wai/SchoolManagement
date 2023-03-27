<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AcademicYearController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $res = AcademicYear::paginate(10);
        return view('admin.category.academicyr_index',['list_result' => $res]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $login_id = Auth::user()->user_id;

        $request->validate([
            'name'      =>'required|min:3',
        ]);
        $insertData = array(
            'name'           =>$request->name,
            'start_date'     =>$request->start_date,
            'end_date'       =>$request->end_date,
            'created_by'     =>$login_id,
            'updated_by'     =>$login_id
        );

        $result=DB::table('academic_year')->insert($insertData);
        
        if($result){
            $res = AcademicYear::paginate(10);
            return redirect(route('academic_year.index',['list_result' => $res]))
                            ->with('success','Academic Year Added Successfully!');
        }else{
            return redirect()->back()->with('danger','Academic Year Added Fail !');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $checkData = DB::table('academic_year')
                    ->where('id',$id)
                    ->whereNull('deleted_at')
                    ->get()->toArray();
        if (!empty($checkData)) {
            
            $res = AcademicYear::where('id',$id)->delete();
            if($res){
                $listres = DB::table('academic_year')
                        ->whereNull('deleted_at')
                        ->paginate(10);
                return redirect(route('academic_year.index',['list_result' => $listres]))
                            ->with('success','Academic Year Deleted Successfully!');
            }
        }else{
            return redirect()->back()->with('error','There is no result with this academic year.');
        }

    }
}
