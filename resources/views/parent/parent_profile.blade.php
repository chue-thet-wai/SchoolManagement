@extends('parent.parent_layout')

@section('parent_content')
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">

    <!-- Bootstrap 5 JS Bundle (including Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .part-title {
            text-align: center;
            color: #0F4D19;
        }

        #edit-profile {
            border-radius: 0px;
            border-bottom-left-radius: 5px;
            width: 100%;
            color: #ffffff;
            background-color: #0F4D19;
        }

        #change-password {
            border-radius: 0px;
            border-bottom-right-radius: 5px;
            width: 100%;
            color: #ffffff;
            background: #E6D74F;
            border: 1px solid #E6D74F;
        }

        .icon-style {
            margin-right: 5px;
            padding: 0px;
            font-size: 1.2em;
            color: #ffffff;
        }

        #view-profile {
            color: #ffffff;
            background: #E6D74F;
        }

        .additional-link {
            background: #e9ecef;
            border: 1px solid #ffffff;
            padding: 5px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            color: #0F4D19;
            margin: 3px;
        }

        #carousel-profile {
            background-color: #ffffff;
        }

        .carousel-indicators li.active {
            background-color: #0F4D19;
        }

        .carousel-indicators li {
            width: 2%;
            height: 8px;
            border: 1px solid #0F4D19;
            background-color: #fff;
        }

        .item {
            text-align: center;
            font-size: 20px;
            font-weight: 700;
        }

        /* Hide carousel controls */
        .carousel-control-prev,
        .carousel-control-next {
            display: none;
        }
        #carousal-dot {
            width: 2%;
            height: 8px;
            border-radius: 50%;
            border: 1px solid #0F4D19;
        }
    </style>

    <header>
        <div class="row system-bar">
            <div class="col-3 left">
                <!--<a href="#" class="back-button">Back</a>-->
            </div>
            <div class="col-6 centre">PROFILE</div>
            <div class="col-3 right">
                <!--<a href="#" class="plus-button"><i class="bi bi-plus-lg"></i></a>-->
            </div>
        </div>
    </header>

    <div class="page">
        @include('layouts.error')
        <div class="profile-container">
            <h5 class="part-title">Your Details</h5>
            <div class="row part-title">
                <div class="col-1"></div>
                <div class="col-10">
                    <div class="card">
                        <p class="mt-2 mb-1">{{$guardian_data->name}}</p>
                        <p class="mb-1"></p>
                        <p class="mb-3">{{$guardian_data->phone}}</p>
                        <div class="row">
                            <div class="col-6" style="padding-right:0px;">
                                <a href="{{ url('parent/edit_profile') }}" class="btn btn-block btn-primary" name="edit-profile" id="edit-profile" value="Edit Profile">
                                    <i class="bi bi-person-check icon-style"></i> Edit Profile
                                </a>
                            </div>
                            <div class="col-6" style="padding-left:0px;">
                                <a href="{{ url('parent/change_password') }}" class="btn btn-block" name="change-password" id="change-password" value="Change Password">
                                    <i class="bi bi-lock icon-style"></i> Change Password
                                </a>
                            </div>
                        </div>
                    </div>                
                </div>
                <div class="col-1"></div>
            </div>
            <br />
            <h5 class="part-title">Your Childrens</h5>
            <div id="carousel-profile" class="carousel slide white box-shadow-rounded" data-bs-ride="carousel" style="height: 160px;">
                <div id="aux-box-carousel" class="carousel-inner">
                    @foreach ($student_data as $index => $student)
                        <div class="carousel-item{{ $index === 0 ? ' active' : '' }}">
                            <div class="row">
                                <div class="col-1"></div>
                                <div class="col-10 card carousel-card">
                                    <div class="d-flex justify-content-between align-items-center p-2">
                                        <div class="d-flex align-items-center children-card">
                                            <img src="{{ asset('parent/profile.png') }}" alt="{{ $student->name }} Image">
                                            <div class="ms-md-5" style="line-height: 1;">
                                                <p class="mt-2">{{ $student->name }}</p>
                                                <p>{{ $student->grade_name }}</p>
                                                <p>{{ $student->student_id }}</p>
                                            </div>
                                        </div>
                                        <div class="ms-auto">
                                            <a class="btn btn-block btn-sm" href="{{ url('parent/student_profile/' . $student->student_id) }}" id="view-profile">
                                                View Profile
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-1"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <ol id="aux-box-carousel-pagination" class="carousel-indicators">
                    @foreach ($student_data as $index => $student)
                        <li data-bs-target="#carousel-profile" id="carousal-dot" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></li>
                    @endforeach
                </ol>
            </div>   
            <br />
            <h5 class="part-title">Additional Features</h5>
            <div class="additional-feature">
                <div class="row">
                    <div class="col-1"></div>
                    <a href="{{ url('parent/contacts') }}" class="col-10 additional-link" href="">
                        <i class="bi bi-person-add"></i> Contacts <i class="bi bi-chevron-right"></i>
                    </a>
                    <div class="col-1"></div>
                </div>
                <div class="row">
                    <div class="col-1"></div>
                    <a class="col-10 additional-link" href="">
                        <i class="bi bi-person-bounding-box"></i> Chats <i class="bi bi-chevron-right"></i>
                    </a>
                    <div class="col-1"></div>
                </div>           
            </div>
        </div>
    </div>
@endsection