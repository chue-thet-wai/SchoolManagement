@extends('layouts.dashboard')

@section('content')
<link href="{{ asset('css/home.css') }}" rel="stylesheet">
<script src="{{ asset('js/home.js') }}" defer></script>
<style>
    #total-student,
    #exam-result,
    #waiting-student,
    #payment-result
    {
        width: 250px; 
        margin-bottom:10px;
        max-height: 400px; 
        overflow-y: auto;
    }
    .custom-table{
        height:250px;
    }
    .custom-table tr {
        height:40px;
    }
    ::webkit-scrollbar {
        width: 3px;
    }

    ::webkit-scrollbar-thumb {
        background-color: #888;
        border-radius: 6px;
    }

    ::webkit-scrollbar-track {
        background-color: #f1f1f1;
    }
</style>
<script>
    window.onload = function() {
        getPaymentData();
    };
    function getPaymentData(){
        var paymentDate = $("#monthSelect").val();
        $.ajax({
            type:'POST',
            url:'/dashboard_payment',
            data:{
                _token :'<?php echo csrf_token() ?>',
                payment_date  : paymentDate
            },
           
           success:function(data){
                // Select the table body
                var tbody = $('#payment-result-tbody');
                if (data.msg == 'found') {
                    tbody.empty();
                    $.each(data.payment_data, function(key, value) {
                        var newRow = $('<tr>' +
                                        '<td class="first-column"><div class="colval-border">' + value['name'] + '</div></td>' +
                                        '<td class="last-column"><div class="colval-border">' + value['amount'] + '</div></td>' +
                                    '</tr>');

                        tbody.append(newRow);
                    });
                } else {
                    tbody.empty();
                }             
            }
        });
    }
</script>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6" id="dashboard-left">
            <div class="row">
                <div class="col-md-6" id="total-student">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th class="first-column" scope="col">Total Student</th>
                                <th class="last-column" scope="col"><div class="colval-border">{{$student_totalcount}}</div></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($student_count as $key=>$value)
                            <tr>
                                <td class="first-column"><div class="colval-border">{{$grade_list[$key]}}</div></td>
                                <td class="last-column"><div class="colval-border">{{$value}}</div></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6" id="exam-result">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th class="first-column" scope="col">Grade</th>
                                <th class="middle-column" scope="col">Pass</th>
                                <th class="last-column" scope="col">Fail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($exam_count as $key => $value)
                            <tr>
                                <td class="first-column"><div class="colval-border">{{$grade_list[$key]}}</div></td>
                                <td class="middle-column"><div class="colval-border">{{$value['pass']}}</div></td>
                                <td class="last-column"><div class="colval-border">{{$value['fail']}}</div></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6" id="waiting-student">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th class="first-column" scope="col">Total Waiting List</th>
                                <th class="last-column" scope="col"><div class="colval-border">{{$waiting_totalcount}}</div></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($waiting_count as $waiting)
                            <tr>
                                <td class="first-column"><div class="colval-border">{{$waiting['grade_name']}}</div></td>
                                <td class="last-column"><div class="colval-border">{{$waiting['count']}}</div></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6" id="payment-result">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th class="first-column" scope="col">Payment</th>
                                <th class="last-column" scope="col">
                                    <!--<input type="date" class="form-control" id="payment_date" onchange="getPaymentData()">-->
                                    <select class="form-control" id="monthSelect" name="month" onchange="getPaymentData()">
                                        @for ($i = 1; $i <= 12; $i++)
                                            @php
                                                $currentYear = date('Y');
                                                $monthName = date("M", mktime(0, 0, 0, $i, 1)); // Use "M" for short month names
                                                $monthValue = date("Y-m", mktime(0, 0, 0, $i, 1));
                                            @endphp
                                            <option value="{{ $monthValue }}" {{ date('Y-m') == $monthValue ? 'selected' : '' }}>
                                                {{ $monthName }}
                                            </option>
                                        @endfor
                                    </select>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="payment-result-tbody">
                            <tr>
                                <td class="first-column"><div class="colval-border">School Fee</div></td>
                                <td class="last-column"><div class="colval-border">0</div></td>
                            </tr>
                            <tr>
                                <td class="first-column"><div class="colval-border">Book Fee</div></td>
                                <td class="last-column"><div class="colval-border">0</div></td>
                            </tr>
                            <tr>
                                <td class="first-column"><div class="colval-border">Registration Fee</div></td>
                                <td class="last-column"><div class="colval-border">0</div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>            
        </div>
        <div class="col-md-6" id="dashboard-right">
            <div class="row ms-2">
                <div class="card" style="background:#e9ecef;">
                    <div class="card-header" style="background:#e9ecef;"><strong>Event Calendar</strong></div>
                    <div class="card-body">
                        <div class="calendar calendar-first" id="calendar_first">
                            <div class="calendar_header">
                                <button class="switch-month switch-left"> <i class="bi bi-caret-left-fill"></i></button>
                                <h2></h2>
                                <button class="switch-month switch-right"> <i class="bi bi-caret-right-fill"></i></button>
                            </div>
                            <div class="calendar_weekdays"></div>
                            <div class="calendar_content"></div>
                        </div>    
                    </div> 
                </div>                    
            </div>
            <div class="row ms-2">
                <div class="event-list">
                    @if(!empty($event_list) && $event_list->count())
                        @php 
                            $i=1; 
                            $colorArray=array('#9FCDFF','#93E4D1','#E1DFB1','#CFAC8C',
                            '#DC75E5','#90D797','#A4A3EB','#CAE895'.
                            '#479EFF','#FB8D8D');
                         @endphp
                        @foreach($event_list as $res)
                            @php 
                                $randomColorKey = array_rand($colorArray);
                                $randomColor = $colorArray[$randomColorKey];
                            @endphp
                            <div>
                                <div class="event-date" style="background-color: {{$randomColor}}">{{date('d M,Y',strtotime($res->event_from_date)).'~'.date('d M,Y',strtotime($res->event_to_date))}}</div>
                                <div class="event-description">                                                
                                    <p>{{$res->description}}</p>
                                </div>
                            </div>
                        @endforeach
                    @else 
                        <p> There is no event yet ! </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
