@extends('layouts.dashboard')

@section('content')
<link href="{{ asset('css/chat.css') }}" rel="stylesheet">
<div class="pagetitle">
	<h1>Chat</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Chat</li>
			<li class="breadcrumb-item active">Chat</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<div class="card" id="chat">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><b>{{ $guardian_data->name }}</b></h5>
        <a class="btn btn-sm btn-primary" href="{{ url('admin/chat/list') }}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
    </div>
    <div class="card-body p-5">
    
        <ul id="chat-messages">
            @if ($messages)
                @foreach($messages as $message)
                    @if ($message['sender_id'] != $message['guardian_id'])
                        <li class="sent">
                            <!--<span class="meta">{{ $message['sender_id'] }}</span>-->
                            <div class="message">{{ $message['message'] }}</div>
                            <span class="date">{{ $message['created_at'] }}</span>
                        </li>
                    @else
                        <li class="received">
                            <!--<span class="meta">{{ $message['sender_id'] }}</span>-->
                            <div class="message">{{ $message['message'] }}</div>
                            <span class="date">{{ $message['created_at'] }}</span>
                        </li>
                    @endif
                @endforeach
            @endif
        </ul>
        <div id="chat-input" class="input-group mt-4">
            <input type="text" id="message-input" class="form-control" placeholder="Type your message here...">
            <div class="input-group-append">
                <button id="send-button" class="btn btn-primary"><i class="bi bi-send-fill" style="color:#ffffff;"></i></button>
            </div>
        </div>
    </div>
</div>

<script src="https://js.pusher.com/7.0/pusher.min.js"></script>

<script>
    var pusher = new Pusher('{{ config("broadcasting.connections.pusher.key") }}', {
        cluster: '{{ config("broadcasting.connections.pusher.options.cluster") }}',
        encrypted: true
    });

    var channel = pusher.subscribe('client-chat-room-{{ $guardian_data->id }}');

    channel.bind('chat-message-sent', function(data) {
        try {
            console.log(data);
            var chatMessages = document.getElementById('chat-messages');
            var newMessage = document.createElement('li');
            newMessage.classList.add(data.message.sender_id != data.message.guardian_id ? 'sent' : 'received');

            // Convert the date and time string to a Date object
            var dateTime = new Date(data.message.created_at);

            /*newMessage.innerHTML = `
                <span class="meta">${data.message.sender_id}</span>
                <div class="message">${data.message.message}</div>
                <span class="date">${dateTime.getFullYear()}-${(dateTime.getMonth() + 1).toString().padStart(2, '0')}-${dateTime.getDate().toString().padStart(2, '0')} ${dateTime.getHours().toString().padStart(2, '0')}:${dateTime.getMinutes().toString().padStart(2, '0')}:${dateTime.getSeconds().toString().padStart(2, '0')}</span>
            `;*/
            newMessage.innerHTML = `
                <div class="message">${data.message.message}</div>
                <span class="date">${dateTime.getFullYear()}-${(dateTime.getMonth() + 1).toString().padStart(2, '0')}-${dateTime.getDate().toString().padStart(2, '0')} ${dateTime.getHours().toString().padStart(2, '0')}:${dateTime.getMinutes().toString().padStart(2, '0')}:${dateTime.getSeconds().toString().padStart(2, '0')}</span>
            `;
            chatMessages.appendChild(newMessage);
        } catch (e) {
            console.error('Error in channel.bind:', e);
        }
    });

    document.getElementById('send-button').addEventListener('click', function() {
        var messageInput = document.getElementById('message-input');
        var message = messageInput.value.trim();
        if (message !== '') {
            axios.post('{{ route("send.message") }}', {
                guardian_id: {{ $guardian_data->id }},
                message: message
            })
            .then(response => {
                console.log(response.data.status);
                // Trigger the Pusher event
                channel.trigger('client-chat-room-{{ $guardian_data->id }}', {
                    message: message
                });
                messageInput.value = '';
            })
            .catch(error => {
                console.error(error);
            });
        }
    });

    function saveLastReadAt() {
        axios.post('{{ route("save.last_read_at") }}')
        .then(response => {
            console.log('Last read at time saved:', response.data.status);
        })
        .catch(error => {
            console.error('Error saving last read at time:', error);
        });
    }

    // Listen for the beforeunload event to save the last read at time before leaving the page
    window.addEventListener('beforeunload', function(event) {
        saveLastReadAt();
    });
</script>

@endsection

