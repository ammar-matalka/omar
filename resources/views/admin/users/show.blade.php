@extends('layouts.admin')

@section('title', __('User Details'))
@section('page-title', __('User Details'))

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('admin.users.index') }}" class="breadcrumb-link">{{ __('Users') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        {{ $user->name }}
    </div>
@endsection

@push('styles')
<style>
    .user-show-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .user-header {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        margin-bottom: var(--space-xl);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .user-hero {
        display: grid;
        grid-template-columns: 200px 1fr auto;
        gap: var(--space-xl);
        padding: var(--space-2xl);
        align-items: center;
    }
    
    .user-avatar-section {
        text-align: center;
    }
    
    .user-avatar {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--admin-secondary-200);
        margin-bottom: var(--space-md);
        box-shadow: var(--shadow-lg);
    }
    
    .user-avatar-placeholder {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: var(--admin-primary-100);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--admin-primary-600);
        font-size: 3rem;
        font-weight: 700;
        margin: 0 auto var(--space-md);
        border: 4px solid var(--admin-secondary-200);
        box-shadow: var(--shadow-lg);
    }
    
    .user-status {
        display: inline-block;
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .status-verified {
        background: var(--success-100);
        color: var(--success-700);
    }
    
    .status-unverified {
        background: var(--warning-100);
        color: var(--warning-700);
    }
    
    .user-info {
        display: flex;
        flex-direction: column;
        gap: var(--space-sm);
    }
    
    .user-name {
        font-size: 2rem;
        font-weight: 800;
        color: var(--admin-secondary-900);
        margin: 0;
    }
    
    .user-email {
        font-size: 1.125rem;
        color: var(--admin-secondary-600);
        margin: 0;
    }
    
    .user-role {
        display: inline-block;
        padding: var(--space-sm) var(--space-md);
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: var(--space-sm);
    }
    
    .role-admin {
        background: var(--error-100);
        color: var(--error-700);
        border: 1px solid var(--error-200);
    }
    
    .role-user {
        background: var(--admin-primary-100);
        color: var(--admin-primary-700);
        border: 1px solid var(--admin-primary-200);
    }
    
    .user-meta {
        display: flex;
        flex-direction: column;
        gap: var(--space-xs);
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
    }
    
    .user-actions {
        display: flex;
        flex-direction: column;
        gap: var(--space-sm);
        align-items: flex-end;
    }
    
    .action-btn {
        padding: var(--space-sm) var(--space-lg);
        border: none;
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: all var(--transition-fast);
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        min-width: 120px;
        justify-content: center;
    }
    
    .action-btn.edit {
        background: var(--warning-500);
        color: white;
    }
    
    .action-btn.edit:hover {
        background: var(--warning-600);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    .action-btn.delete {
        background: var(--error-500);
        color: white;
    }
    
    .action-btn.delete:hover {
        background: var(--error-600);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    .action-btn.back {
        background: var(--admin-secondary-500);
        color: white;
    }
    
    .action-btn.back:hover {
        background: var(--admin-secondary-600);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    .user-stats {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        margin-bottom: var(--space-xl);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .stats-header {
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        color: white;
        padding: var(--space-lg) var(--space-xl);
    }
    
    .stats-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-xl);
        padding: var(--space-xl);
    }
    
    .stat-item {
        text-align: center;
        padding: var(--space-lg);
        border-radius: var(--radius-lg);
        background: var(--admin-secondary-50);
        border: 1px solid var(--admin-secondary-200);
        transition: all var(--transition-fast);
    }
    
    .stat-item:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    .stat-icon {
        font-size: 2rem;
        margin-bottom: var(--space-sm);
        color: var(--admin-primary-500);
    }
    
    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-xs);
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: var(--admin-secondary-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 500;
    }
    
    .user-details {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        margin-bottom: var(--space-xl);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .details-header {
        background: linear-gradient(135deg, var(--admin-secondary-500), var(--admin-secondary-600));
        color: white;
        padding: var(--space-lg) var(--space-xl);
    }
    
    .details-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .details-content {
        padding: var(--space-xl);
    }
    
    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--space-xl);
    }
    
    .detail-group {
        display: flex;
        flex-direction: column;
        gap: var(--space-lg);
    }
    
    .detail-item {
        display: flex;
        flex-direction: column;
        gap: var(--space-xs);
    }
    
    .detail-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--admin-secondary-700);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .detail-value {
        font-size: 1rem;
        color: var(--admin-secondary-900);
        font-weight: 500;
    }
    
    .detail-value.empty {
        color: var(--admin-secondary-400);
        font-style: italic;
    }
    
    .recent-activity {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-200);
    }
    
    .activity-header {
        background: linear-gradient(135deg, var(--success-500), var(--success-600));
        color: white;
        padding: var(--space-lg) var(--space-xl);
    }
    
    .activity-content {
        padding: var(--space-xl);
    }
    
    .activity-list {
        display: flex;
        flex-direction: column;
        gap: var(--space-lg);
    }
    
    .activity-item {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        padding: var(--space-md);
        border-radius: var(--radius-lg);
        background: var(--admin-secondary-50);
        border: 1px solid var(--admin-secondary-200);
        transition: all var(--transition-fast);
    }
    
    .activity-item:hover {
        background: var(--admin-secondary-100);
        transform: translateX(4px);
    }
    
    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: white;
    }
    
    .activity-order {
        background: var(--admin-primary-500);
    }
    
    .activity-login {
        background: var(--success-500);
    }
    
    .activity-register {
        background: var(--warning-500);
    }
    
    .activity-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: var(--space-xs);
    }
    
    .activity-title {
        font-weight: 600;
        color: var(--admin-secondary-900);
    }
    
    .activity-description {
        font-size: 0.875rem;
        color: var(--admin-secondary-600);
    }
    
    .activity-time {
        font-size: 0.75rem;
        color: var(--admin-secondary-500);
        font-weight: 500;
    }
    
    .empty-activity {
        text-align: center;
        padding: var(--space-2xl);
        color: var(--admin-secondary-500);
    }
    
    .empty-icon {
        font-size: 3rem;
        margin-bottom: var(--space-md);
        opacity: 0.5;
    }
    
    @media (max-width: 768px) {
        .user-show-container {
            margin: 0;
        }
        
        .user-hero {
            grid-template-columns: 1fr;
            gap: var(--space-lg);
            text-align: center;
        }
        
        .user-actions {
            flex-direction: row;
            justify-content: center;
            align-items: center;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .details-grid {
            grid-template-columns: 1fr;
        }
        
        .activity-item {
            flex-direction: column;
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
<div class="user-show-container">
    <!-- User Header -->
    <div class="user-header fade-in">
        <div class="user-hero">
            <!-- Avatar Section -->
            <div class="user-avatar-section">
                @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="user-avatar">
                @else
                    <div class="user-avatar-placeholder">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                @endif
                
                @if($user->email_verified_at)
                    <div class="user-status status-verified">
                        <i class="fas fa-check-circle"></i>
                        {{ __('Verified') }}
                    </div>
                @else
                    <div class="user-status status-unverified">
                        <i class="fas fa-clock"></i>
                        {{ __('Unverified') }}
                    </div>
                @endif
            </div>
            
            <!-- User Info -->
            <div class="user-info">
                <h1 class="user-name">{{ $user->name }}</h1>
                <p class="user-email">{{ $user->email }}</p>
                
                <div class="user-role role-{{ $user->role ?? 'user' }}">
                    <i class="fas fa-{{ ($user->role ?? 'user') === 'admin' ? 'crown' : 'user' }}"></i>
                    {{ ucfirst($user->role ?? 'user') }}
                </div>
                
                <div class="user-meta">
                    <div class="meta-item">
                        <i class="fas fa-calendar-plus"></i>
                        {{ __('Joined') }} {{ $user->created_at->format('M d, Y') }}
                    </div>
                    
                    @if($user->last_login_at)
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            {{ __('Last login') }} {{ $user->last_login_at->diffForHumans() }}
                        </div>
                    @endif
                    
                    @if($user->phone)
                        <div class="meta-item">
                            <i class="fas fa-phone"></i>
                            {{ $user->phone }}
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Actions -->
            <div class="user-actions">
                <a href="{{ route('admin.users.edit', $user) }}" class="action-btn edit">
                    <i class="fas fa-edit"></i>
                    {{ __('Edit User') }}
                </a>
                
                @if($user->id !== auth()->id())
                    <button class="action-btn delete" onclick="deleteUser('{{ $user->id }}')">
                        <i class="fas fa-trash"></i>
                        {{ __('Delete User') }}
                    </button>
                @endif
                
                <a href="{{ route('admin.users.index') }}" class="action-btn back">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('Back to Users') }}
                </a>
            </div>
        </div>
    </div>
    
    <!-- User Statistics -->
    <div class="user-stats fade-in">
        <div class="stats-header">
            <h2 class="stats-title">
                <i class="fas fa-chart-bar"></i>
                {{ __('User Statistics') }}
            </h2>
        </div>
        
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="stat-value">{{ $user->orders->count() ?? 0 }}</div>
                <div class="stat-label">{{ __('Total Orders') }}</div>
            </div>
            
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-value">${{ number_format($user->orders->sum('total') ?? 0, 2) }}</div>
                <div class="stat-label">{{ __('Total Spent') }}</div>
            </div>
            
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-value">{{ $user->testimonials->count() ?? 0 }}</div>
                <div class="stat-label">{{ __('Testimonials') }}</div>
            </div>
            
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="stat-value">{{ $user->wishlist->count() ?? 0 }}</div>
                <div class="stat-label">{{ __('Wishlist Items') }}</div>
            </div>
        </div>
    </div>
    
    <!-- User Details -->
    <div class="user-details fade-in">
        <div class="details-header">
            <h2 class="details-title">
                <i class="fas fa-info-circle"></i>
                {{ __('Account Details') }}
            </h2>
        </div>
        
        <div class="details-content">
            <div class="details-grid">
                <div class="detail-group">
                    <div class="detail-item">
                        <div class="detail-label">{{ __('Full Name') }}</div>
                        <div class="detail-value">{{ $user->name }}</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">{{ __('Email Address') }}</div>
                        <div class="detail-value">{{ $user->email }}</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">{{ __('Phone Number') }}</div>
                        <div class="detail-value {{ $user->phone ? '' : 'empty' }}">
                            {{ $user->phone ?: __('Not provided') }}
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">{{ __('User Role') }}</div>
                        <div class="detail-value">{{ ucfirst($user->role ?? 'user') }}</div>
                    </div>
                </div>
                
                <div class="detail-group">
                    <div class="detail-item">
                        <div class="detail-label">{{ __('Registration Date') }}</div>
                        <div class="detail-value">{{ $user->created_at->format('M d, Y \a\t g:i A') }}</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">{{ __('Email Verification') }}</div>
                        <div class="detail-value">
                            @if($user->email_verified_at)
                                {{ __('Verified on') }} {{ $user->email_verified_at->format('M d, Y \a\t g:i A') }}
                            @else
                                {{ __('Not verified') }}
                            @endif
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">{{ __('Last Login') }}</div>
                        <div class="detail-value {{ $user->last_login_at ? '' : 'empty' }}">
                            @if($user->last_login_at)
                                {{ $user->last_login_at->format('M d, Y \a\t g:i A') }}
                                <br><small>({{ $user->last_login_at->diffForHumans() }})</small>
                            @else
                                {{ __('Never logged in') }}
                            @endif
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">{{ __('Account Status') }}</div>
                        <div class="detail-value">
                            @if($user->email_verified_at)
                                <span style="color: var(--success-600);">{{ __('Active') }}</span>
                            @else
                                <span style="color: var(--warning-600);">{{ __('Pending Verification') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="recent-activity fade-in">
        <div class="activity-header">
            <h2 class="details-title">
                <i class="fas fa-history"></i>
                {{ __('Recent Activity') }}
            </h2>
        </div>
        
        <div class="activity-content">
            @if($user->orders->count() > 0 || $user->last_login_at)
                <div class="activity-list">
                    @if($user->last_login_at)
                        <div class="activity-item">
                            <div class="activity-icon activity-login">
                                <i class="fas fa-sign-in-alt"></i>
                            </div>
                            <div class="activity-info">
                                <div class="activity-title">{{ __('Last Login') }}</div>
                                <div class="activity-description">{{ __('User logged into the system') }}</div>
                                <div class="activity-time">{{ $user->last_login_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    @endif
                    
                    @foreach($user->orders->take(5) as $order)
                        <div class="activity-item">
                            <div class="activity-icon activity-order">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <div class="activity-info">
                                <div class="activity-title">{{ __('Order') }} #{{ $order->order_number }}</div>
                                <div class="activity-description">
                                    {{ __('Placed an order worth') }} ${{ number_format($order->total, 2) }}
                                </div>
                                <div class="activity-time">{{ $order->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    @endforeach
                    
                    <div class="activity-item">
                        <div class="activity-icon activity-register">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="activity-info">
                            <div class="activity-title">{{ __('Account Created') }}</div>
                            <div class="activity-description">{{ __('User registered on the platform') }}</div>
                            <div class="activity-time">{{ $user->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>
            @else
                <div class="empty-activity">
                    <div class="empty-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="empty-title">{{ __('No Recent Activity') }}</div>
                    <p class="empty-text">{{ __('This user has no recent activity to display.') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: var(--space-xl); border-radius: var(--radius-xl); max-width: 400px; width: 90%; text-align: center;">
        <div style="color: var(--error-500); font-size: 3rem; margin-bottom: var(--space-lg);">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 style="margin-bottom: var(--space-md); color: var(--admin-secondary-900);">{{ __('Delete User') }}</h3>
        <p style="margin-bottom: var(--space-xl); color: var(--admin-secondary-600);">
            {{ __('Are you sure you want to delete this user? This action cannot be undone and will remove all associated data.') }}
        </p>
        <div style="display: flex; gap: var(--space-md); justify-content: center;">
            <button onclick="closeDeleteModal()" class="btn btn-secondary">{{ __('Cancel') }}</button>
            <button onclick="confirmDelete()" class="btn btn-danger">{{ __('Delete') }}</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let userToDelete = null;
    
    // Delete user functions
    function deleteUser(userId) {
        userToDelete = userId;
        document.getElementById('deleteModal').style.display = 'flex';
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
        userToDelete = null;
    }
    
    function confirmDelete() {
        if (userToDelete) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/users/${userToDelete}`;
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = '{{ csrf_token() }}';
            
            form.appendChild(methodInput);
            form.appendChild(tokenInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
    
    // Initialize animations
    document.addEventListener('DOMContentLoaded', function() {
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
    });
</script>
@endpush