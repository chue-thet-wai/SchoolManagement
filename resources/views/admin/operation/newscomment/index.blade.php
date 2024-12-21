@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
    <h1>News Comment</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Operation</li>
            <li class="breadcrumb-item active">News / Comment</li>
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
                <h5><b>News Data</b></h5>
                <table class="table table-bordered">
                    <tr>
                        <td><b>Image</b></td>
                        <td><img src="{{asset('assets/news_images/'.$news_data['image'])}}" alt="" height=50 width=50></img></td>
                    </tr>
                    <tr>
                        <td><b>Title</b></td>
                        <td>{{$news_data['title']}}</td>
                    </tr>
                    <tr>
                        <td><b>Description</b></td>
                        <td>{{$news_data['description']}}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{url('admin/news/list')}}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>
        <br />
        <div class="row mt-2">
            <div class="col-md-1"></div>
            <div class="col-md-10 content-title">
                <h5><b>Comment List</b></h5>
            </div>
        </div>
        <div class="row m-2">
            <div class="col-md-1"></div>
            <form class="col-md-10" method="POST" action="{{ url('admin/news/comment/store') }}" enctype="multipart/form-data">
                @csrf
                <br />
                <input type="hidden" name="news_id" value="{{$news_data['id']}}" />
                <div class="row">
                    <div class="form-group col-md-10">
                        <label for="comment"><b>Comment</b></label>
                        <div class="col-sm-12">
                            <input type="text" id="comment" name="comment" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group col-md-2">
                        <div class="d-grid mt-4">
                            <input type="submit" value="Add" class="btn btn-primary">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="row g-4 m-2" style="display: flex;overflow-x: auto;">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <table cellpadding="0" cellspacing="0" class="datatable table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Comment</th>
                            <th>Created By</th>
                            <th>Created Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($list_result) && $list_result->count())
                            @foreach($list_result as $res)
                            <tr>
                                <td>{{$res->comment }}</td>
                                <td>@if ($res->comment_by_parent != null) {{"Parent"}} @else {{"School"}} @endif</td>
                                <td>{{date('Y-m-d H:i:s',strtotime($res->created_at))}}</td>
                                <td class="center">
                                    <form method="post" action="{{ url('admin/special_request_comment/delete/'.$res->id) }}" style="display: inline;">
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
        </div>
        <div class="d-flex">
            {!! $list_result->links() !!}
        </div>
    </div>
</section>


@endsection

