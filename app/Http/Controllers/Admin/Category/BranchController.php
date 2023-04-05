<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $res = Branch::paginate(10);
        return view('admin.category.branch_index',['list_result' => $res]);
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
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'name'      =>'required|min:3',
        ]);
        $insertData = array(
            'name'           =>$request->name,
            'phone'          =>$request->phone,
            'address'        =>$request->address,
            'created_by'     =>$login_id,
            'updated_by'     =>$login_id,
            'created_at'     =>$nowDate,
            'updated_at'     =>$nowDate
        );

        $result=Branch::insert($insertData);
        
        if($result){
            $res = Branch::paginate(10);
            return redirect(route('branch.index',['list_result' => $res]))
                            ->with('success','Branch Added Successfully!');
        }else{
            return redirect()->back()->with('danger','Branch Added Fail !');
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
        $checkData = Branch::where('id',$id)->get()->toArray();

        if (!empty($checkData)) {
            
            $res = Branch::where('id',$id)->delete();
            if($res){
                $listres = Branch::paginate(10);

                return redirect(route('branch.index',['list_result' => $listres]))
                            ->with('success','Branch Deleted Successfully!');
            }
        }else{
            return redirect()->back()->with('error','There is no result with this branch.');
        }
    }
}
