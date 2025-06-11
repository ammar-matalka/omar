@extends('layouts.app')

@section('title', __('My Messages') . ' - ' . config('app.name'))

@push('styles')
<style>
    .conversations-hero {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        padding: var(--space-2xl) 0;
        position: relative;
        overflow: hidden;
    }
    
    .conversations-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="white" opacity="0.1"><polygon points="0,0 1000,0 1000,80 0,100"/></svg>');
        background-size: cover;
        background-position: bottom;
    }
    
    .hero-content {
        position: relative;
        z-index: 1;
        text-align: center;
    }
    
    .hero-title {
        font-size: 2.5rem;
        font-weight: 900;
        margin-bottom: var(--space-sm);
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    
    .hero-subtitle {
        font-size: 1.125rem;
        opacity: 0.9;
        margin-bottom: var(--space-lg);
    }
    
    .breadcrumb {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
        font-size: 0.875rem;
        opacity: 0.9;
        flex-wrap: wrap;
    }
    
    .breadcrumb-link {
        color: white;
        text-decoration: none;
        transition: opacity var(--transition-fast);
    }
    
    .breadcrumb-link:hover {
        opacity: 0.8;
        text-decoration: underline;
    }
    
    .breadcrumb-separator {
        opacity: 0.6;
    }
    
    .conversations-container {
        padding: var(--space-3xl) 0;
        background: var(--background);
        min-height: 60vh;
    }
    
    .conversations-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-2xl);
        flex-wrap: wrap;
        gap: var(--space-lg);
    }
    
    .conversations-count {
        font-size: 1.125rem;
        color: var(--on-surface-variant);
        font-weight: 500;
    }
    
    .new-conversation-btn {
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-md) var(--space-xl);
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: white;
        border-radius: var(--radius-lg);
        text-decoration: none;
        font-weight: 600;
        transition: all var(--transition-fast);
        box-shadow: var(--shadow-md);
    }
    
    .new-conversation-btn:hover {
        background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    .conversations-grid {
        display: flex;
        flex-direction: column;
        gap: var(--space-lg);
    }
    
    .conversation-card {
        background: var(--surface);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        box-shadow: var(--shadow-sm);
        transition: all var(--transition-normal);
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }
    
    .conversation-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-500), var(--secondary-500));
        transform: scaleX(0);
        transition: transform var(--transition-normal);
    }
    
    .conversation-card:hover {
        border-color: var(--primary-200);
        box-shadow: var(--shadow-lg);
        transform: translateY(-4px);
    }
    
    .conversation-card:hover::before {
        transform: scaleX(1);
    }
    
    .conversation-card.unread {
        border-color: var(--primary-300);
        background: linear-gradient(135deg, var(--primary-50), var(--surface));
    }
    
    .conversation-card.unread::before {
        transform: scaleX(1);
    }
    
    .conversation-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: var(--space-md);
        flex-wrap: wrap;
        gap: var(--space-md);
    }
    
    .conversation-info {
        flex: 1;
    }
    
    .conversation-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--on-surface);
        margin-bottom: var(--space-xs);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        line-height: 1.3;
    }
    
    .unread-indicator {
        width: 12px;
        height: 12px;
        background: var(--primary-500);
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    
    .conversation-meta {
        display: flex;
        align-items: center;
        gap: var(--space-lg);
        color: var(--on-surface-variant);
        font-size: 0.875rem;
        flex-wrap: wrap;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
    }
    
    .conversation-status {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: var(--space-sm);
    }
    
    .status-badge {
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-md);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-open {
        background: var(--success-100);
        color: var(--success-700);
    }
    
    .status-closed {
        background: var(--error-100);
        color: var(--error-700);
    }
    
    .conversation-time {
        font-size: 0.75rem;
        color: var(--on-surface-variant);
        font-weight: 500;
    }
    
    .conversation-preview {
        color: var(--on-surface-variant);
        font-size: 0.875rem;
        line-height: 1.5;
        margin-bottom: var(--space-md);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .conversation-actions {
        display: flex;
        gap: var(--space-md);
        justify-content: flex-end;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .message-count {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
        color: var(--on-surface-variant);
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-sm) var(--space-md);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        background: var(--surface);
        color: var(--on-surface-variant);
        text-decoration: none;
        font-size: 0.75rem;
        font-weight: 500;
        transition: all var(--transition-fast);
    }
    
    .action-btn:hover {
        background: var(--primary-50);
        border-color: var(--primary-200);
        color: var(--primary-600);
        transform: translateY(-1px);
    }
    
    .action-btn.primary {
        background: var(--primary-500);
        color: white;
        border-color: var(--primary-500);
    }
    
    .action-btn.primary:hover {
        background: var(--primary-600);
        border-color: var(--primary-600);
    }
    
    .empty-state {
        text-align: center;
        padding: var(--space-3xl) var(--space-xl);
        color: var(--on-surface-variant);
    }
    
    .empty-icon {
        font-size: 4rem;
        margin-bottom: var(--space-lg);
        opacity: 0.5;
        color: var(--primary-300);
    }
    
    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: var(--space-md);
        color: var(--on-surface);
    }
    
    .empty-text {
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: var(--space-xl);
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .empty-cta {
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-md) var(--space-xl);
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: white;
        border-radius: var(--radius-lg);
        text-decoration: none;
        font-weight: 600;
        transition: all var(--transition-fast);
        box-shadow: var(--shadow-md);
    }
    
    .empty-cta:hover {
        background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    .pagination-wrapper {
        margin-top: var(--space-2xl);
        display: flex;
        justify-content: center;
    }
    
    .search-box {
        position: relative;
        margin-bottom: var(--space-xl);
    }
    
    .search-input {
        width: 100%;
        padding: var(--space-md) var(--space-md) var(--space-md) var(--space-3xl);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-lg);
        background: var(--surface);
        color: var(--on-surface);
        font-size: 1rem;
        transition: all var(--transition-fast);
    }
    
    .search-input:focus {
        outline: none;
        border-color: var(--primary-500);
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    }
    
    .search-icon {
        position: absolute;
        left: var(--space-md);
        top: 50%;
        transform: translateY(-50%);
        color: var(--on-surface-variant);
        font-size: 1rem;
    }
    
    .filter-tabs {
        display: flex;
        gap: var(--space-md);
        margin-bottom: var(--space-xl);
        border-bottom: 2px solid var(--border-color);
        overflow-x: auto;
        padding-bottom: var(--space-md);
    }
    
    .filter-tab {
        padding: var(--space-sm) var(--space-lg);
        border: none;
        background: none;
        color: var(--on-surface-variant);
        font-weight: 500;
        cursor: pointer;
        border-radius: var(--radius-md);
        transition: all var(--transition-fast);
        white-space: nowrap;
        position: relative;
    }
    
    .filter-tab.active {
        color: var(--primary-600);
        background: var(--primary-50);
    }
    
    .filter-tab.active::after {
        content: '';
        position: absolute;
        bottom: -var(--space-md);
        left: 0;
        right: 0;
        height: 2px;
        background: var(--primary-500);
    }
    
    .filter-tab:hover:not(.active) {
        background: var(--surface-variant);
        color: var(--on-surface);
    }
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
        }
        
        .conversations-header {
            flex-direction: column;
            align-items: stretch;
            gap: var(--space-md);
        }
        
        .new-conversation-btn {
            justify-content: center;
        }
        
        .conversation-header {
            flex-direction: column;
            gap: var(--space-sm);
        }
        
        .conversation-status {
            align-items: flex-start;
            flex-direction: row;
            justify-content: space-between;
        }
        
        .conversation-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: var(--space-sm);
        }
        
        .conversation-actions {
            justify-content: stretch;
        }
        
        .action-btn {
            flex: 1;
            justify-content: center;
        }
        
        .breadcrumb {
            justify-content: center;
        }
        
        .filter-tabs {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<!-- Conversations Hero -->
<section class="conversations-hero">
    <div class="container">
        <div class="hero-content fade-in">
            <!-- Breadcrumb -->
            <nav class="breadcrumb">
                <a href="{{ route('home') }}" class="breadcrumb-link">
                    <i class="fas fa-home"></i>
                    {{ __('Home') }}
                </a>
                <span class="breadcrumb-separator">/</span>
                <a href="{{ route('user.profile.show') }}" class="breadcrumb-link">
                    {{ __('Profile') }}
                </a>
                <span class="breadcrumb-separator">/</span>
                <span>{{ __('Messages') }}</span>
            </nav>
            
            <h1 class="hero-title">{{ __('My Messages') }}</h1>
            <p class="hero-subtitle">{{ __('Communicate with our support team and track your conversations') }}</p>
        </div>
    </div>
</section>

<!-- Conversations Container -->
<section class="conversations-container">
    <div class="container">
        <!-- Conversations Header -->
        <div class="conversations-header fade-in">
            <div class="conversations-count">
                {{ __('Total Conversations') }}: <strong>{{ $conversations->total() }}</strong>
            </div>
            
            <a href="{{ route('user.conversations.create') }}" class="new-conversation-btn">
                <i class="fas fa-plus"></i>
                {{ __('Start New Conversation') }}
            </a>
        </div>
        
        <!-- Search and Filters -->
        <div class="search-box fade-in">
            <i class="fas fa-search search-icon"></i>
            <input 
                type="text" 
                class="search-input" 
                placeholder="{{ __('Search conversations...') }}"
                id="search-input"
                oninput="filterConversations()"
            >
        </div>
        
        <div class="filter-tabs fade-in">
            <button class="filter-tab active" onclick="filterByStatus('all')">
                <i class="fas fa-comments"></i>
                {{ __('All') }}
            </button>
            <button class="filter-tab" onclick="filterByStatus('unread')">
                <i class="fas fa-envelope"></i>
                {{ __('Unread') }}
            </button>
            <button class="filter-tab" onclick="filterByStatus('open')">
                <i class="fas fa-envelope-open"></i>
                {{ __('Open') }}
            </button>
            <button class="filter-tab" onclick="filterByStatus('closed')">
                <i class="fas fa-archive"></i>
                {{ __('Closed') }}
            </button>
        </div>
        
        <!-- Conversations Grid -->
        @if($conversations->count() > 0)
            <div class="conversations-grid" id="conversations-grid">
                @foreach($conversations as $conversation)
                    <div class="conversation-card fade-in {{ !$conversation->is_read_by_user ? 'unread' : '' }}" 
                         data-status="{{ $conversation->status ?? 'open' }}"
                         data-title="{{ strtolower($conversation->title) }}"
                         data-url="{{ route('user.conversations.show', $conversation) }}"
                         style="cursor: pointer;">>>>
                        
                        <!-- Conversation Header -->
                        <div class="conversation-header">
                            <div class="conversation-info">
                                <div class="conversation-title">
                                    @if(!$conversation->is_read_by_user)
                                        <span class="unread-indicator"></span>
                                    @endif
                                    <i class="fas fa-comments"></i>
                                    {{ $conversation->title }}
                                </div>
                                <div class="conversation-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ $conversation->created_at->format('M d, Y') }}
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-clock"></i>
                                        {{ $conversation->updated_at->diffForHumans() }}
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-user"></i>
                                        {{ $conversation->user->name }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="conversation-status">
                                <span class="status-badge status-{{ $conversation->status ?? 'open' }}">
                                    {{ ucfirst($conversation->status ?? 'open') }}
                                </span>
                                <div class="conversation-time">
                                    {{ $conversation->updated_at->format('h:i A') }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Conversation Preview -->
                        @if($conversation->messages->first())
                            <div class="conversation-preview">
                                {{ Str::limit($conversation->messages->first()->message, 150) }}
                            </div>
                        @endif
                        
                        <!-- Conversation Actions -->
                        <div class="conversation-actions" onclick="event.stopPropagation()">
                            <div class="message-count">
                                <i class="fas fa-comment-dots"></i>
                                {{ $conversation->messages->count() }} {{ __('messages') }}
                            </div>
                            
                            <a href="{{ route('user.conversations.show', $conversation) }}" class="action-btn primary">
                                <i class="fas fa-eye"></i>
                                {{ __('View') }}
                            </a>
                            
                            @if(!$conversation->is_read_by_user)
                                <button class="action-btn" onclick="markAsRead('{{ $conversation->id }}')">
                                    <i class="fas fa-check"></i>
                                    {{ __('Mark Read') }}
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($conversations->hasPages())
                <div class="pagination-wrapper fade-in">
                    {{ $conversations->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="empty-state fade-in">
                <div class="empty-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <h2 class="empty-title">{{ __('No Conversations Yet') }}</h2>
                <p class="empty-text">
                    {{ __('You haven\'t started any conversations yet. Click the button below to start your first conversation with our support team.') }}
                </p>
                <a href="{{ route('user.conversations.create') }}" class="empty-cta">
                    <i class="fas fa-plus"></i>
                    {{ __('Start New Conversation') }}
                </a>
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Initialize animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 100);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    
    document.querySelectorAll('.fade-in').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.6s ease-out';
        observer.observe(el);
    });
    
    // Filter conversations by search
    function filterConversations() {
        const searchTerm = document.getElementById('search-input').value.toLowerCase();
        const conversationCards = document.querySelectorAll('.conversation-card');
        
        conversationCards.forEach(card => {
            const title = card.dataset.title;
            const text = card.textContent.toLowerCase();
            
            if (title.includes(searchTerm) || text.includes(searchTerm)) {
                card.style.display = 'block';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 50);
            } else {
                card.style.opacity = '0';
                card.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    card.style.display = 'none';
                }, 300);
            }
        });
    }
    
    // Filter conversations by status
    function filterByStatus(status) {
        // Update active tab
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        event.target.classList.add('active');
        
        const conversationCards = document.querySelectorAll('.conversation-card');
        
        conversationCards.forEach(card => {
            let show = false;
            
            switch (status) {
                case 'all':
                    show = true;
                    break;
                case 'unread':
                    show = card.classList.contains('unread');
                    break;
                case 'open':
                    show = card.dataset.status === 'open' || !card.dataset.status;
                    break;
                case 'closed':
                    show = card.dataset.status === 'closed';
                    break;
            }
            
            if (show) {
                card.style.display = 'block';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 50);
            } else {
                card.style.opacity = '0';
                card.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    card.style.display = 'none';
                }, 300);
            }
        });
        
        // Update URL parameter
        const url = new URL(window.location);
        if (status !== 'all') {
            url.searchParams.set('status', status);
        } else {
            url.searchParams.delete('status');
        }
        window.history.pushState({}, '', url);
    }
    
    // Mark conversation as read
    function markAsRead(conversationId) {
        fetch(`/user/conversations/${conversationId}/mark-read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const card = document.querySelector(`[onclick*="conversations/${conversationId}"]`);
                if (card) {
                    card.classList.remove('unread');
                    const unreadIndicator = card.querySelector('.unread-indicator');
                    if (unreadIndicator) {
                        unreadIndicator.remove();
                    }
                    const markReadBtn = card.querySelector('[onclick*="markAsRead"]');
                    if (markReadBtn) {
                        markReadBtn.remove();
                    }
                }
                showNotification('{{ __("Conversation marked as read") }}', 'success');
            } else {
                showNotification(data.message || '{{ __("Failed to mark as read") }}', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('{{ __("An error occurred") }}', 'error');
        });
    }
    
    // Show notification
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} slide-in`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 300px;
            box-shadow: var(--shadow-xl);
            animation: slideIn 0.3s ease-out;
        `;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            ${message}
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease-out forwards';
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
    
    // Add click handlers to conversation cards
    document.querySelectorAll('.conversation-card').forEach(card => {
        card.addEventListener('click', function(e) {
            // Don't navigate if clicking on action buttons
            if (!e.target.closest('.conversation-actions')) {
                const url = this.dataset.url;
                if (url) {
                    window.location.href = url;
                }
            }
        });
    });
    
    // Apply filters from URL parameters on page load
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        
        if (status) {
            const statusTab = document.querySelector(`[onclick*="filterByStatus('${status}')"]`);
            if (statusTab) {
                document.querySelectorAll('.filter-tab').forEach(tab => {
                    tab.classList.remove('active');
                });
                statusTab.classList.add('active');
                filterByStatus(status);
            }
        }
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // New conversation with Ctrl+N
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            window.location.href = '{{ route("user.conversations.create") }}';
        }
        
        // Search with Ctrl+F
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            document.getElementById('search-input').focus();
        }
    });
    
    // Auto-refresh for new messages (optional)
    let autoRefreshInterval;
    
    function startAutoRefresh() {
        autoRefreshInterval = setInterval(() => {
            fetch(window.location.href, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newGrid = doc.querySelector('#conversations-grid');
                const currentGrid = document.querySelector('#conversations-grid');
                
                if (newGrid && currentGrid) {
                    // Check if there are new conversations
                    const currentCount = currentGrid.children.length;
                    const newCount = newGrid.children.length;
                    
                    if (newCount > currentCount) {
                        showNotification('{{ __("New messages received") }}', 'info');
                        // Optionally update the grid
                        // currentGrid.innerHTML = newGrid.innerHTML;
                    }
                }
            })
            .catch(error => {
                console.log('Auto-refresh failed:', error);
            });
        }, 30000); // Refresh every 30 seconds
    }
    
    function stopAutoRefresh() {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
        }
    }
    
    // Start auto-refresh when page is visible
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            stopAutoRefresh();
        } else {
            startAutoRefresh();
        }
    });
    
    // Start auto-refresh on load
    startAutoRefresh();
    
    // Stop auto-refresh when leaving page
    window.addEventListener('beforeunload', stopAutoRefresh);
</script>

<style>
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
</style>
@endpush