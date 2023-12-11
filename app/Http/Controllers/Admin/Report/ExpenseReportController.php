<?php

namespace App\Http\Controllers\Admin\Report;

use App\Exports\ExportExpense;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ExpenseReportController extends Controller
{
    public function expenseReport(Request $request) 
    {

        $res = DB::table('expense')->whereNull('deleted_at');
        if ($request['action'] == 'search') {
            if (request()->has('expensereport_title') && request()->input('expensereport_title') != '') {
                $res->where('title', 'Like', '%' . request()->input('expensereport_title') . '%');
            }
            if (request()->has('expensereport_fromdate') && request()->input('expensereport_fromdate') != '') {
                $res->where('expense_date','>=', request()->input('expensereport_fromdate'));
            }
            if (request()->has('expensereport_todate') && request()->input('expensereport_todate') != '') {
                $res->where('expense_date','<=', request()->input('expensereport_todate'));
            }
           
        } else if ($request['action'] == 'export') {
            if (request()->has('expensereport_title') && request()->input('expensereport_title') != '') {
                $res->where('title', 'Like', '%' . request()->input('expensereport_title') . '%');
            }
            if (request()->has('expensereport_fromdate') && request()->input('expensereport_fromdate') != '') {
                $res->where('expense_date','>=', request()->input('expensereport_fromdate'));
            }
            if (request()->has('expensereport_todate') && request()->input('expensereport_todate') != '') {
                $res->where('expense_date','<=', request()->input('expensereport_todate'));
            }

            $res->select('expense.*');
            $expenseRes = $res->get();

            $expenseData = [];
            foreach ($expenseRes as $res) {
                $resArr['title']          = $res->title;
                $resArr['expense_date']   = date('Y-m-d',strtotime($res->expense_date));
                $resArr['amount']         = $res->amount;
                $resArr['note']           = $res->note;
                $cancelData[] = $resArr;
            }
    
            return Excel::download(new ExportExpense($cancelData), 'expense_export.csv');
        } else {
            request()->merge([
                'expensereport_fromdate'   => null,
                'expensereport_todate'   => null,
                'expensereport_title'      => null,
            ]);
        }       
        $res->select('expense.*');
        $res = $res->paginate(20);
        return view('admin.report.expensereport',[
            'list_result' => $res
        ]);
    }
}
