<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Models\AdditionalFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Grade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdditionalFeeController extends Controller
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
    public function AdditionalFeeList(Request $request)
    {
        $res = AdditionalFee::select('additional_fee.*');
        if ($request['action'] == 'search') {
            if (request()->has('additional_fee_name') && request()->input('additional_fee_name') != '') {
                $res->where('name', 'Like', '%' . request()->input('additional_fee_name') . '%');
            }
            if (request()->has('additional_fee_grade_id') && request()->input('additional_fee_grade_id') != '') {
                $res->where('grade_id', request()->input('additional_fee_grade_id'));
            }
        } else {
            request()->merge([
                'additional_fee_name'      => null,
                'additional_fee_grade_id'  => null,
            ]);
        }

        $res = $res->paginate(20);

        $grade_list_data    = $this->categoryRepository->getGrade();
        $grade_list = [];
        foreach ($grade_list_data as $g) {
            $grade_list[$g->id] = $g->name;
        }
        return view('admin.category.additionalfee_index', [
            'grade_list' => $grade_list,
            'list_result' => $res
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $grade_list    = $this->categoryRepository->getGrade();
        return view('admin.category.additionalfee_registration', [
            'grade_list' => $grade_list,
            'action' => 'Add'
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
            'name'      => 'required|min:3',
        ]);
        $insertData = [];

        if ($request->grade_id == 0) {
            $grades = Grade::get('id');
            foreach ($grades as $grade) {
                $data = [
                    'name'               => $request->name,
                    'additional_amount'  => $request->amount,
                    'grade_id'           => $grade['id'],
                    'created_by'         => $login_id,
                    'updated_by'         => $login_id,
                    'created_at'         => $nowDate,
                    'updated_at'         => $nowDate,
                ];
                array_push($insertData, $data);
            }
        }

        if ($request->grade_id != '99' && $request->grade_id != 0) {
            // $insertData['grade_id'] = $request->grade_id;
            $data = [
                'name'               => $request->name,
                'additional_amount'  => $request->amount,
                'grade_id'           => $request->grade_id,
                'created_by'         => $login_id,
                'updated_by'         => $login_id,
                'created_at'         => $nowDate,
                'updated_at'         => $nowDate,
            ];
            array_push($insertData, $data);
        }
        $result = AdditionalFee::insert($insertData);

        if ($result) {
            return redirect(url('admin/additional_fee/list'))
                ->with('success', 'Additional Fee Added Successfully!');
        } else {
            return redirect()->back()->with('danger', 'Additional Fee Added Fail !');
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
        $grade_list    = $this->categoryRepository->getGrade();
        $update_res = AdditionalFee::where('id', $id)->get();
        return view('admin.category.additionalfee_registration', [
            'grade_list' => $grade_list,
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
            'name'            => 'required|min:3'
        ]);

        DB::beginTransaction();
        try {
            $updateData = array(
                'name'               => $request->name,
                'additional_amount'  => $request->amount,
                'updated_by'         => $login_id,
                'updated_at'         => $nowDate
            );
            if ($request->grade_id != '99') {
                $updateData['grade_id'] = $request->grade_id;
            }
            $result = AdditionalFee::where('id', $id)->update($updateData);

            if ($result) {
                DB::commit();
                return redirect(url('admin/additional_fee/list'))->with('success', 'Additionnal Fee Updated Successfully!');
            } else {
                return redirect()->back()->with('danger', 'Additional Fee Updated Fail !');
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error', 'Additional Fee Updared Fail !');
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
        $checkData = AdditionalFee::where('id', $id)->get()->toArray();

        if (!empty($checkData)) {

            $res = AdditionalFee::where('id', $id)->delete();
            if ($res) {
                return redirect(url('admin/additional_fee/list'))
                    ->with('success', 'Additional Fee Deleted Successfully!');
            }
        } else {
            return redirect()->back()->with('error', 'There is no result with this Additional Fee.');
        }
    }
}
