@extends('layouts.dashboard')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
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
                    $("#card_amount").html(data.card_amount);
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
            <div class="col-md-9 content-title">
                <h4><b>Create Food Order</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{url('admin/food_order/list')}}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>

        <br />
        <form method="POST" action="{{url('admin/food_order/save')}}" enctype="multipart/form-data">
            @csrf
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <div class='row'>
                        <label for="card_id"><b>Card ID<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="text" id="card_id" name="card_id" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" name="cardid_search" id="cardid_search" class="btn btn-sm btn-primary mt-1" onclick="getStudentData()">Search</button>
                        </div>
                    </div>
                    <div class="row">
                        <span name="student_data_msg" id="student_data_msg"></span>
                    </div>
                </div>
            </div>
            <br />
            <!--start student info data-->
            <div class="row g-4">
                <div class="col-md-1"></div>
                <br />
                <fieldset id='student_info_block' class="form-group col-md-8 border m-2 p-3" style='display:none;'>
                    <legend for="" class="float-none w-auto" style='font-size:1.2em;'><b>  Student Info  </b></legend>
                
                    <div class="form-group col-md-10">
                        <div class="row col-md-10">
                            <div class='col-sm-4'>
                                <label for=""><b>Student ID </b></label>
                            </div>
                            <div class='col-sm-1'>
                                :
                            </div>
                            <div class='col-sm-7'>
                                <span id='student_id'></span>
                            </div>
                        </div>
                        <div class="row col-md-10">
                            <div class='col-sm-4'>
                                <label for=""><b>Student Name </b></label>
                            </div>
                            <div class='col-sm-1'>
                                :
                            </div>
                            <div class='col-sm-7'>
                                <span id='student_name'></span>
                            </div>
                        </div>
                        <div class="row col-md-10">
                            <div class='col-sm-4'>
                                <label for=""><b> Card Amount </b></label>
                            </div>
                            <div class='col-sm-1'>
                                :
                            </div>
                            <div class='col-sm-7'>
                                <span id='card_amount'></span>
                            </div>
                        </div>                        
                    </div>                    
                </fieldset>
                <div class="col-md-1"></div>
            </div>
            <!--end student info data-->
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
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="col-md-1"></div>
            </div>
            <div class='row g-4'>
                <div class="col-md-10"></div>
                <div class="form-group col-md-2">
					<div class="d-grid mt-4">
						<input type="submit" value="Save" class="btn btn-primary">
					</div>
				</div>
            </div>
        </form>
    </div>
</section>


@endsection