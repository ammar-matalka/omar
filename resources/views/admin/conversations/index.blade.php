@extends('layouts.admin')

@section('title', 'Conversations')
@section('page-title', 'Customer Conversations')

@section('breadcrumb')
<div class="breadcrumb-item">
    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">Dashboard</a>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-right"></i>
    Conversations
</div>
@endsection

@section('content')
<div style="padding: var(--space-xl);">
    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-2xl);">
        <div>
            <h1 style="font-size: 2rem; font-weight: 700; color: var(--admin-secondary-900); margin-bottom: var(--space-sm);">
                <i class="fas fa-comments" style="color: var(--admin-primary-500); margin-right: var(--space-sm);"></i>
                Customer Conversations
            </h1>
            <p style="color: var(--admin-secondary-600); font-size: 0.875rem;">
                Manage customer inquiries and support conversations
            </p>
        </div>
        
        <div style="display: flex; gap: var(--space-md);">
            @if($conversations->where('is_read_by_admin', false)->count() > 0)
            <form action="{{ route('admin.conversations.mark-all-read') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check-double"></i>
                    Mark All as Read ({{ $conversations->where('is_read_by_admin', false)->count() }})
                </button>
            </form>
            @endif
            
            <button class="btn btn-secondary" onclick="window.location.reload()">
                <i class="fas fa-sync-alt"></i>
                Refresh
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: var(--space-lg); margin-bottom: var(--space-2xl);">
        <div class="card">
            <div class="card-body" style="text-align: center; padding: var(--space-xl);">
                <div style="font-size: 2.5rem; color: var(--admin-primary-500); margin-bottom: var(--space-md);">
                    <i class="fas fa-comments"></i>
                </div>
                <div style="font-size: 2rem; font-weight: 700; color: var(--admin-secondary-900); margin-bottom: var(--space-sm);">
                    {{ $conversations->count() }}
                </div>
                <div style="color: var(--admin-secondary-600); font-size: 0.875rem; font-weight: 500;">
                    Total Conversations
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body" style="text-align: center; padding: var(--space-xl);">
                <div style="font-size: 2.5rem; color: var(--error-500); margin-bottom: var(--space-md);">
                    <i class="fas fa-envelope"></i>
                </div>
                <div style="font-size: 2rem; font-weight: 700; color: var(--admin-secondary-900); margin-bottom: var(--space-sm);">
                    {{ $conversations->where('is_read_by_admin', false)->count() }}
                </div>
                <div style="color: var(--admin-secondary-600); font-size: 0.875rem; font-weight: 500;">
                    Unread Messages
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body" style="text-align: center; padding: var(--space-xl);">
                <div style="font-size: 2.5rem; color: var(--success-500); margin-bottom: var(--space-md);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div style="font-size: 2rem; font-weight: 700; color: var(--admin-secondary-900); margin-bottom: var(--space-sm);">
                    {{ $conversations->where('is_read_by_admin', true)->count() }}
                </div>
                <div style="color: var(--admin-secondary-600); font-size: 0.875rem; font-weight: 500;">
                    Resolved
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body" style="text-align: center; padding: var(--space-xl);">
                <div style="font-size: 2.5rem; color: var(--info-500); margin-bottom: var(--space-md);">
                    <i class="fas fa-users"></i>
                </div>
                <div style="font-size: 2rem; font-weight: 700; color: var(--admin-secondary-900); margin-bottom: var(--space-sm);">
                    {{ $conversations->unique('user_id')->count() }}
                </div>
                <div style="color: var(--admin-secondary-600); font-size: 0.875rem; font-weight: 500;">
                    Active Users
                </div>
            </div>
        </div>
    </div>

    <!-- Conversations List -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list"></i>
                All Conversations
            </h3>
        </div>
        
        <div class="card-body" style="padding: 0;">
            @if($conversations->count() > 0)
                @foreach($conversations as $conversation)
                <div style="border-bottom: 1px solid var(--admin-secondary-200); padding: var(--space-lg); {{ !$conversation->is_read_by_admin ? 'background: rgba(59, 130, 246, 0.02); border-left: 4px solid var(--admin-primary-500);' : '' }}">
                    <div style="display: flex; justify-content: space-between; align-items: start; gap: var(--space-lg);">
                        <!-- Conversation Info -->
                        <div style="flex: 1;">
                            <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-sm);">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 1rem;">
                                    {{ strtoupper(substr($conversation->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h4 style="font-size: 1.125rem; font-weight: 600; color: var(--admin-secondary-900); margin-bottom: var(--space-xs);">
                                        {{ $conversation->title }}
                                        @if(!$conversation->is_read_by_admin)
                                            <span style="background: var(--error-500); color: white; padding: 0.25rem 0.5rem; border-radius: var(--radius-sm); font-size: 0.625rem; font-weight: 700; margin-left: var(--space-sm);">NEW</span>
                                        @endif
                                    </h4>
                                    <div style="display: flex; align-items: center; gap: var(--space-lg); color: var(--admin-secondary-600); font-size: 0.875rem;">
                                        <span>
                                            <i class="fas fa-user" style="margin-right: var(--space-xs);"></i>
                                            {{ $conversation->user->name }}
                                        </span>
                                        <span>
                                            <i class="fas fa-envelope" style="margin-right: var(--space-xs);"></i>
                                            {{ $conversation->user->email }}
                                        </span>
                                        <span>
                                            <i class="fas fa-clock" style="margin-right: var(--space-xs);"></i>
                                            {{ $conversation->updated_at->format('M d, Y H:i') }}
                                        </span>
                                        <span>
                                            <i class="fas fa-comment" style="margin-right: var(--space-xs);"></i>
                                            {{ $conversation->messages->count() ?? 0 }} messages
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            @if($conversation->lastMessage ?? false)
                            <div style="background: var(--admin-secondary-50); padding: var(--space-md); border-radius: var(--radius-md); margin-top: var(--space-md);">
                                <p style="color: var(--admin-secondary-700); font-size: 0.875rem; line-height: 1.6; margin: 0;">
                                    <strong>{{ $conversation->lastMessage->is_from_admin ? 'Admin' : $conversation->user->name }}:</strong>
                                    "{{ Str::limit($conversation->lastMessage->message, 120) }}"
                                </p>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Status & Actions -->
                        <div style="display: flex; flex-direction: column; align-items: end; gap: var(--space-md);">
                            <div style="display: flex; align-items: center; gap: var(--space-sm);">
                                @if(!$conversation->is_read_by_admin)
                                    <span class="badge badge-warning">New Message</span>
                                @else
                                    <span class="badge badge-success">Read</span>
                                @endif
                            </div>
                            
                            <div style="display: flex; gap: var(--space-sm);">
                                <a href="{{ route('admin.conversations.show', $conversation) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i>
                                    View
                                </a>
                                @if(!$conversation->is_read_by_admin)
                                <form action="{{ route('admin.conversations.mark-read', $conversation) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-check"></i>
                                        Mark Read
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                
                <!-- Pagination -->
                <div style="padding: var(--space-lg); display: flex; justify-content: center;">
                    {{ $conversations->links() }}
                </div>
            @else
                <div style="text-align: center; padding: var(--space-3xl);">
                    <div style="font-size: 4rem; color: var(--admin-secondary-400); margin-bottom: var(--space-lg);">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--admin-secondary-700); margin-bottom: var(--space-md);">
                        No Conversations Yet
                    </h3>
                    <p style="color: var(--admin-secondary-500);">
                        Customer conversations will appear here when they start contacting support.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Enhanced hover effects */
.card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.btn:hover {
    transform: translateY(-1px);
}

/* Conversation row hover */
div[style*="border-bottom"]:hover {
    background: var(--admin-secondary-50) !important;
}

/* Animation for fade-in */
.card {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    div[style*="display: flex"][style*="justify-content: space-between"] {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: var(--space-lg) !important;
    }
    
    div[style*="grid-template-columns"] {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) !important;
    }
    
    div[style*="display: flex"][style*="align-items: start"] {
        flex-direction: column !important;
        align-items: stretch !important;
    }
}
</style>

<script>
// Auto-refresh every 30 seconds for new messages
setInterval(function() {
    // You can implement AJAX to check for new messages
    console.log('Checking for new conversations...');
}, 30000);

// Add smooth animations
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
});
</script>
@endsection