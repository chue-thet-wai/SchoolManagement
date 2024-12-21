<?php

namespace App\Http\Controllers\Admin\Library;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\RegistrationRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\BookCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class BookCategoryController extends Controller
{

    public function __construct() 
    {
        
    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bookCategoryList(Request $request)
    {
        $res = BookCategory::select('book_category.*');
        if ($request['action'] == 'search') {
            if (request()->has('book_category_name') && request()->input('book_category_name') != '') {
                $res->where('name', request()->input('book_category_name'));
            }
        }else {
            request()->merge([
                'book_category_name'      => null,
            ]);
        }       
        $res = $res->paginate(20);

        return view('admin.library.bookcategory.bookcategory_list',['list_result' => $res]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bookCategoryCreate()
    {        
        return view('admin.library.bookcategory.bookcategory_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bookCategorySave(Request $request)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'name'            =>'required',
        ]); 
        
        DB::beginTransaction();
        try{
            $insertData = array(
                'name'              =>$request->name,
                'remark'            =>$request->remark,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $result=BookCategory::insert($insertData);
                        
            if($result){      
                DB::commit();
                return redirect(url('admin/book_category/list'))->with('success','Book Category Created Successfully!');
            }else{
                return redirect()->back()->with('danger','Book Category Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Book Category Created Fail !');
        }    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bookCategoryEdit($id)
    {        
        $res = BookCategory::where('id',$id)->get();
        return view('admin.library.bookcategory.bookcategory_update',['result'=>$res]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bookCategoryUpdate(Request $request, $id)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'name'       =>'required',
        ]);

        DB::beginTransaction();
        try{
            $updateData   = array(
                'name'          =>$request->name,
                'remark'        =>$request->remark,
                'updated_by'    =>$login_id,
                'updated_at'    =>$nowDate

            );
            
            $result=BookCategory::where('id',$id)->update($updateData);
                      
            if($result){
                DB::commit();               
                return redirect(url('admin/book_category/list'))->with('success','Book Category Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Book Category Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Book Category Updared Fail !');
        }  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bookCategoryDelete($id)
    {
        DB::beginTransaction();
        try{
            $checkData = BookCategory::where('id',$id)->first();

            if (!empty($checkData)) {
                try {
                    // Attempt to delete the record
                    $res = BookCategory::where('id',$id)->forceDelete();
                   
                    if($res){
                        DB::commit();
                        //To return list
                        return redirect(url('admin/book_category/list'))->with('success','Book Category Deleted Successfully!');
                    }
                } catch (\Illuminate\Database\QueryException $e) {
                    // Check if the exception is due to a foreign key constraint violation
                    if ($e->errorInfo[1] === 1451) {
                        return redirect()->back()->with('danger','Cannot delete this record because it is being used in other.');
                    }
                    return redirect()->back()->with('danger','An error occurred while deleting the record.');
                }
            }else{
                return redirect()->back()->with('danger','There is no result with this book category.');
            }
           

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Book Category Deleted Failed!');
        }
    }
}
