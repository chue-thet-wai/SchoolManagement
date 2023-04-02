<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\CategoryRepositoryInterface;

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
    public function index()
    {
        $res = Room::paginate(10);
        $branch_list   = $this->categoryRepository->getBranch();
        return view('admin.category.room_index',['list_result' => $res,'branch_list'=>$branch_list]);
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
        if ($request->branch_id == '99') {
            return redirect()->back()->with('danger','Please select Branch !');
        }
        $insertData = array(
            'name'           =>$request->name,
            'capacity'       =>$request->capacity,
            'branch_id'      =>$request->branch_id,
            'created_by'     =>$login_id,
            'updated_by'     =>$login_id
        );

        $result=Room::insert($insertData);
        
        if($result){
            $res = Room::paginate(10);
            return redirect(route('room.index',['list_result' => $res]))
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
        $checkData = Room::where('id',$id)->get()->toArray();

        if (!empty($checkData)) {
            
            $res = Room::where('id',$id)->delete();
            if($res){
                $listres = Room::paginate(10);

                return redirect(route('room.index',['list_result' => $listres]))
                            ->with('success','Room Deleted Successfully!');
            }
        }else{
            return redirect()->back()->with('error','There is no result with this room.');
        }
    }
}
