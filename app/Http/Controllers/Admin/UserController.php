<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show_userlist()
    {
        $user_res = DB::table('users')
           ->paginate(10);
        return view('admin.user.userlist',['user_list' => $user_res]);
    }

    public function show_profile()
    {
        $user_id=Auth::id();
        $user_res = DB::select('select * from users where id="'.$user_id.'"');
       
        return view('admin.user.profile',['user_res' => $user_res]);
    }

    public function logout()
    {        
        Auth::logout();
        return redirect('/');
    }

    public function edit_user($id)
    {
        $user_res = DB::select('select * from users where id="'.$id.'"');
        return view('admin.user.edit',['user_res' => $user_res]);
    }

    public function update_user(Request $request,$id)
    {
        $result=DB::table('users')
        ->where('id', $id) 
        ->limit(1)
        ->update(['role' => $request->role_status]);
        if($result){
            return redirect(url('admin/userlist'))->with('success','User Updated Successfully!');
        } else{
            return redirect(url('admin/userlist'))->with('success','No change made.');
        }
    }
    public function destroy_user($id)
    {
        $result = DB::delete('Delete from users where id="'.$id.'"');
        if($result){
            return redirect(url('admin/userlist'))->with('success','User Deleted Successfully!');
        }      
        
    }
}
