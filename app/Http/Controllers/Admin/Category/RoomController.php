<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\CategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoomController extends Controller
{
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository) 
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function RoomList(Request $request)
    {  
        $res = Room::select('room.*');
        if ($request['action'] == 'search') {
            if (request()->has('room_name') && request()->input('room_name') != '') {
                $res->where('name','Like', '%' . request()->input('room_name') . '%');
            }
            if (request()->has('room_branch_id') && request()->input('room_branch_id') != '') {
                $res->where('branch_id', request()->input('room_branch_id'));
            }
        }else {
            request()->merge([
                'room_name'         => null,
                'room_branch_id'    => null,
            ]);
        }     
        $res = $res->paginate(20);  
      
        $branch_list_data   = $this->categoryRepository->getBranch();
        $branch_list=[];
        foreach($branch_list_data as $b) {
            $branch_list[$b->id] = $b->name;
        } 

        return view('admin.category.room_index',[
            'list_result' => $res,
            'branch_list' =>$branch_list
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branch_list   = $this->categoryRepository->getBranch();

        return view('admin.category.room_registration',[
            'branch_list' =>$branch_list,
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
        if ($request->branch_id == '99') {
            return redirect()->back()->with('danger','Please select Branch !');
        }
        $insertData = array(
            'name'           =>$request->name,
            'capacity'       =>$request->capacity,
            'branch_id'      =>$request->branch_id,
            'created_by'     =>$login_id,
            'updated_by'     =>$login_id,
            'created_at'     =>$nowDate,
            'updated_at'     =>$nowDate
        );

        $result=Room::insert($insertData);
        
        if($result){
            return redirect(url('admin/room/list'))
                            ->with('success','Room Added Successfully!');
        }else{
            return redirect()->back()->with('danger','Room Added Fail !');
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
        $branch_list   = $this->categoryRepository->getBranch();
        $update_res = Room::where('id',$id)->get();
        return view('admin.category.room_registration',[
            'branch_list'=> $branch_list,
            'result'     => $update_res,
            'action'     => 'Update'
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
            'name'      =>'required|min:3',
        ]);
        if ($request->branch_id == '99') {
            return redirect()->back()->with('danger','Please select Branch !');
        }

        DB::beginTransaction();
        try{
            $updateData = array(
                'name'           =>$request->name,
                'capacity'       =>$request->capacity,
                'branch_id'      =>$request->branch_id,
                'updated_by'     =>$login_id,
                'updated_at'     =>$nowDate
            );
            
            $result=Room::where('id',$id)->update($updateData);
                      
            if($result){
                DB::commit();               
                return redirect(url('admin/room/list'))->with('success','Room Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Room Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Room Updated Fail !');
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
        $checkData = Room::where('id',$id)->get()->toArray();

        if (!empty($checkData)) {
            
            try {
                // Attempt to delete the record
                $res = Room::where('id',$id)->forceDelete();
               
                if($res){

                    return redirect(url('admin/room/list'))->with('success','Room Deleted Successfully!');
                }
            } catch (\Illuminate\Database\QueryException $e) {
                // Check if the exception is due to a foreign key constraint violation
                if ($e->errorInfo[1] === 1451) {
                    return redirect()->back()->with('danger','Cannot delete this record because it is being used in other.');
                }
                return redirect()->back()->with('danger','An error occurred while deleting the record.');
            }
        }else{
            return redirect()->back()->with('danger','There is no result with this room.');
        }
    }
}
