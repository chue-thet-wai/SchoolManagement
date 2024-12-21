<?php

namespace App\Http\Controllers\Admin\Driver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\CreateInfoRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\DriverInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DriverInfoController extends Controller
{
    private CreateInfoRepositoryInterface $createInfoRepository;
    private UserRepositoryInterface $userRepository;

    public function __construct(CreateInfoRepositoryInterface $createInfoRepository,UserRepositoryInterface $userRepository) 
    {
        $this->createInfoRepository = $createInfoRepository;
        $this->userRepository = $userRepository;
    }
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function driverinfoList(Request $request)
    {  
        $res = DriverInfo::select('driver_info.*');;
        if ($request['action'] == 'search') {
            if (request()->has('driverinfo_name') && request()->input('driverinfo_name') != '') {
                $res->where('name', 'Like', '%' . request()->input('driverinfo_name') . '%');
            }
            if (request()->has('driverinfo_driverid') && request()->input('driverinfo_driverid') != '') {
                $res->where('driver_id', request()->input('driverinfo_driverid'));
            }
            if (request()->has('driverinfo_phone') && request()->input('driverinfo_phone') != '') {
                $res->where('phone', request()->input('driverinfo_phone'));
            }
        }else {
            request()->merge([
                'driverinfo_driverid' => null,
                'driverinfo_name' => null,
                'driverinfo_name' => null,
            ]);
        }       
        $res = $res->paginate(20);
        $township = $this->userRepository->getTownship();
            
        return view('admin.driver.driverinfo.index',[
            'list_result' => $res,
            'township'    => $township
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $township = $this->userRepository->getTownship();
        return view('admin.driver.driverinfo.create',[
            'township'     =>$township
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
            'name'            =>'required|min:3',
            'driver_profile' =>'mimes:jpeg,jpg,png|max:1000',
        ]); 
       
        $driverID = $this->createInfoRepository->generateDriverID();

        if($request->hasFile('driver_profile')){
            $image=$request->file('driver_profile');
            $extension = $image->extension();
            $image_name = $driverID. "_" . time() . "." . $extension;
        }else{
            $image_name="";
        } 

        if($request->hasFile('driver_license')){
            $license=$request->file('driver_license');
            $extension = $license->extension();
            $license_name = $driverID. "_" . time() . "." . $extension;
        }else{
            $license_name="";
        }      
                
        DB::beginTransaction();
        try{
            $insertData = array(
                'driver_id'         =>$driverID,
                'name'              =>$request->name,
                'date_of_birth'     =>$request->date_of_birth,
                'phone'             =>$request->phone,
                'password'          =>bcrypt($request->password),
                'township'          =>$request->township,
                'address'           =>$request->address,
                'profile_image'     =>$image_name,
                'type_of_license'   =>$license_name,
                'license_number'    =>$request->license_number,
                'year_of_experience'=>$request->year_of_experience,
                'start_date'        =>$request->start_date,
                'resign_date'       =>$request->resign_date,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $result=DriverInfo::insert($insertData);
                        
            if($result){
                if ($image_name != "") {
                    $image->move(public_path('assets/driver_images'),$image_name);   
                }
                if ($license_name != "") {
                    $license->move(public_path('assets/driver_licenses'),$license_name);   
                }               
                DB::commit();
                return redirect(url('admin/driver_info/list'))->with('success','Driver Information Created Successfully!');
            }else{
                return redirect()->back()->with('danger','Driver Information Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Driver Information Created Fail !');
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
        $township = $this->userRepository->getTownship();
        $res = DriverInfo::where('driver_id',$id)->get();
        return view('admin.driver.driverinfo.update',[
            'result'    =>$res,
            'township'  =>$township
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
            'name'            =>'required|min:3',
            'driver_profile'  =>'mimes:jpeg,jpg,png|max:1000',
        ]); 

        if($request->hasFile('driver_profile')){

            $previous_img=$request->previous_image;
            @unlink(public_path('/assets/driver_images/'. $previous_img));

            $image=$request->file('driver_profile');
            $extension = $image->extension();
            $image_name = $id. "_" . time() . "." . $extension;
        }else{
            $image_name="";
        } 

        if($request->hasFile('driver_license')){

            $previous_license=$request->previous_license;
            @unlink(public_path('/assets/driver_licenses/'. $previous_license));

            $license=$request->file('driver_license');
            $extension = $license->extension();
            $license_name = $id. "_" . time() . "." . $extension;
        }else{
            $license_name="";
        } 

        DB::beginTransaction();
        try{
            $infoData = array(
                'driver_id'         =>$id,
                'name'              =>$request->name,
                'date_of_birth'     =>$request->date_of_birth,
                'phone'             =>$request->phone,
                //'password'          =>bcrypt($request->password),
                'township'          =>$request->township,
                'address'           =>$request->address,
                'license_number'    =>$request->license_number,
                'year_of_experience'=>$request->year_of_experience,
                'start_date'        =>$request->start_date,
                'resign_date'       =>$request->resign_date,
                'updated_by'        =>$login_id,
                'updated_at'        =>$nowDate
            );
            
            if ($image_name != "") {
                $infoData['profile_image'] = $image_name;
            }
            if ($license_name != "") {
                $infoData['type_of_license'] = $license_name;
            }
            $driver = DriverInfo::where('driver_id', $id)->first();
            if ($driver && $driver->password != $request->password) {
                $infoData['password'] = bcrypt($request->password);
            }
            $result=DriverInfo::where('driver_id',$id)->update($infoData);
                      
            if($result){
                if ($image_name != "") {
                    $image->move(public_path('assets/driver_images'),$image_name);  
                } 
                if ($license_name != "") {
                    $license->move(public_path('assets/driver_licenses'),$license_name);  
                }  
                DB::commit();               
                return redirect(url('admin/driver_info/list'))->with('success','Driver Information Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Driver Information Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Driver Information Updared Fail !');
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
        try{
            $checkData = DriverInfo::where('driver_id',$id)->first();

            if (!empty($checkData)) {

                try {
                    // Attempt to delete the record
                    $res = DriverInfo::where('id',$id)->forceDelete();
                   
                    if($res){
                        DB::commit();
                        //To delete image and license file
                        $image=$checkData['profile_image'];
                        if($image !=''){
                            @unlink(public_path('/assets/driver_images/'. $image));
                        }
                        $file=$checkData['type_of_license'];
                        if($file != ''){
                            @unlink(public_path('/assets/driver_licenses/'. $file));
                        }     
                        return redirect(url('admin/driver_info/list'))->with('success','Driver Information Deleted Successfully!');
                    }
                } catch (\Illuminate\Database\QueryException $e) {
                    // Check if the exception is due to a foreign key constraint violation
                    if ($e->errorInfo[1] === 1451) {
                        return redirect()->back()->with('danger','Cannot delete this record because it is being used in other.');
                    }
                    return redirect()->back()->with('danger','An error occurred while deleting the record.');
                }
                
            }else{
                return redirect()->back()->with('danger','There is no result with this driver information.');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Driver Information Deleted Failed!');
        }
    }
}
