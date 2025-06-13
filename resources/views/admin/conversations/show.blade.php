@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Conversation with {{ $conversation->user->name }}</h1>
        <a href="{{ route('admin.conversations.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Conversations
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Conversation Details</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Subject:</strong>
                        <p>{{ $conversation->title }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Customer:</strong>
                        <p>{{ $conversation->user->name }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong>
                        <p>{{ $conversation->user->email }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Started:</strong>
                        <p>{{ $conversation->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <strong>Last Updated:</strong>
                        <p>{{ $conversation->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Messages</h6>
                </div>
                <div class="card-body">
                    <div class="chat-container" style="max-height: 400px; overflow-y: auto;">
                        @foreach($messages as $message)
                            <div class="chat-message mb-3 {{ $message->is_from_admin ? 'admin-message' : 'user-message' }}">
                                <div class="chat-message-content {{ $message->is_from_admin ? 'bg-primary text-white' : 'bg-light' }} p-3 rounded">
                                    <p class="mb-1">{{ $message->message }}</p>
                                    <small class="{{ $message->is_from_admin ? 'text-white-50' : 'text-muted' }}">
                                        {{ $message->is_from_admin ? 'Admin' : $conversation->user->name }} - {{ $message->created_at->format('M d, Y h:i A') }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Reply</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.conversations.reply', $conversation) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <textarea class="form-control @error('message') is-invalid @enderror" name="message" rows="3" placeholder="Type your reply here..." required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane fa-sm"></i> Send Reply
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .chat-message {
        display: flex;
        margin-bottom: 15px;
    }
    
    .user-message {
        justify-content: flex-start;
    }
    
    .admin-message {
        justify-content: flex-end;
    }
    
    .chat-message-content {
        max-width: 80%;
        padding: 10px 15px;
        border-radius: 15px;
    }
    
    .user-message .chat-message-content {
        border-bottom-left-radius: 5px;
    }
    
    .admin-message .chat-message-content {
        border-bottom-right-radius: 5px;
    }
</style>
@endsection