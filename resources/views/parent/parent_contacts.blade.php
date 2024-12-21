@extends('parent.parent_layout')

@section('parent_content')
    <style>
        .rounded-circle {
            border-radius: 50%;
            border: 2px solid #2B7A4A;
        }
    </style>
    <header>
        <div class="system-bar">
            <div class="left">
                <a href="{{ url('parent/home') }}" class="back-button">Back</a>
            </div>
            <div class="centre">CONTACTS</div>
            <div class="right">
                <!--<a href="#" class="plus-button"><i class="bi bi-plus-lg"></i></a>-->
            </div>
        </div>
    </header>
    <div class="page">
        @include('layouts.error')
        <p>Contacts List</p>
        <div class="container">
            <ul class="list-group">
                @foreach ($contact_data as $contact)
                <li class="list-group-item">
                    <div class="d-flex justify-content-start align-items-center">
                        <img src="{{ asset('assets/images/profile.jpg') }}" alt="Left Image" class="mr-3 rounded-circle" width="50">
                        <div class="ms-md-4" style="line-height:1px;padding:3px;">
                            <p class="mt-2">{{$contact['name']}}</p>
                            <p class="ms-2"><small>{{$contact['relationship']}}</small></p>
                            <p>Ph : {{$contact['phone']}}</p>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection