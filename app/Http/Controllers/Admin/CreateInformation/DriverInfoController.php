<?php

namespace App\Http\Controllers\Admin\CreateInformation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\CreateInfoRepositoryInterface;
use App\Models\DriverInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DriverInfoController extends Controller
{
    private CreateInfoRepositoryInterface $createInfoRepository;

    public function __construct(CreateInfoRepositoryInterface $createInfoRepository) 
    {
        $this->createInfoRepository = $createInfoRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $res = DriverInfo::paginate(10);
        return view('admin.createinformation.driverinfo.index',['list_result' => $res]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.createinformation.driverinfo.create');
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
            'driver_profile' =>'mimes:jpeg,jpg,png | max:1000',
        ]); 
       
        $driverID = $this->createInfoRepository->generateDriverID();

        if($request->hasFile('driver_profile')){
            $image=$request->file('driver_profile');
            $extension = $image->extension();
            $image_name = $driverID. "_" . time() . "." . $extension;
        }else{
            $image_name="";
        } 

        if($request->hasFile('driver_licence')){
            $licence=$request->file('driver_licence');
            $extension = $licence->extension();
            $licence_name = $driverID. "_" . time() . "." . $extension;
        }else{
            $licence_name="";
        }      
                
        DB::beginTransaction();
        try{
            $insertData = array(
                'driver_id'         =>$driverID,
                'name'              =>$request->name,
                'date_of_birth'     =>$request->date_of_birth,
                'phone'             =>$request->phone,
                'address'           =>$request->address,
                'profile_image'     =>$image_name,
                'type_of_licence'   =>$licence_name,
                'year_of_experience'=>$request->year_of_experience,
                'start_date'        =>$request->start_date,
                'resign_date'       =>$request->resign_date,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id
            );
            $result=DriverInfo::insert($insertData);
                        
            if($result){
                if ($image_name != "") {
                    $image->move(public_path('assets/driver_images'),$image_name);   
                }
                if ($licence_name != "") {
                    $licence->move(public_path('assets/driver_licences'),$licence_name);   
                }               
                DB::commit();
                return redirect(route('driver_info.index'))->with('success','Driver Information Created Successfully!');
            }else{
                return redirect()->back()->with('danger','Driver Information Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Driver Information Created Fail !');
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
        $res = DriverInfo::where('driver_id',$id)->get();
        return view('admin.createinformation.driverinfo.update',['result'=>$res]);
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
            'driver_profile'  =>'mimes:jpeg,jpg,png | max:1000',
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

        if($request->hasFile('driver_licence')){

            $previous_licence=$request->previous_licence;
            @unlink(public_path('/assets/driver_licences/'. $previous_licence));

            $licence=$request->file('driver_licence');
            $extension = $licence->extension();
            $licence_name = $id. "_" . time() . "." . $extension;
        }else{
            $licence_name="";
        } 

        DB::beginTransaction();
        try{
            $infoData = array(
                'driver_id'         =>$id,
                'name'              =>$request->name,
                'date_of_birth'     =>$request->date_of_birth,
                'phone'             =>$request->phone,
                'address'           =>$request->address,
                'year_of_experience'=>$request->year_of_experience,
                'start_date'        =>$request->start_date,
                'resign_date'       =>$request->resign_date,
                'updated_by'        =>$login_id
            );
            
            if ($image_name != "") {
                $infoData['profile_image'] = $image_name;
            }
            if ($licence_name != "") {
                $infoData['type_of_licence'] = $licence_name;
            }
            $result=DriverInfo::where('driver_id',$id)->update($infoData);
                      
            if($result){
                if ($image_name != "") {
                    $image->move(public_path('assets/driver_images'),$image_name);  
                } 
                if ($licence_name != "") {
                    $licence->move(public_path('assets/driver_licences'),$licence_name);  
                }  
                DB::commit();               
                return redirect(route('driver_info.index'))->with('success','Driver Information Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Driver Information Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Driver Information Updared Fail !');
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
                
                $res = DriverInfo::where('driver_id',$id)->delete();
                if($res){
                    //To delete image and licence file
                    $image=$checkData['profile_image'];
                    if($image !=''){
                        @unlink(public_path('/assets/driver_images/'. $image));
                    }
                    $file=$checkData['type_of_licence'];
                    if($file != ''){
                        @unlink(public_path('/assets/driver_licences/'. $file));
                    }               

                }
            }else{
                return redirect()->back()->with('error','There is no result with this driver information.');
            }
            DB::commit();
            //To return list
            return redirect(route('driver_info.index'))->with('success','Driver Information Deleted Successfully!');

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Driver Information Deleted Failed!');
        }
    }
}
