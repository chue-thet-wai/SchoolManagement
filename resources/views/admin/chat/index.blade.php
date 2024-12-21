@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
    <h1>Chat</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Chat</li>
        </ol>
    </nav>
    @include('layouts.error')
</div><!-- End Page Title -->
<section class="card">
    <div class="card-header">
        <h5><b>Chat List</b></h5>
    </div>
    <div class="card-body">
        <div class="chat-list">
            @foreach($guardian_list as $user)
            <div class="chat-user">
                <a href="{{ route('chat.show', $user['id']) }}" class="chat-link">
                    <div class="chat-user-avatar">
                        @if ($user['photo']=='')
                            <img src="{{ asset('profile.png') }}" alt="{{ $user['name'] }}">
                        @else
                            <img src="{{ asset('assets/guardian_images/'. $user['photo']) }}" alt="{{ $user['name'] }}">
                        @endif
                    </div>
                    <div class="chat-user-details">
                        <h5 class="chat-user-name">{{ $user['name'] }}</h5>
                        @if ($user['has_unread'])
                            <p class="chat-user-last-message">New Message</p>
                        @endif
                    </div>
                    <!--<div class="chat-user-status">
                        <span class="online"></span>
                    </div>-->
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<style>
    .chat-list {
        list-style-type: none;
        margin: 0;
        padding: 0;
    }

    .chat-user {
        display: flex;
        align-items: center;
        padding: 10px;
        border-bottom: 1px solid #eee;
    }

    .chat-link {
        display: flex;
        width: 100%;
        text-decoration: none;
        color: inherit;
    }

    .chat-user-avatar {
        flex: 0 0 50px;
        margin-right: 10px;
    }

    .chat-user-avatar img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }

    .chat-user-details {
        flex: 1;
    }

    .chat-user-name {
        margin: 0;
        font-weight: bold;
    }

    .chat-user-last-message {
        margin: 0;
        color: #777;
    }

    .chat-user-status {
        flex: 0 0 10px;
        height: 10px;
        width: 10px;
        border-radius: 50%;
        background-color: #4CAF50; /* Green */
    }

    .online {
        background-color: #4CAF50; /* Green */
    }
</style>

@endsection
