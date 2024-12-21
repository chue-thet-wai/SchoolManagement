@extends('parent.parent_layout')

@section('parent_content')
    <style>
        .student-data {
            background: #ede2e2;
            text-align: center;
            justify-content: center;
            align-items: center;
            padding: 5px;
            font-weight: 600;
            margin-top: -10px;
        }

        .billing-data {
            margin-top: 20px;
        }

        .billing-data .card {
            background: #ede2e2;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .billing-data table {
            width: 100%;
            border-collapse: collapse;
            border-radius:10px;
        }

        .billing-data td {
            padding: 8px;
            text-align: center;
        }

        .billing-data th {
            background-color: #2b7a4a;
            color: #fff;
            padding: 8px;
            text-align: center;
        }
        .billing-status{
            color: #fff;
            border-radius: 10px 10px 0px 0px;
            border: none;
            padding: 2% 20% 2% 19%;
            width:80%;
        }
    </style>
    <header>
        <div class="system-bar">
            <div class="left">
                <a href="{{ url('parent/student_profile/'.$student_id) }}" class="back-button">Back</a>
            </div>
            <div class="centre">BILLING</div>
            <div class="right">
                <!--<a href="#" class="plus-button"><i class="bi bi-plus-lg"></i></a>-->
            </div>
        </div>
    </header>
    <div class="page">
        @include('layouts.error')
        <div class="row student-data">
            <div class="col-6">
                <p>Fee List</p>
            </div>
            <div class="col-6">
                <p class='m-0 p-0'>({{$student_data->name}})</p>
                <p class='m-0 p-0'>{{$student_data->class_name}}</p>
            </div>
        </div>
        <div class="billing-list">
            @if (count($unpaid_invoices)>0)
            <div class="mt-4 billing-data">
                <div class="row">
                    <div class="col-8"></div>
                    <div class="col-4">
                        <div class="btn btn-danger billing-status">Unpaid</div>
                    </div>
                </div>
                <div class="card" id="parent-card">
                    <table>
                        <thead>
                            <tr>
                                <th style=" border-top-left-radius: 10px;">Invoice Id</th>
                                <th>Type</th>
                                <th>Due Date</th>
                                <th style=" border-top-right-radius: 10px;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($unpaid_invoices as $invoice)
                            <tr>
                                <td>{{$invoice->invoice_id}}</td>
                                <td>Mid Terms</td>
                                <td>DD/MM/YY</td>
                                <td>{{$invoice->net_total}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
            @if (count($paid_invoices)>0)
            <div class="mt-4 billing-data">
                <div class="row">
                    <div class="col-8"></div>
                    <div class="col-4">
                        <div class="btn billing-status" style="background:#00C314;">Paid</div>
                    </div>
                </div>
                <div class="card" id="parent-card">
                    <table>
                        <thead>
                            <tr>
                                <th style=" border-top-left-radius: 10px;">Invoice Id</th>
                                <th>Type</th>
                                <th>Due Date</th>
                                <th style=" border-top-right-radius: 10px;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paid_invoices as $invoice)
                            <tr>
                                <td>{{$invoice->invoice_id}}</td>
                                <td>Mid Terms</td>
                                <td>DD/MM/YY</td>
                                <td>{{$invoice->net_total}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
