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
    public function SubjectList(Request $request)
    {
        $res = Subject::select('subject.*');
        if ($request['action'] == 'search') {
            if (request()->has('subject_name') && request()->input('subject_name') != '') {
                $res->where('name', 'Like', '%' . request()->input('subject_name') . '%');
            }
            if (request()->has('subject_grade_id') && request()->input('subject_grade_id') != '') {
                $res->where('grade_id', request()->input('subject_grade_id'));
            }
        } else {
            request()->merge([
                'subject_name'         => null,
                'subject_grade_id'     => null,
            ]);
        }
        $res = $res->paginate(20);

        $grade_list_data = $this->categoryRepository->getGrade();
        $grade_list = [];
        foreach ($grade_list_data as $g) {
            $grade_list[$g->id] = $g->name;
        }
        return view('admin.category.subject_index', [
            'grade_list'  => $grade_list,
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
        $grade_list = $this->categoryRepository->getGrade();
        return view('admin.category.subject_registration', [
            'grade_list'  => $grade_list,
            'action'      => 'Add'
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
            'grade_id'  => 'required|array'
        ]);

        // if ($request->grade_id == '99') {
        //     return redirect()->back()->with('danger', 'Please select grade !');
        // }
        $insertData = [];
        foreach ($request->grade_id as $grade) {
            $data = [
                'name'           => $request->name,
                'grade_id'       => $grade,
                'created_by'     => $login_id,
                'updated_by'     => $login_id,
                'created_at'     => $nowDate,
                'updated_at'     => $nowDate
            ];
            array_push($insertData, $data);
        }

        $result = Subject::insert($insertData);

        if ($result) {
            $res = Subject::paginate(10);
            return redirect(url('admin/subject/list'))
                ->with('success', 'Subject Added Successfully!');
        } else {
            return redirect()->back()->with('danger', 'Subject Added Fail !');
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
        $grade_list = $this->categoryRepository->getGrade();
        $update_res = Subject::where('id', $id)->get();
        return view('admin.category.subject_registration', [
            'grade_list'  => $grade_list,
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
            'name'      => 'required|min:3',
        ]);

        if ($request->grade_id == '99') {
            return redirect()->back()->with('danger', 'Please select grade !');
        }

        DB::beginTransaction();
        try {
            $updateData = array(
                'name'           => $request->name,
                'grade_id'       => $request->grade_id,
                'updated_by'     => $login_id,
                'updated_at'     => $nowDate
            );

            $result = Subject::where('id', $id)->update($updateData);

            if ($result) {
                DB::commit();
                return redirect(url('admin/subject/list'))->with('success', 'Subject Updated Successfully!');
            } else {
                return redirect()->back()->with('danger', 'Subject Updated Fail !');
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger', 'Subject Updared Fail !');
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
        $checkData = Subject::where('id', $id)->get()->toArray();
        if (!empty($checkData)) {
            try {
                // Attempt to delete the record
                $res = Subject::where('id',$id)->forceDelete();
               
                if($res){

                    return redirect(url('admin/subject/list'))->with('success','Subject Deleted Successfully!');
                }
            } catch (\Illuminate\Database\QueryException $e) {
                // Check if the exception is due to a foreign key constraint violation
                if ($e->errorInfo[1] === 1451) {
                    return redirect()->back()->with('danger','Cannot delete this record because it is being used in other.');
                }
                return redirect()->back()->with('danger','An error occurred while deleting the record.');
            }
           
        } else {
            return redirect()->back()->with('danger', 'There is no result with this subject.');
        }
    }
}
