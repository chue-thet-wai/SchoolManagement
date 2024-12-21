<?php

namespace App\Http\Controllers\Admin\Library;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\RegistrationRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\BookRent;
use App\Models\BookRegister;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class BookRentController extends Controller
{
    private RegistrationRepositoryInterface $regRepository;

    public function __construct(RegistrationRepositoryInterface $regRepository) 
    {
        $this->regRepository     = $regRepository;
    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bookRentList(Request $request)
    {
        $bookregisters=$this->getBookRegister();
        $book_list=[];
        foreach($bookregisters as $b) {
            $book_list[$b->id] = $b->name;
        }

        $res = BookRent::select('book_rent.*');
        if ($request['action'] == 'search') {
            if (request()->has('student_name') && request()->input('student_name') != '') {
                $res->where('student_id', request()->input('student_name'));
            }
        }else {
            request()->merge([
                'student_name'      => null,
            ]);
        }       
        $res = $res->paginate(20);

        return view('admin.library.bookrent.bookrent_list',[
            'list_result' => $res,
            'book_list' => $book_list
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */ 
    public function bookRentCreate()
    {   
        $book_list       =$this->getBookRegister(); 
        $student_list    = $this->regRepository->getStudentInfo();

        return view('admin.library.bookrent.bookrent_create',[
            'book_list'    => $book_list,
            'student_list' => $student_list
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bookRentSave(Request $request)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'book_title'        =>'required',
            'student_id'        =>'required',
            'rent_date'          =>'required',
            'return_date'        =>'required'
        ]); 
        
        DB::beginTransaction();
        try{
            $insertData = array(
                'book_title'          =>$request->book_title,
                'student_id'          =>$request->student_id,
                'rent_date'           =>$request->rent_date,
                'return_date'         =>$request->return_date,
                'actual_return_date'  =>$request->actual_return_date,
                'created_by'          =>$login_id,
                'updated_by'          =>$login_id,
                'created_at'          =>$nowDate,
                'updated_at'          =>$nowDate
            );
            $result=BookRent::insert($insertData);
                        
            if($result){      
                DB::commit();
                return redirect(url('admin/book_rent/list'))->with('success','Book Rent Created Successfully!');
            }else{
                return redirect()->back()->with('danger','Book Rent Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Book Rent Created Fail !');
        }    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bookRentEdit($id)
    {     
        $book_list       = $this->getBookRegister(); 
        $student_list    = $this->regRepository->getStudentInfo();

        $res = BookRent::where('id',$id)->get();
        return view('admin.library.bookrent.bookrent_update',[
            'result'            => $res,
            'book_list'         => $book_list,
            'student_list'      => $student_list
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bookRentUpdate(Request $request, $id)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'book_title'         =>'required',
            'student_id'         =>'required',
            'rent_date'          =>'required',
            'return_date'        =>'required'
        ]);

        DB::beginTransaction();
        try{
            $updateData   = array(
                'book_title'          =>$request->book_title,
                'student_id'          =>$request->student_id,
                'rent_date'           =>$request->rent_date,
                'return_date'         =>$request->return_date,
                'actual_return_date'  =>$request->actual_return_date,
                'updated_by'          =>$login_id,
                'updated_at'          =>$nowDate

            );
            
            $result=BookRent::where('id',$id)->update($updateData);
                      
            if($result){
                DB::commit();               
                return redirect(url('admin/book_rent/list'))->with('success','Book Rent Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Book Rent Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Book Rent Updared Fail !');
        }  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bookRentDelete($id)
    {
        DB::beginTransaction();
        try{
            $checkData = BookRent::where('id',$id)->first();

            if (!empty($checkData)) {
                
                $res = BookRent::where('id',$id)->delete();
                if($res){
                    DB::commit();
                    //To return list
                    return redirect(url('admin/book_rent/list'))->with('success','Book Rent Deleted Successfully!');
                }
            }else{
                return redirect()->back()->with('danger','There is no result with this book rent.');
            }
           

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Book Rent Deleted Failed!');
        }
    }

    public function getBookRegister() {
        $books_list = BookRegister::all();      
        return $books_list;
    }
}
