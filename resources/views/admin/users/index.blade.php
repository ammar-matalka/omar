@extends('layouts.admin')

@section('title', __('Users Management'))
@section('page-title', __('Users Management'))

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        {{ __('Users') }}
    </div>
@endsection

@push('styles')
<style>
    .users-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-xl);
        flex-wrap: wrap;
        gap: var(--space-md);
    }
    
    .users-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .users-stats {
        display: flex;
        gap: var(--space-lg);
        flex-wrap: wrap;
    }
    
    .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: var(--space-md);
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--admin-secondary-200);
        min-width: 100px;
    }
    
    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--admin-primary-600);
    }
    
    .stat-label {
        font-size: 0.75rem;
        color: var(--admin-secondary-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: var(--space-xs);
    }
    
    .filters-section {
        background: white;
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        margin-bottom: var(--space-xl);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .filters-title {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: var(--space-lg);
        color: var(--admin-secondary-900);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-lg);
        margin-bottom: var(--space-lg);
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: var(--space-sm);
    }
    
    .filter-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--admin-secondary-700);
    }
    
    .filter-input {
        padding: var(--space-sm) var(--space-md);
        border: 1px solid var(--admin-secondary-300);
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        transition: border-color var(--transition-fast);
    }
    
    .filter-input:focus {
        outline: none;
        border-color: var(--admin-primary-500);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .filter-actions {
        display: flex;
        gap: var(--space-sm);
        justify-content: flex-end;
    }
    
    .users-table-container {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-200);
    }
    
    .table-header {
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        color: white;
        padding: var(--space-lg) var(--space-xl);
    }
    
    .table-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .users-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .users-table th {
        background: var(--admin-secondary-50);
        padding: var(--space-md) var(--space-lg);
        text-align: left;
        font-weight: 600;
        color: var(--admin-secondary-700);
        border-bottom: 1px solid var(--admin-secondary-200);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .users-table td {
        padding: var(--space-md) var(--space-lg);
        border-bottom: 1px solid var(--admin-secondary-100);
        color: var(--admin-secondary-700);
        vertical-align: middle;
    }
    
    .users-table tbody tr:hover {
        background: var(--admin-secondary-50);
    }
    
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--admin-secondary-200);
    }
    
    .user-avatar-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--admin-primary-100);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--admin-primary-600);
        font-weight: 600;
        font-size: 1rem;
    }
    
    .user-info {
        display: flex;
        align-items: center;
        gap: var(--space-md);
    }
    
    .user-details {
        display: flex;
        flex-direction: column;
    }
    
    .user-name {
        font-weight: 600;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-xs);
    }
    
    .user-email {
        font-size: 0.875rem;
        color: var(--admin-secondary-600);
    }
    
    .user-role {
        display: inline-block;
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .role-admin {
        background: var(--error-100);
        color: var(--error-700);
    }
    
    .role-user {
        background: var(--admin-primary-100);
        color: var(--admin-primary-700);
    }
    
    .status-badge {
        display: inline-block;
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .status-active {
        background: var(--success-100);
        color: var(--success-700);
    }
    
    .status-inactive {
        background: var(--error-100);
        color: var(--error-700);
    }
    
    .status-pending {
        background: var(--warning-100);
        color: var(--warning-700);
    }
    
    .user-actions {
        display: flex;
        gap: var(--space-xs);
        align-items: center;
    }
    
    .action-btn {
        padding: var(--space-xs) var(--space-sm);
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all var(--transition-fast);
        font-size: 0.75rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
    }
    
    .action-btn.view {
        background: var(--admin-primary-100);
        color: var(--admin-primary-600);
    }
    
    .action-btn.view:hover {
        background: var(--admin-primary-200);
        transform: translateY(-1px);
    }
    
    .action-btn.edit {
        background: var(--warning-100);
        color: var(--warning-600);
    }
    
    .action-btn.edit:hover {
        background: var(--warning-200);
        transform: translateY(-1px);
    }
    
    .action-btn.delete {
        background: var(--error-100);
        color: var(--error-600);
    }
    
    .action-btn.delete:hover {
        background: var(--error-200);
        transform: translateY(-1px);
    }
    
    .empty-state {
        text-align: center;
        padding: var(--space-3xl);
        color: var(--admin-secondary-500);
    }
    
    .empty-icon {
        font-size: 4rem;
        margin-bottom: var(--space-lg);
        opacity: 0.5;
    }
    
    .empty-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: var(--space-sm);
        color: var(--admin-secondary-700);
    }
    
    .empty-text {
        margin-bottom: var(--space-xl);
        font-size: 1.125rem;
    }
    
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: var(--space-xl);
    }
    
    .join-date {
        font-size: 0.875rem;
        color: var(--admin-secondary-600);
    }
    
    .last-login {
        font-size: 0.875rem;
        color: var(--admin-secondary-600);
    }
    
    @media (max-width: 768px) {
        .users-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .users-stats {
            width: 100%;
            justify-content: space-between;
        }
        
        .filters-grid {
            grid-template-columns: 1fr;
        }
        
        .users-table-container {
            overflow-x: auto;
        }
        
        .users-table {
            min-width: 600px;
        }
        
        .user-info {
            flex-direction: column;
            align-items: flex-start;
            gap: var(--space-sm);
        }
    }
</style>
@endpush

@section('content')
<!-- Users Header -->
<div class="users-header">
    <div>
        <h1 class="users-title">
            <i class="fas fa-users"></i>
            {{ __('Users Management') }}
        </h1>
    </div>
    
    <div class="users-stats">
        <div class="stat-item">
            <div class="stat-number">{{ $users->total() }}</div>
            <div class="stat-label">{{ __('Total Users') }}</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">{{ $users->where('role', 'admin')->count() }}</div>
            <div class="stat-label">{{ __('Admins') }}</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">{{ $users->where('email_verified_at', '!=', null)->count() }}</div>
            <div class="stat-label">{{ __('Verified') }}</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">{{ $users->where('created_at', '>=', now()->subDays(30))->count() }}</div>
            <div class="stat-label">{{ __('New (30d)') }}</div>
        </div>
        
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            {{ __('Add User') }}
        </a>
    </div>
</div>

<!-- Filters Section -->
<div class="filters-section fade-in">
    <h2 class="filters-title">
        <i class="fas fa-filter"></i>
        {{ __('Filter Users') }}
    </h2>
    
    <form method="GET" action="{{ route('admin.users.index') }}">
        <div class="filters-grid">
            <div class="filter-group">
                <label class="filter-label">{{ __('Search') }}</label>
                <input 
                    type="text" 
                    name="search" 
                    class="filter-input" 
                    placeholder="{{ __('Search by name or email...') }}"
                    value="{{ request('search') }}"
                >
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('Role') }}</label>
                <select name="role" class="filter-input">
                    <option value="">{{ __('All Roles') }}</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>{{ __('Admin') }}</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>{{ __('User') }}</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('Status') }}</label>
                <select name="status" class="filter-input">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>{{ __('Verified') }}</option>
                    <option value="unverified" {{ request('status') == 'unverified' ? 'selected' : '' }}>{{ __('Unverified') }}</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('Registration Date') }}</label>
                <select name="date_filter" class="filter-input">
                    <option value="">{{ __('All Time') }}</option>
                    <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>{{ __('Today') }}</option>
                    <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>{{ __('This Week') }}</option>
                    <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>{{ __('This Month') }}</option>
                    <option value="year" {{ request('date_filter') == 'year' ? 'selected' : '' }}>{{ __('This Year') }}</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('Sort By') }}</label>
                <select name="sort" class="filter-input">
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>{{ __('Name') }}</option>
                    <option value="email" {{ request('sort') == 'email' ? 'selected' : '' }}>{{ __('Email') }}</option>
                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>{{ __('Registration Date') }}</option>
                    <option value="last_login" {{ request('sort') == 'last_login' ? 'selected' : '' }}>{{ __('Last Login') }}</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('Order') }}</label>
                <select name="order" class="filter-input">
                    <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>{{ __('Ascending') }}</option>
                    <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>{{ __('Descending') }}</option>
                </select>
            </div>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
                {{ __('Filter') }}
            </button>
            
            @if(request()->hasAny(['search', 'role', 'status', 'date_filter', 'sort', 'order']))
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    {{ __('Clear Filters') }}
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Users Table -->
@if($users->count() > 0)
    <div class="users-table-container fade-in">
        <div class="table-header">
            <h2 class="table-title">
                <i class="fas fa-table"></i>
                {{ __('Users List') }}
            </h2>
        </div>
        
        <table class="users-table">
            <thead>
                <tr>
                    <th>{{ __('User') }}</th>
                    <th>{{ __('Role') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Joined') }}</th>
                    <th>{{ __('Last Login') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="user-info">
                                @if($user->avatar)
                                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="user-avatar">
                                @else
                                    <div class="user-avatar-placeholder">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                @endif
                                
                                <div class="user-details">
                                    <div class="user-name">{{ $user->name }}</div>
                                    <div class="user-email">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        
                        <td>
                            <span class="user-role role-{{ $user->role ?? 'user' }}">
                                {{ ucfirst($user->role ?? 'user') }}
                            </span>
                        </td>
                        
                        <td>
                            @if($user->email_verified_at)
                                <span class="status-badge status-active">{{ __('Verified') }}</span>
                            @else
                                <span class="status-badge status-pending">{{ __('Unverified') }}</span>
                            @endif
                        </td>
                        
                        <td>
                            <div class="join-date">
                                {{ $user->created_at->format('M d, Y') }}<br>
                                <small>{{ $user->created_at->diffForHumans() }}</small>
                            </div>
                        </td>
                        
                        <td>
                            <div class="last-login">
                                @if($user->last_login_at)
                                    {{ $user->last_login_at->format('M d, Y') }}<br>
                                    <small>{{ $user->last_login_at->diffForHumans() }}</small>
                                @else
                                    <span class="text-muted">{{ __('Never') }}</span>
                                @endif
                            </div>
                        </td>
                        
                        <td>
                            <div class="user-actions">
                                <a href="{{ route('admin.users.show', $user) }}" class="action-btn view" title="{{ __('View') }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <a href="{{ route('admin.users.edit', $user) }}" class="action-btn edit" title="{{ __('Edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                @if($user->id !== auth()->id())
                                    <button class="action-btn delete" onclick="deleteUser('{{ $user->id }}')" title="{{ __('Delete') }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($users->hasPages())
        <div class="pagination-wrapper">
            {{ $users->appends(request()->query())->links() }}
        </div>
    @endif
@else
    <div class="users-table-container fade-in">
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3 class="empty-title">{{ __('No Users Found') }}</h3>
            <p class="empty-text">
                @if(request()->hasAny(['search', 'role', 'status', 'date_filter']))
                    {{ __('No users match your search criteria.') }}
                @else
                    {{ __('Start by adding your first user to the system.') }}
                @endif
            </p>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i>
                {{ __('Add First User') }}
            </a>
        </div>
    </div>
@endif

<!-- Delete Confirmation Modal -->
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: var(--space-xl); border-radius: var(--radius-xl); max-width: 400px; width: 90%; text-align: center;">
        <div style="color: var(--error-500); font-size: 3rem; margin-bottom: var(--space-lg);">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 style="margin-bottom: var(--space-md); color: var(--admin-secondary-900);">{{ __('Delete User') }}</h3>
        <p style="margin-bottom: var(--space-xl); color: var(--admin-secondary-600);">
            {{ __('Are you sure you want to delete this user? This action cannot be undone.') }}
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