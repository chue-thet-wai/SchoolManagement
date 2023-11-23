@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
    <h1>Role and Permission</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">User Management</li>
            <li class="breadcrumb-item active">Role and Permission</li>
        </ol>
    </nav>
    @include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    <div class="card-body">
        <br />
        
        <div class="row g-4">
            <div class="col-md-1"></div>
            <div class="col-md-9" style='color:#012970;'>
                <h4><b>Update Activity</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{route('role_permission.index')}}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>

        <br />
        <form method="POST" action="{{route('role_permission.update',$result[0]->id)}}" enctype="multipart/form-data">
            @csrf
            <br />
            {{method_field('PUT')}}
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="role_name"><b>Role<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="text" name="role_name" class="form-control" value="{{$result[0]->name}}" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for=""><b>Permission</b></label>
                        <div class="col-sm-10">
                            @foreach($permission_list as $a)
                                @php if (in_array($a->id,$choose_permissions)) { @endphp
                                    <input type="checkbox" class="permission_check" name="checkPermission[]"  value="{{$a->id}}" checked> {{$a->sub_menu}}
                                @php } else { @endphp
                                    <input type="checkbox" class="permission_check" name="checkPermission[]"  value="{{$a->id}}"> {{$a->sub_menu}}
                                @php } @endphp
                                <br />
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-2">
                    <div class="d-grid mt-4">
                        <input type="submit" value="Update" class="btn btn-primary">
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <br />
        </form>
        <br />
    </div>
</section>


@endsection