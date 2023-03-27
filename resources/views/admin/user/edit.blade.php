@extends('layouts.dashboard')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header contains-buttons">
                    <h5 class="card-title">User Details
                        <a class="btn btn-sm pull-right" href="{{ url('admin/userlist') }}" id="form-header-btn"> Back</a>
                    </h5>
                </div>

                <div class="card-body"> 
                    <div><h5>Name :{{$user_res[0]->name}} </h5></div>
                    <div><h5>Email  :{{$user_res[0]->email}} </h5></div>
                   
                    <br />
                    <div>
                        <form method="post" action="{{ url('admin/userlist/update/'.$user_res[0]->id) }}" style="display: inline;" >
                            @csrf
                            <div class="form-group"> 
                                <label for="">Role</label>
                                <br />                            
                                <label class="radio-inline"><input type="radio" id="admin" name="role_status" value="1" 
                                @if($user_res[0]->role == "1")
                                    checked
                                @endif
                                > Super Admin</label>
                                <label class="radio-inline"><input type="radio" id="user" name="role_status" value="2"
                                @if($user_res[0]->role == "2")
                                    checked
                                @endif
                                > Branch Admin</label>
                                <label class="radio-inline"><input type="radio" id="user" name="role_status" value="2"
                                @if($user_res[0]->role == "2")
                                    checked
                                @endif
                                > Teacher</label>
                                <label class="radio-inline"><input type="radio" id="user" name="role_status" value="2"
                                @if($user_res[0]->role == "2")
                                    checked
                                @endif
                                > Driver</label>
                            </div>
                            <input type="submit" class="btn btn-sm btn-warning" value="Update">
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection