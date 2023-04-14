<?php

namespace App\Http\Controllers\Admin\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\CategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubjectController extends Controller
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
        $res = Subject::paginate(10);
        $grade_list = $this->categoryRepository->getGrade();
        return view('admin.category.subject_index',[
            'grade_list'  =>$grade_list,
            'list_result' => $res,
            'action'      => 'Add'
        ]);
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

        if ($request->grade_id == '99') {
            return redirect()->back()->with('danger','Please select grade !');
        }
        
        $insertData = array(
            'name'           =>$request->name,
            'grade_id'       =>$request->grade_id,
            'created_by'     =>$login_id,
            'updated_by'     =>$login_id,
            'created_at'     =>$nowDate,
            'updated_at'     =>$nowDate
        );

        $result=Subject::insert($insertData);
        
        if($result){
            $res = Subject::paginate(10);
            return redirect(route('subject.index',['list_result' => $res]))
                            ->with('success','Subject Added Successfully!');
        }else{
            return redirect()->back()->with('danger','Subject Added Fail !');
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
        $res = Subject::paginate(10);
        $grade_list = $this->categoryRepository->getGrade();
        $update_res = Subject::where('id',$id)->get();
        return view('admin.category.subject_index',[
            'grade_list'  =>$grade_list,
            'list_result' => $res,
            'result'      => $update_res,
            'action'      => 'Update'
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

        if ($request->grade_id == '99') {
            return redirect()->back()->with('danger','Please select grade !');
        }

        DB::beginTransaction();
        try{
            $updateData = array(
                'name'           =>$request->name,
                'grade_id'       =>$request->grade_id,
                'updated_by'     =>$login_id,
                'updated_at'     =>$nowDate
            );
            
            $result=Subject::where('id',$id)->update($updateData);
                      
            if($result){
                DB::commit();               
                return redirect(route('subject.index'))->with('success','Subject Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Subject Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Subject Updared Fail !');
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
        $checkData = Subject::where('id',$id)->get()->toArray();
        if (!empty($checkData)) {
            
            $res = Subject::where('id',$id)->delete();
            if($res){
                $listres = Subject::paginate(10);
                return redirect(route('subject.index',['list_result' => $listres]))
                            ->with('success','Subject Deleted Successfully!');
            }
        }else{
            return redirect()->back()->with('error','There is no result with this subject.');
        }
    }
}
