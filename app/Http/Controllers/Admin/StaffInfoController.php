<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\StaffInfo;
use Illuminate\Support\Facades\Storage;

class StaffInfoController extends Controller
{
    private CategoryRepositoryInterface $categoryRepository;
    private UserRepositoryInterface $userRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository, UserRepositoryInterface $userRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->userRepository     = $userRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function staffInfoList(Request $request)
    {
        $res = StaffInfo::leftJoin("users", "users.user_id", "=", "staff_info.user_id")
            ->select('staff_info.*');
        if ($request['action'] == 'search') {
            if (request()->has('staffinfo_name') && request()->input('staffinfo_name') != '') {
                $res->where('staff_info.name', 'Like', '%' . request()->input('staffinfo_name') . '%');
            }
            if (request()->has('staffinfo_email') && request()->input('staffinfo_email') != '') {
                $res->where('staff_info.email', request()->input('staffinfo_email'));
            }
            if (request()->has('staffinfo_contactno') && request()->input('staffinfo_contactno') != '') {
                $res->where('staff_info.contact_number', request()->input('staffinfo_contactno'));
            }
        } else {
            request()->merge([
                'staffinfo_name'      => null,
                'staffinfo_email'     => null,
                'staffinfo_contactno' => null
            ]);
        }
        $res = $res->paginate(20);

        $department_list    = $this->userRepository->getDepartment();
        return view('admin.user.index', ['department_list' => $department_list, 'list_result' => $res]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $department_list    = $this->userRepository->getDepartment();
        return view('admin.user.create', ['department_list' => $department_list]);
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
            'name'            => 'required|min:3',
            'email'           => 'required|min:3|email|unique:users,email',
            'user_profile'    => 'required|mimes:jpeg,jpg,png|max:1000',
            'department_id'   => 'required',
            'contact_no'      => 'numeric|nullable'
        ]);

        $userID = $this->userRepository->generateUserID();
        $image_name = '';

        if ($request->hasFile('user_profile')) {
            try {
                $image = $request->file('user_profile');
                $image_name = $userID . "_" . time() . "." . $image->extension();
                
                // Upload the image to S3 with public visibility
                $path = Storage::disk('s3')->putFileAs('my-files', $image, $image_name, 'public');
                Log::info('Image uploaded to S3 with public access: ' . $path);
            } catch (\Exception $e) {
                Log::error('Error uploading image to S3: ' . $e->getMessage());
                return redirect()->back()->with('danger', 'Failed to upload image.');
            }
        }

        $userData = [
            'user_id'     => $userID,
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => bcrypt($request->password),
            'role'        => $request->department_id,
            'created_by'  => $login_id,
            'updated_by'  => $login_id,
            'created_at'  => $nowDate,
            'updated_at'  => $nowDate
        ];

        $userRes = User::insert($userData);
        if ($userRes) {
            $insertData = [
                'user_id'           => $userID,
                'department_id'     => $request->department_id,
                'name'              => $request->name,
                'login_name'        => $request->login_name,
                'startworking_date' => $request->startworking_date,
                'email'             => $request->email,
                'contact_number'    => $request->contact_no,
                'address'           => $request->address,
                'remark'            => $request->remark,
                'resign_status'     => $request->status,
                'profile_image'     => $image_name,
                'created_by'        => $login_id,
                'updated_by'        => $login_id,
                'created_at'        => $nowDate,
                'updated_at'        => $nowDate
            ];
            if ($request->status == 0) {
                $insertData['resign_date'] = $nowDate;
            }

            $result = StaffInfo::insert($insertData);

            if ($result) {
                return redirect(url('admin/user/list'))->with('success', 'User Information Created Successfully!');
            } else {
                return redirect()->back()->with('danger', 'User Information Creation Failed!');
            }
        } else {
            return redirect()->back()->with('danger', 'User Information Creation Failed!');
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
        $res = StaffInfo::leftjoin('users', 'users.user_id', '=', 'staff_info.user_id')
            ->where('staff_info.user_id', $id)
            ->select('staff_info.*')
            ->get();

        $department_list    = $this->userRepository->getDepartment();
        return view('admin.user.update', ['department_list' => $department_list, 'result' => $res]);
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
            'name'            => 'required|min:3',
            'email'           => 'required|min:3|email',
            'user_profile' => 'mimes:jpeg,jpg,png|max:1000',
            'contact_no'      => 'numeric|nullable'
        ]);
        if ($request->department_id == '99') {
            return redirect()->back()->with('danger', 'Please select Department !');
        }
        $chkEmail = $this->userRepository->checkEmail($request->email, $id);
        if ($chkEmail == false) {
            return redirect()->back()->with('danger', 'Email already exist !');
        }

        if ($request->hasFile('user_profile')) {

            $previous_img = $request->previous_image;
            @unlink(public_path('/assets/user_images/' . $previous_img));

            $image = $request->file('user_profile');
            $extension = $image->extension();
            $image_name = $id . "_" . time() . "." . $extension;
        } else {
            $image_name = "";
        }

        $userData = array(
            'user_id'     => $id,
            'name'        => $request->name,
            'email'       => $request->email,
            'role'        => $request->department_id,
            'updated_by'  => $login_id,
            'updated_at'     => $nowDate
        );
        if ($request->password == '') {
            $userData['password'] = bcrypt($request->password);
        }

        $userRes = User::where('user_id', $id)->update($userData);
        if ($userRes) {
            $infoData = array(
                'user_id'           => $id,
                'department_id'     => $request->department_id,
                'name'              => $request->name,
                'login_name'        => $request->login_name,
                'startworking_date' => $request->startworking_date,
                'email'             => $request->email,
                'contact_number'    => $request->contact_no,
                'address'           => $request->address,
                'remark'            => $request->remark,
                'resign_status'     => $request->status,
                'created_by'        => $login_id,
                'updated_by'        => $login_id,
                'updated_at'        => $nowDate
            );
            if ($request->status == 0) {
                $infoData['resign_date'] = $nowDate;
            }
            if ($image_name != "") {
                $infoData['profile_image'] = $image_name;
            }

            $result = StaffInfo::where('user_id', $id)->update($infoData);

            if ($result) {
                if ($image_name != "") {
                    $image->move(public_path('assets/user_images'), $image_name);
                }
                return redirect(url('admin/user/list'))->with('success', 'User Information Updated Successfully!');
            } else {
                return redirect()->back()->with('danger', 'User Information Updated Fail !');
            }
        } else {
            return redirect()->back()->with('danger', 'User Information Created Fail !');
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
        DB::beginTransaction();
        try {
            $checkData = StaffInfo::where('user_id', $id)->first();

            if (!empty($checkData)) {

                $res = StaffInfo::where('user_id', $id)->delete();
                if ($res) {
                    //To delet user
                    try{                        
                        $userdel = User::where('user_id', $id)->forceDelete();
                        //To delete image
                        $image = $checkData['profile_image'];
                        @unlink(public_path('/assets/user_images/' . $image));

                        DB::commit();
                        //To return list
                        return redirect(url('admin/user/list'))->with('success', 'User Information Deleted Successfully!');

                    } catch (\Illuminate\Database\QueryException $e) {
                        // Check if the exception is due to a foreign key constraint violation
                        if ($e->errorInfo[1] === 1451) {
                            return redirect()->back()->with('danger','Cannot delete this record because it is being used in other.');
                        }
                        return redirect()->back()->with('danger','An error occurred while deleting the record.');
                    }
                }
            } else {
                DB::rollback();
                return redirect()->back()->with('danger', 'There is no result with this user information.');
            }
           
        } catch (\Exception $e) {
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger', 'User Information Deleted Failed!');
        }
    }
}
