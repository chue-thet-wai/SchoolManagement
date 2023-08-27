@extends('layouts.dashboard')

@section('content')
<script>
    function selectAll(){
        if($('#select_all').is(':checked')){
            $('.menu_check').each(function(){
                this.checked = true;
            });
        }else{
             $('.menu_check').each(function(){
                this.checked = false;
            });
        }
    }

    function unselectAll() {
        if($('.menu_check:checked').length == $('.menu_check').length){
            $('#select_all').prop('checked',true);
        }else{
            $('#select_all').prop('checked',false);
        }
    }  
    
    function getStudentData(){
        var cardNo = $("#card_id").val();
        $.ajax({
           type:'POST',
           url:'/admin/cash_counter/card_data',
           data:{
                _token :'<?php echo csrf_token() ?>',
                card_id  : cardNo
            },
           
           success:function(data){
                if (data.msg == 'found') {
                    $("#student_data_msg").html('Student data found!.');
                    $("#student_id").html(data.student_id);
                    $("#student_name").html(data.student_name);
                    $("#card_amount").html(data.card_amont);
                    $('#student_info_block').show();
                } else {
                    $("#student_data_msg").html('Student data not found!.');
                    $("#student_id").html('');
                    $("#student_name").html('');
                    $("#card_amount").html('');
                    $('#student_info_block').hide();
                }             
            }
        });
    }
    function changeQtyAmount(menuid) {
        var x = document.getElementById(menuid+"-qty").value;
        const myArray = x.split("-");
        var qty   = myArray[0];
        var id    = myArray[1];
        var price = myArray[2];
        
        var qtyPrice = price * qty;         
        $("#"+id+"-qtytotal").html(qtyPrice);
    }
    
</script>
<div class="pagetitle">
    <h1>Sale Counter</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Shop</li>
            <li class="breadcrumb-item active">Sale Counter</li>
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
                <h4><b>Update Food Order</b></h4>
            </div>
            <div class="col-md-1">
                <a class="btn btn-sm btn-primary" href="{{ url('admin/menu/list') }}" id="form-header-btn"> Back</a>
            </div>
            <div class="col-md-1"></div>
        </div>

        <br />
        <form method="POST" action="{{ url('admin/food_order/update/'.$result[0]->id) }}" enctype="multipart/form-data">
            @csrf
            <br />
            {{method_field('POST')}}
            <br />
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <div class='row'>
                        <label for="card_id"><b>Card ID<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{$result[0]->card_id}}" required disabled>
                            <input type="hidden" id="card_id" name="card_id" class="form-control" value="{{$result[0]->card_id}}" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <br />
                <div class="table-wrapper-scroll-y my-custom-scrollbar col-md-10">
                    <table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered" id="attendance-table">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" class="check-all" id="select_all" onclick="selectAll()">
                                </th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($menu_list) && $menu_list->count())
                                @php $i=1;@endphp
                                @foreach($menu_list as $res)
                                <tr>
                                    @if (array_key_exists($res->id,$order_item))
                                        <td><input type="checkbox" class="menu_check" name="checkMenu[]"  value="{{$res->id}}" onclick="unselectAll()" checked></td>
                                        <td><img src="{{asset('assets/menu/'.$res->menu_image)}}" alt="" height=50 width=50></img></td>
                                        <td>{{$res->name}}</td>
                                        <td>{{$res->price}}</td>
                                        <td>
                                            <select class="form-select" id="{{$res->id.'-qty'}}" name="{{$res->id.'-qty'}}" onchange="changeQtyAmount('{{$res->id}}')">
                                                @php for($i=0;$i<10;$i++) { @endphp
                                                    @if ($i==$order_item[$res->id]['quantity'])
                                                        <option value="{{$i.'-'.$res->id.'-'.$res->price}}" selected="selected">{{$i}}</option>
                                                    @else 
                                                        <option value="{{$i.'-'.$res->id.'-'.$res->price}}">{{$i}}</option>
                                                    @endif
                                                @php } @endphp
                                            </select>
                                        </td>
                                        <td><span id="{{$res->id.'-qtytotal'}}">{{$order_item[$res->id]['qty_total']}}</span></td>
                                        <td>
                                            <input type="text" class="form-control" name="{{$res->id.'-remark'}}">
                                        </td>
                                    @else
                                        <td><input type="checkbox" class="menu_check" name="checkMenu[]"  value="{{$res->id}}" onclick="unselectAll()"></td>
                                        <td><img src="{{asset('assets/menu/'.$res->menu_image)}}" alt="" height=50 width=50></img></td>
                                        <td>{{$res->name}}</td>
                                        <td>{{$res->price}}</td>
                                        <td>
                                            <select class="form-select" id="{{$res->id.'-qty'}}" name="{{$res->id.'-qty'}}" onchange="changeQtyAmount('{{$res->id}}')">
                                                @php for($i=0;$i<10;$i++) { @endphp
                                                <option value="{{$i.'-'.$res->id.'-'.$res->price}}">{{$i}}</option>
                                                @php } @endphp
                                            </select>
                                        </td>
                                        <td><span id="{{$res->id.'-qtytotal'}}"></span></td>
                                        <td>
                                            <input type="text" class="form-control" name="{{$res->id.'-remark'}}">
                                        </td>
                                    @endif
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="col-md-1"></div>
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
        </form>
        <br />
    </div>
</section>


@endsection