@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Exam Terms Detail</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Exam</li>
			<li class="breadcrumb-item active">Exam Terms / Detail</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    <div class="card-body">        
        <br />
        <div class="row g-4">
            <div class="col-md-10 content-title">
                <div class="exam-term-data m-2">
                    <h5><b>Exam Term Data</b></h5>
                    <table class="table table-bordered">
                        <tr>
                            <td><b>Name</b></td>
                            <td>{{$exam_term_data['name']}}</td>
                        </tr>
                        <tr>
                            <td><b>Grade</b></td>
                            <td>{{$exam_term_data['grade']}}</td>
                        </tr>
                        <tr>
                            <td><b>Academic Year</b></td>
                            <td>{{$exam_term_data['academic_year']}}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{ url('admin/exam_terms/list') }}" id="form-header-btn"><i
                        class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>
        <br />
        <div class="row g-4">
            <div class="col-md-10 content-title">
                <h5><b>Exam Term Detail List</b></h5>
            </div>
            <div class="col-md-2">
                @php $examTermsId = $exam_term_data['id']; @endphp
                <a class="btn btn-sm btn-primary" href="{{url('admin/exam_terms_detail/create/'.$examTermsId)}}" id="form-header-btn"><span class="bi bi-plus"></span> Create</a>
            </div>
        </div>
        <br />
        <div class="row g-4 m-2" style="display: flex;overflow-x: auto;">
            <table cellpadding="0" cellspacing="0" class="datatable table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Image</th>
                        <th>Subject</th>
                        <th>Exam Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($list_result) && $list_result->count())
                        @php $i=1;@endphp
                        @foreach($list_result as $res)
                        <tr>
                            <td>@php echo $i;$i++; @endphp</td>
                            <td><img src="{{asset('assets/subject_images/'.$res->subject_image)}}" alt="" height=50 width=50></img></td>
                            <td>{{$subject_list[$res->subject_id] }}</td>
                            <td>{{date('Y-m-d',strtotime($res->exam_date))}}</td>
                            <td class="center">
                                <a href="{{ url('admin/exam_terms_detail/edit/'.$res->id) }}">
                                    <button type="submit" value="edit" class="btn m-1 p-0 border-0">
                                        <span id="boot-icon" class="bi bi-pencil-square" style="font-size: 20px; color:rgb(58 69 207);"></span>
                                    </button>                            
                                </a>
                                <form method="post" action="{{ url('admin/exam_terms_detail/delete/'.$res->id) }}" style="display: inline;">
                                    @csrf
                                    {{ method_field('DELETE') }}
                                    <button type="submit" value="delete" class="btn m-1 p-0 border-0">
                                        <span id="boot-icon" class="bi bi-trash" style="font-size: 20px; color: rgb(165, 42, 42);"></span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    @else
                    <tr>
                        <td colspan="8">There are no data.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="d-flex">
            {!! $list_result->links() !!}
        </div>
    </div>
</section>


@endsection

