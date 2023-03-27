<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Models\AdditionalFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\CategoryRepositoryInterface;

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
    public function index()
    {
        $res = AdditionalFee::paginate(10);
        $grade_list    = $this->categoryRepository->getGrade();
        return view('admin.category.additionalfee_index',['grade_list'=>$grade_list,'list_result' => $res]);
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
        $insertData = array(
            'name'               =>$request->name,
            'additional_amount'  =>$request->amount,
            'created_by'         =>$login_id,
            'updated_by'         =>$login_id
        );
        if ($request->grade_id !='99') {
            $insertData['grade_id'] = $request->grade_id;
        }

        $result=AdditionalFee::insert($insertData);
        
        if($result){
            $res = AdditionalFee::paginate(10);
            return redirect(route('additional_fee.index',['list_result' => $res]))
                            ->with('success','Additional Fee Added Successfully!');
        }else{
            return redirect()->back()->with('danger','Additional Fee Added Fail !');
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
        $checkData = AdditionalFee::where('id',$id)->get()->toArray();

        if (!empty($checkData)) {
            
            $res = AdditionalFee::where('id',$id)->delete();
            if($res){
                $listres = AdditionalFee::paginate(10);

                return redirect(route('additional_fee.index',['list_result' => $listres]))
                            ->with('success','Additional Fee Deleted Successfully!');
            }
        }else{
            return redirect()->back()->with('error','There is no result with this Additional Fee.');
        }
    }
}
