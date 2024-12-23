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
    public function AcademicYearList(Request $request)
    {  
        $res = AcademicYear::select('academic_year.*');
        if ($request['action'] == 'search') {
            if (request()->has('academic_year_name') && request()->input('academic_year_name') != '') {
                $res->where('name','Like', '%' . request()->input('academic_year_name') . '%');
            }
            if (request()->has('academic_year_startdate') && request()->input('academic_year_startdate') != '') {
                $res->where('start_date', '>=',request()->input('academic_year_startdate'));
            }
            if (request()->has('academic_year_enddate') && request()->input('academic_year_enddate') != '') {
                $res->where('end_date', '<=', request()->input('academic_year_enddate'));
            }
        }else {
            request()->merge([
                'academic_year_name'      => null,
                'academic_year_startdate' => null,
                'academic_year_enddate'   => null,
            ]);
        }       
    
        $res = $res->paginate(20);     

        return view('admin.category.academicyr_index',['list_result' => $res]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {          
        return view('admin.category.academicyr_registration',[
            'action'=>'Add'
        ]);
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
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'name'      =>'required|min:3',
        ]);
        $insertData = array(
            'name'           =>$request->name,
            'start_date'     =>$request->start_date,
            'end_date'       =>$request->end_date,
            'created_by'     =>$login_id,
            'updated_by'     =>$login_id,
            'created_at'     =>$nowDate,
            'updated_at'     =>$nowDate
        );

        $result=DB::table('academic_year')->insert($insertData);
        
        if($result){
            return redirect(url('admin/academic_year/list'))->with('success','Academic Year Added Successfully!');
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
        $res = AcademicYear::where('id',$id)->get();
        return view('admin.category.academicyr_registration',[
            'result'=>$res,
            'action'=>'Update'
        ]);
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
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'name'            =>'required|min:3'
        ]); 

        DB::beginTransaction();
        try{
            $updateData = array(
                'name'           =>$request->name,
                'start_date'     =>$request->start_date,
                'end_date'       =>$request->end_date,
                'updated_by'     =>$login_id,
                'updated_at'     =>$nowDate

            );
            
            $result=AcademicYear::where('id',$id)->update($updateData);
                      
            if($result){
                DB::commit();               
                return redirect(url('admin/academic_year/list'))->with('success','Academic Year Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Academic Year Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Academic Year Updated Fail !');
        }  
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
                    //->whereNull('deleted_at')
                    ->get()->toArray();
        if (!empty($checkData)) {
            try {
                // Attempt to delete the record
                $res = AcademicYear::where('id', $id)->forceDelete();
               
                if($res){
                    
                    return redirect(url('admin/academic_year/list'))
                                ->with('success','Academic Year Deleted Successfully!');
                }
            } catch (\Illuminate\Database\QueryException $e) {
                // Check if the exception is due to a foreign key constraint violation
                if ($e->errorInfo[1] === 1451) {
                    return redirect()->back()->with('danger','Cannot delete this record because it is being used in other.');
                }
                return redirect()->back()->with('danger','An error occurred while deleting the record.');
            }
            
        }else{
            return redirect()->back()->with('danger','There is no result with this academic year.');
        }

    }
}
