<?php

namespace App\Http\Controllers\Admin\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $res = Grade::paginate(10);
        return view('admin.category.grade_index',['list_result' => $res]);
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
            'created_by'     =>$login_id,
            'updated_by'     =>$login_id
        );

        $result=Grade::insert($insertData);
        
        if($result){
            $res = Grade::paginate(10);
            return redirect(route('grade.index',['list_result' => $res]))
                            ->with('success','Grade Added Successfully!');
        }else{
            return redirect()->back()->with('danger','Grade Added Fail !');
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
        $checkData = Grade::where('id',$id)->get()->toArray();

        if (!empty($checkData)) {
            
            $res = Grade::where('id',$id)->delete();
            if($res){
                $listres = Grade::paginate(10);

                return redirect(route('grade.index',['list_result' => $listres]))
                            ->with('success','Grade Deleted Successfully!');
            }
        }else{
            return redirect()->back()->with('error','There is no result with this grade.');
        }
    }
}
