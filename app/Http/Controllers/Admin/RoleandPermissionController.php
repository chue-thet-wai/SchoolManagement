<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\CreateInfoRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\Permission;

class RoleandPermissionController extends Controller
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
        $res = Role::paginate(10);
        
        return view('admin.roleandpermission.index',[
            'list_result'  => $res]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission_list = $this->getPermissions();               

        return view('admin.roleandpermission.create',[
            'permission_list'      =>$permission_list,
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
            'role_name'       =>'required',
        ]); 

        $checkPermission = $request->checkPermission;
        if (empty($checkPermission)) {
            return redirect()->back()->with('danger','Please check permission !');
        }
                
        DB::beginTransaction();
        try{

            $insertRole = array(
                'name'               =>$request->role_name,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $role_id=Role::insertGetId($insertRole);
                        
            if($role_id){     
                for ($i=0;$i<count($checkPermission);$i++) {
                    $permission_id = $checkPermission[$i];
                    $insertRolePermission = array(
                        'role_id'           =>$role_id,
                        'permission_id'     =>$permission_id,
                        'created_by'        =>$login_id,
                        'updated_by'        =>$login_id,
                        'created_at'        =>$nowDate,
                        'updated_at'        =>$nowDate
                    );
                    $res=DB::table('role_permission')->insert($insertRolePermission);
                    if (!$res) {
                        return redirect()->back()->with('danger','Role and Permission Insert Fail !');
                    }

                }
                DB::commit();
                return redirect(route('role_permission.index'))->with('success','Role and Permission Insert Successfully!');
            }else{
                return redirect()->back()->with('danger','Role and Permission Insert Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Role and Permission Insert Fail !');
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
        $permission_list = $this->getPermissions();                  

        $res = Role::where('id',$id)->get();
      
        $chkRolePermission = DB::table('role_permission')
                                        ->where('role_id',$id)
                                        ->whereNull('deleted_at')
                                        ->get()->toArray();
        $choose_permissions = array_column($chkRolePermission,'permission_id');
        return view('admin.roleandpermission.update',[
            'permission_list'   =>$permission_list,
            'choose_permissions'=>$choose_permissions,
            'result'            =>$res]
        );
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
            'role_name'       =>'required',
        ]);
        
        $checkPermission = $request->checkPermission;
        if (empty($checkPermission)) {
            return redirect()->back()->with('danger','Please check permission !');
        }
       

        DB::beginTransaction();
        try{
            $roleData = array(
                'name'               =>$request->role_name,
                'updated_by'         =>$login_id,
                'updated_at'         =>$nowDate

            );
            
            $result=Role::where('id',$id)->update($roleData);
                      
            if($result){
                $chkRolePermission = DB::table('role_permission')
                                        ->where('role_id',$id)
                                        ->whereNull('deleted_at')
                                        ->get()->toArray();
                $oldPermissionId = array_column($chkRolePermission,'permission_id');
                
                $addPermission = array_diff($checkPermission,$oldPermissionId);
                $delPermission = array_diff($oldPermissionId,$checkPermission);

                //delete permission
                if (!empty($delPermission)) {
                    foreach($delPermission as $key=>$value) {
                        $permission_id = $value;  
                        $res=DB::table('role_permission')
                            ->where('permission_id',$permission_id)
                            ->update(['deleted_at'=>$nowDate]);
                    }
                }
                

                //add new permission 
                if (!empty($addPermission)) {
                    foreach ($addPermission as $key=>$value) {
                        $permission_id = $value;                    
                        $insertRolePermission = array(
                            'role_id'           =>$id,
                            'permission_id'     =>$permission_id,
                            'created_by'        =>$login_id,
                            'updated_by'        =>$login_id,
                            'created_at'        =>$nowDate,
                            'updated_at'        =>$nowDate
                        );
                        $res=DB::table('role_permission')->insert($insertRolePermission);
                        if (!$res) {
                            return redirect()->back()->with('danger','Role and Permission Updated Fail !');
                        }
                    }
                }
                
                DB::commit();               
                return redirect(route('role_permission.index'))->with('success','Role and Permission Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Role and Permission Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Role and Permission Updated Fail !');
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
            $checkData = Role::where('id',$id)->first();
            $nowDate  = date('Y-m-d H:i:s', time());

            if (!empty($checkData)) {
                
                $res = Role::where('id',$id)->delete();
                if($res){
                    $res=DB::table('role_permission')
                        ->where('role_id',$id)
                        ->update(['deleted_at',$nowDate]);
                    DB::commit();
                    //To return list
                    return redirect(route('role_permission.index'))->with('success','Role and Permission Deleted Successfully!');
                }
            }else{
                return redirect()->back()->with('error','There is no result with this role and permission.');
            }
           

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Role and Permission Deleted Failed!');
        }
    }

    public function getPermissions() {
        $permission_list = Permission::all();      
        return $permission_list;
    }
}
