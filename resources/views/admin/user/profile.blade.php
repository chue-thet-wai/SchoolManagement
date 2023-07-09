@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
            <div class="card">
                <div class="card-header">My Profile</div>
                <div class="card-body">
                    <div class="well well-sm">
                        <div class="row">
                            <div class="col-sm-6 col-md-4">
                                <img src="{{asset($profile_image)}}" alt="" class="img-rounded img-responsive" 
                                style="width:70px;height:70px;"/>
                            </div>
                            <div class="col-sm-6 col-md-8">
                                <h4>{{$user_res[0]->name}} </h4>
                                <br />
                                <b>Email : </b>{{$user_res[0]->email}}
                                <br />
                                <br />
                                @php  
                                    $role = Auth::user()->role;
                                    if ($role == 2) {
                                    $roleName = 'Branch Admin';
                                    } else if ($role== 3){
                                    $roleName = 'Teacher';
                                    }  else {
                                    $roleName = 'Super Admin';
                                    }    
                                @endphp
                                <b>Role : </b>{{$roleName}}
                                <br />
                                <br />
                                @php
                                    $joinDate = $user_res[0]->created_at;
                                    if($joinDate !='') {
                                        $joinDate = date("Y-m-d", strtotime($joinDate));
                                    }
                                @endphp
                                <b>Join Date : </b>{{$joinDate}}
                                <br />
                                <br />
                                <a href="{{ url('admin/logout') }}" class="btn btn-sm btn-primary">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection