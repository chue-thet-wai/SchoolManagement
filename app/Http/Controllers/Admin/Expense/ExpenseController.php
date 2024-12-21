<?php

namespace App\Http\Controllers\Admin\Expense;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\RegistrationRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ExpenseController extends Controller
{

    public function __construct() 
    {
        
    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function expenseList(Request $request)
    {
        $res = Expense::select('expense.*');
        if ($request['action'] == 'search') {
            if (request()->has('expense_title') && request()->input('expense_title') != '') {
                $res->where('title','Like', '%' . request()->input('expense_title') . '%');
            }
            if (request()->has('expensefilter_date') && request()->input('expensefilter_date') != '') {
                $res->where('expense_date', request()->input('expensefilter_date'));
            }
        }else {
            request()->merge([
                'expense_title'      => null,
                'expensefilter_date' => null,
            ]);
        }       
        $res = $res->paginate(20);

        return view('admin.expense.expense_list',['list_result' => $res]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function expenseCreate()
    {        
        return view('admin.expense.expense_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function expenseSave(Request $request)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'title'               =>'required',
            'expense_date'        =>'required',
            'amount'              =>'required',
        ]); 
                
        DB::beginTransaction();
        try{
            $insertData = array(
                'title'             =>$request->title,
                'expense_date'      =>$request->expense_date,
                'amount'            =>$request->amount,
                'note'              =>$request->note,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $result=Expense::insert($insertData);
                        
            if($result){      
                DB::commit();
                return redirect(url('admin/expense/list'))->with('success','Expense Created Successfully!');
            }else{
                return redirect()->back()->with('danger','Expense Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Expense Created Fail !');
        }    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function expenseEdit($id)
    {    
        $res = Expense::where('id',$id)->get();
        return view('admin.expense.expense_update',['result'=>$res]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function expenseUpdate(Request $request, $id)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'title'               =>'required',
            'expense_date'        =>'required',
            'amount'              =>'required',
        ]);

        DB::beginTransaction();
        try{
            $updateData = array(
                'title'             =>$request->title,
                'expense_date'      =>$request->expense_date,
                'amount'            =>$request->amount,
                'note'              =>$request->note,
                'updated_by'        =>$login_id,
                'updated_at'        =>$nowDate

            );
            
            $result=Expense::where('id',$id)->update($updateData);
                      
            if($result){
                DB::commit();               
                return redirect(url('admin/expense/list'))->with('success','Expense Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Expense Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Expense Updared Fail !');
        }  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function expenseDelete($id)
    {
        DB::beginTransaction();
        try{
            $checkData = Expense::where('id',$id)->first();

            if (!empty($checkData)) {
                
                $res = Expense::where('id',$id)->delete();
                if($res){
                    DB::commit();
                    //To return list
                    return redirect(url('admin/expense/list'))->with('success','Expense Deleted Successfully!');
                }
            }else{
                return redirect()->back()->with('danger','There is no result with this expense.');
            }
           

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Expense Deleted Failed!');
        }
    }
}
