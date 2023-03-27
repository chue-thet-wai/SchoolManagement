<?php

namespace App\Http\Controllers\Admin\CreateInformation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TeacherInfo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Interfaces\CategoryRepositoryInterface;

class TeacherInfoController extends Controller
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
        $res = TeacherInfo::leftJoin("users", "users.user_id", "=", "teacher_info.user_id")
                ->paginate(10);
        $grade_list    = $this->categoryRepository->getGrade();
        return view('admin.createinformation.teacherinfo.index',['grade_list'=>$grade_list,'list_result' => $res]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.createinformation.teacherinfo.create');
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
            'name'            =>'required|min:3',
            'email'           =>'required|min:3',
            'teacher_profile' =>'required | mimes:jpeg,jpg,png | max:1000',
        ]);        

        if($request->hasFile('teacher_profile')){
            $image=$request->file('teacher_profile');
            $extension = $image->extension();
            $image_name = $request->name . "_" . time() . "." . $extension;
        }else{
            $image_name="";
        } 
        $userData = array(
            'user_id'     =>'10002',
            'name'        =>$request->name,
            'email'       =>$request->email,
            'password'    =>bcrypt('admin123'),
            'role'        =>2,
            'phone'       =>$request->phone,
            'created_by'  =>$login_id,
            'updated_by'  =>$login_id
        );
        $result=User::insert($userData);
       
        $insertData = array(
            'user_id'        =>'10002',
            'nrc'            =>$request->nrc,
            'date_of_birth'  =>$request->date_of_birth,
            'joined_date'    =>$request->joined_date,
            'address'        =>$request->address,
            'created_by'     =>$login_id,
            'updated_by'     =>$login_id
        );

        $result=TeacherInfo::insert($insertData);
                  
        if($result){
            $image->move(public_path('assets/teacher_images'),$image_name);   
            return redirect(route('teacher_info.index'))->with('success','Teacher Information Created Successfully!');
        }else{
            return redirect()->back()->with('danger','Teacher Information Created Fail !');
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
        $checkData = TeacherInfo::where('id',$id)->first()->toArray();
        if (!empty($checkData)) {
            
            $res = TeacherInfo::where('id',$id)->delete();
            if($res){
                //To delet user
                $checkUserData = User::where('user_id',$checkData['user_id'])->first()->toArray();
                $userdel = User::where('user_id',$checkData['user_id'])->delete();

                //To delete image
                $image=$checkUserData['image'];
                @unlink(public_path('albums_images/'. $image));

                //To return list
                $listres = TeacherInfo::leftJoin("users", "users.user_id", "=", "teacher_info.user_id")
                        ->paginate(10);
                return redirect(route('subject.index',['list_result' => $listres]))
                            ->with('success','Teacher Information Deleted Successfully!');
            }
        }else{
            return redirect()->back()->with('error','There is no result with this teacher information.');
        }
    }
}
