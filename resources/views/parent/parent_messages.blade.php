@extends('parent.parent_layout')

@section('parent_content')
<link href="{{ asset('css/home.css') }}" rel="stylesheet">
<script src="{{ asset('js/home.js') }}" defer></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<style>
    #app>div.page{
        top:0px !important;
    }
    .message-header,
    .cost,
    .amount {
        text-align:center;
    }
    .message-date {
        font-weight: 700;
        color: #0F4D19;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
    }

    .message-icon {
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }
</style>
    <header>
        <div class="row system-bar">
            <div class="col-3 left">
                <!--<a href="#" class="back-button">Back</a>-->
            </div>
            <div class="col-6 centre">MESSAGE</div>
            <div class="col-3 right">
                <!--<a href="#" class="plus-button"><i class="bi bi-plus-lg"></i></a>-->
            </div>
        </div>
    </header>
    <div class="page">
        @include('layouts.error')
        <div class="message-container mb-5">
            <!--<div class="row mx-2">                
                <div class="card message-detail" id="parent-card">
                    <div class="card-body">
                        <div class="message-header"><b>Description</b></div>
                        <p>
                            Some quick example text to build on the card title and make up the bulk of the card's content.
                        </p>
                        <div class="mt-1 cost">Cost</div> 
                        <div class="amount">15,000MMK</div>
                        <div class="mt-1 address">
                            Address : xxxxxxxxxxxxxxxxx
                        </div>                       
                    </div>   
                </div>                   
            </div>-->

            <div class="row mb-3" style="background:#e9ecef;">
                <h5 class="mt-1">List</h5>
            </div>
            <div class="row mx-2">
                <div class="message-list">
                    @foreach ($messages as $m)
                        <div class="card mt-3" id="parent-card">
                            <div class="row">
                                <div class="col-1"></div>
                                <div class="col-10">
                                    <div class="row p-2">
                                        <div class="col-3 message-date">{{$m['date']}}</div> 
                                        <div class="col-7 message-content">
                                            {{$m['title']}} <br />
                                            {{$m['description']}} <br />
                                            {{$m['remark']}} 
                                        </div> 
                                        <div class="col-2 message-icon"><i class="bi bi-chevron-right"></i></div>
                                    </div>
                                </div>
                                <div class="col-1"></div>
                            </div>  
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection