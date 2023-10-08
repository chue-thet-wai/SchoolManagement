<?php

namespace App\Http\Controllers\Admin\Library;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BookCategory;
use App\Models\BookRegister;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class BookRegisterController extends Controller
{

    public function __construct() 
    {
        
    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bookRegisterList(Request $request)
    {
        $bookcategorys=$this->getBookCategory();
        $bookcategory_list=[];
        foreach($bookcategorys as $b) {
            $bookcategory_list[$b->id] = $b->name;
        }
        $res = BookRegister::select('book_register.*');
        if ($request['action'] == 'search') {
            if (request()->has('book_title') && request()->input('book_title') != '') {
                $res->where('title', request()->input('book_title'));
            }
        }else {
            request()->merge([
                'book_title'      => null,
            ]);
        }       
        $res = $res->paginate(20);

        return view('admin.library.bookregister.bookregister_list',[
            'list_result' => $res,
            'bookcategory_list' => $bookcategory_list
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */ 
    public function bookRegisterCreate()
    {   
        $bookcategory_list=$this->getBookCategory();     
        return view('admin.library.bookregister.bookregister_create',[
            'bookcategory_list' => $bookcategory_list
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bookRegisterSave(Request $request)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'category_id'            =>'required',
            'title'                  =>'required',
            'author'                 =>'required'
        ]); 
        
        DB::beginTransaction();
        try{
            $insertData = array(
                'category_id'       =>$request->category_id,
                'title'             =>$request->title,
                'author'            =>$request->author,
                'description'       =>$request->description,
                'quantity'          =>$request->quantity,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $result=BookRegister::insert($insertData);
                        
            if($result){      
                DB::commit();
                return redirect(url('admin/book_register/list'))->with('success','Book Register Created Successfully!');
            }else{
                return redirect()->back()->with('danger','Book Register Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Book Register Created Fail !');
        }    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bookRegisterEdit($id)
    {     
        $bookcategory_list=$this->getBookCategory();      
        $res = BookRegister::where('id',$id)->get();
        return view('admin.library.bookregister.bookregister_update',[
            'result'            => $res,
            'bookcategory_list' => $bookcategory_list
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bookRegisterUpdate(Request $request, $id)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'category_id'            =>'required',
            'title'                  =>'required',
            'author'                 =>'required'
        ]);

        DB::beginTransaction();
        try{
            $updateData   = array(
                'category_id'       =>$request->category_id,
                'title'             =>$request->title,
                'author'            =>$request->author,
                'description'       =>$request->description,
                'quantity'          =>$request->quantity,
                'updated_by'        =>$login_id,
                'updated_at'        =>$nowDate

            );
            
            $result=BookRegister::where('id',$id)->update($updateData);
                      
            if($result){
                DB::commit();               
                return redirect(url('admin/book_register/list'))->with('success','Book Register Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Book Register Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Book Register Updared Fail !');
        }  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bookRegisterDelete($id)
    {
        DB::beginTransaction();
        try{
            $checkData = BookRegister::where('id',$id)->first();

            if (!empty($checkData)) {
                
                $res = BookRegister::where('id',$id)->delete();
                if($res){
                    DB::commit();
                    //To return list
                    return redirect(url('admin/book_register/list'))->with('success','Book Register Deleted Successfully!');
                }
            }else{
                return redirect()->back()->with('error','There is no result with this book register.');
            }
           

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Book Register Deleted Failed!');
        }
    }

    public function getBookCategory() {
        $bookcategory_list = BookCategory::all();      
        return $bookcategory_list;
    }
}
