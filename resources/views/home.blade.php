@extends('layouts.dashboard')

@section('content')
<link href="{{ asset('css/home.css') }}" rel="stylesheet">
<script src="{{ asset('js/home.js') }}" defer></script>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6" id="dashboard-left">
            <div class="row">
                <div class="col-md-6" id="total-student">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th class="first-column" scope="col">Total Student</th>
                                <th class="last-column" scope="col"><div class="colval-border">150</div></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="first-column"><div class="colval-border">Grade (I)</div></td>
                                <td class="last-column"><div class="colval-border">10</div></td>
                            </tr>
                            <tr>
                                <td class="first-column"><div class="colval-border">Grade (II)</div></td>
                                <td class="last-column"><div class="colval-border">20</div></td>
                            </tr>
                            <tr>
                                <td class="first-column"><div class="colval-border">Grade (III)</div></td>
                                <td class="last-column"><div class="colval-border">30</div></td>
                            </tr>
                            <tr>
                                <td class="first-column"><div class="colval-border">Grade (IV)</div></td>
                                <td class="last-column"><div class="colval-border">40</div></td>
                            </tr>
                            <tr>
                                <td class="first-column"><div class="colval-border">Grade (V)</div></td>
                                <td class="last-column"><div class="colval-border">50</div></td>
                            </tr>
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
                            <tr>
                                <td class="first-column"><div class="colval-border">Grade (I)</div></td>
                                <td class="middle-column"><div class="colval-border">10</div></td>
                                <td class="last-column"><div class="colval-border">10</div></td>
                            </tr>
                            <tr>
                                <td class="first-column"><div class="colval-border">Grade (II)</div></td>
                                <td class="middle-column"><div class="colval-border">10</div></td>
                                <td class="last-column"><div class="colval-border">20</div></td>
                            </tr>
                            <tr>
                                <td class="first-column"><div class="colval-border">Grade (III)</div></td>
                                <td class="middle-column"><div class="colval-border">10</div></td>
                                <td class="last-column"><div class="colval-border">30</div></td>
                            </tr>
                            <tr>
                                <td class="first-column"><div class="colval-border">Grade (IV)</div></td>
                                <td class="middle-column"><div class="colval-border">10</div></td>
                                <td class="last-column"><div class="colval-border">40</div></td>
                            </tr>
                            <tr>
                                <td class="first-column"><div class="colval-border">Grade (V)</div></td>
                                <td class="middle-column"><div class="colval-border">10</div></td>
                                <td class="last-column"><div class="colval-border">50</div></td>
                            </tr>
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
                                <th class="last-column" scope="col"><div class="colval-border">150</div></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="first-column"><div class="colval-border">Grade (I)</div></td>
                                <td class="last-column"><div class="colval-border">10</div></td>
                            </tr>
                            <tr>
                                <td class="first-column"><div class="colval-border">Grade (II)</div></td>
                                <td class="last-column"><div class="colval-border">20</div></td>
                            </tr>
                            <tr>
                                <td class="first-column"><div class="colval-border">Grade (III)</div></td>
                                <td class="last-column"><div class="colval-border">30</div></td>
                            </tr>
                            <tr>
                                <td class="first-column"><div class="colval-border">Grade (IV)</div></td>
                                <td class="last-column"><div class="colval-border">40</div></td>
                            </tr>
                            <tr>
                                <td class="first-column"><div class="colval-border">Grade (V)</div></td>
                                <td class="last-column"><div class="colval-border">50</div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6" id="exam-result">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th class="first-column" scope="col">Payment</th>
                                <th class="last-column" scope="col">
                                    <select class="form-control" id="monthSelect" name="month">
                                        <option value="00">Month</option>
                                        <option value="01">January</option>
                                        <option value="02">February</option>
                                        <option value="03">March</option>
                                        <option value="04">April</option>
                                        <option value="05">May</option>
                                        <option value="06">June</option>
                                        <option value="07">July</option>
                                        <option value="08">August</option>
                                        <option value="09">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="first-column"><div class="colval-border">School Fee</div></td>
                                <td class="last-column"><div class="colval-border">10</div></td>
                            </tr>
                            <tr>
                                <td class="first-column"><div class="colval-border">Book Fee</div></td>
                                <td class="last-column"><div class="colval-border">20</div></td>
                            </tr>
                            <tr>
                                <td class="first-column"><div class="colval-border">Registration Fee</div></td>
                                <td class="last-column"><div class="colval-border">30</div></td>
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
                        @php $i=1;@endphp
                        @foreach($event_list as $res)
                            <div>
                                <div class="event-date">{{date('d M,Y',strtotime($res->event_from_date))}}</div>
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
