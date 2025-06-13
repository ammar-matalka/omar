@extends('layouts.app')

@section('content')
<div class="conversations-container py-5">
    <div class="container">
        <!-- Header Section with Statistics -->
        <div class="header-section mb-4">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-6 fw-bold mb-0" style="color:rgb(0, 0, 0);">
                        <i class="fas fa-comments me-2"></i>My Conversations
                    </h1>
                    <p class="text-muted mt-2">Stay connected with your ongoing discussions</p>
                </div>
                <div class="col-lg-6">
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <div class="stat-card bg-primary bg-opacity-10 rounded-4 p-3 h-100">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon bg-primary rounded-circle p-2 me-3">
                                        <i class="fas fa-comment-dots text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="fw-bold mb-0">{{ $conversations->total() }}</h3>
                                        <p class="text-muted mb-0 small">Total</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="stat-card bg-success bg-opacity-10 rounded-4 p-3 h-100">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon bg-success rounded-circle p-2 me-3">
                                        <i class="fas fa-check text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="fw-bold mb-0">{{ $conversations->where('is_read_by_user', true)->count() }}</h3>
                                        <p class="text-muted mb-0 small">Read</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="stat-card bg-danger bg-opacity-10 rounded-4 p-3 h-100">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon bg-danger rounded-circle p-2 me-3">
                                        <i class="fas fa-bell text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="fw-bold mb-0">{{ $conversations->where('is_read_by_user', false)->count() }}</h3>
                                        <p class="text-muted mb-0 small">Unread</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="action-bar mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <a href="{{ route('user.conversations.create') }}" class="btn btn-primary rounded-pill w-100 d-flex align-items-center justify-content-center">
                        <i class="fas fa-plus-circle me-2"></i> Start New Conversation
                    </a>
                </div>
                <div class="col-md-8">
                    <form action="{{ route('user.conversations.index') }}" method="GET" class="search-form">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control border-end-0 rounded-pill-start" 
                                   placeholder="Search conversations..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-outline-secondary border-start-0 rounded-pill-end">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Conversation Cards -->
        @if($conversations->isEmpty())
            <div class="empty-state text-center py-5">
                <div class="empty-icon mb-4">
                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                        <i class="fas fa-comments text-primary" style="font-size: 3rem;"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-3">No Conversations Yet</h3>
                <p class="text-muted mb-4 col-md-6 mx-auto">Start your first conversation to connect with our team. We're here to help with any questions or concerns you might have.</p>
                <a href="{{ route('user.conversations.create') }}" class="btn btn-primary btn-lg rounded-pill px-5">
                    <i class="fas fa-plus-circle me-2"></i> Start Your First Conversation
                </a>
            </div>
        @else
            <div class="conversation-filters mb-3">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link {{ !request('filter') ? 'active' : '' }}" href="{{ route('user.conversations.index') }}">All</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('filter') == 'unread' ? 'active' : '' }}" href="{{ route('user.conversations.index', ['filter' => 'unread']) }}">Unread</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('filter') == 'recent' ? 'active' : '' }}" href="{{ route('user.conversations.index', ['filter' => 'recent']) }}">Recent</a>
                    </li>
                </ul>
            </div>

            <div class="conversation-cards">
                <div class="row g-4">
                    @foreach($conversations as $conversation)
                        <div class="col-md-6 col-xl-4">
                            <div class="conversation-card card h-100 border-0 rounded-4 shadow-sm hover-shadow position-relative overflow-hidden">
                                @if(!$conversation->is_read_by_user)
                                    <div class="unread-indicator"></div>
                                @endif
                                <div class="card-body p-4">
                                    <div class="d-flex mb-3">
                                        <div class="conversation-avatar me-3">
                                            <div class="avatar-wrapper rounded-circle bg-{{ ['primary', 'success', 'danger', 'warning', 'info'][array_rand(['primary', 'success', 'danger', 'warning', 'info'])] }} d-flex align-items-center justify-content-center text-white" style="width: 50px; height: 50px;">
                                                {{ strtoupper(substr($conversation->title, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div>
                                            <h5 class="card-title fw-bold mb-1">{{ Str::limit($conversation->title, 28) }}</h5>
                                            <p class="card-subtitle text-muted small mb-0">
                                                <i class="far fa-clock me-1"></i>{{ $conversation->updated_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="message-preview mb-3">
                                        <p class="text-muted mb-0">
                                            @if($conversation->lastMessage)
                                                {{ Str::limit($conversation->lastMessage->content, 100) }}
                                            @else
                                                No messages yet
                                            @endif
                                        </p>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="conversation-meta small">
                                            <span class="badge {{ $conversation->is_read_by_user ? 'bg-light text-dark' : 'bg-primary' }} rounded-pill">
                                                {{ $conversation->is_read_by_user ? 'Read' : 'New' }}
                                            </span>
                                            @if($conversation->messages_count > 0)
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill ms-1">
                                                    <i class="far fa-comment-dots me-1"></i>{{ $conversation->messages_count }}
                                                </span>
                                            @endif
                                        </div>
                                        <a href="{{ route('user.conversations.show', $conversation) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                            View <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="pagination-wrapper mt-5">
                    <div class="d-flex justify-content-center">
                        {{ $conversations->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Enhanced Conversation Styles */
    .header-section {
        padding-bottom: 1.5rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .stat-icon {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .search-form .form-control:focus {
        box-shadow: none;
        border-color: #ced4da;
    }
    
    .rounded-pill-start {
        border-top-left-radius: 50rem !important;
        border-bottom-left-radius: 50rem !important;
    }
    
    .rounded-pill-end {
        border-top-right-radius: 50rem !important;
        border-bottom-right-radius: 50rem !important;
    }
    
    .conversation-card {
        transition: all 0.3s ease;
    }
    
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }
    
    .unread-indicator {
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background-color: #0d6efd;
    }
    
    .conversation-filters .nav-pills .nav-link {
        padding: 0.4rem 1.2rem;
        border-radius: 50rem;
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .conversation-filters .nav-pills .nav-link:not(.active) {
        color: #495057;
        background-color: rgba(0, 0, 0, 0.04);
    }
    
    .empty-state {
        margin: 3rem 0;
    }
    
    .avatar-wrapper {
        font-weight: bold;
        font-size: 1.2rem;
    }
    
    /* Custom Pagination Styling */
    .pagination {
        --bs-pagination-color: #0d6efd;
        --bs-pagination-hover-color: #0a58ca;
        --bs-pagination-focus-color: #0a58ca;
        --bs-pagination-active-bg: #0d6efd;
        --bs-pagination-active-border-color: #0d6efd;
        --bs-pagination-border-radius: 50rem;
        --bs-pagination-padding-x: 1rem;
        --bs-pagination-padding-y: 0.5rem;
        --bs-pagination-margin-start: 0.25rem;
        --bs-pagination-margin-end: 0.25rem;
    }
</style>
@endpush