@extends('layouts.admin')

@section('title', __('Educational Card Orders') . ' - ' . config('app.name'))

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 text-gray-800 mb-0">{{ __('Educational Card Orders') }}</h1>
                    <p class="mb-0 text-gray-600">{{ __('Manage and track educational card orders') }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.educational-card-orders.export', request()->query()) }}" 
                       class="btn btn-success btn-sm">
                        <i class="fas fa-download me-1"></i>
                        {{ __('Export CSV') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                {{ __('Total Orders') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_orders']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                {{ __('Pending Orders') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['pending_orders']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                {{ __('Processing Orders') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['processing_orders']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cog fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                {{ __('Total Revenue') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($stats['total_revenue'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('Filters') }}</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.educational-card-orders.index') }}" class="row">
                <div class="col-md-2">
                    <label for="status" class="form-label">{{ __('Status') }}</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">{{ __('All Statuses') }}</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>{{ __('Processing') }}</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="academic_year" class="form-label">{{ __('Academic Year') }}</label>
                    <select name="academic_year" id="academic_year" class="form-select">
                        <option value="">{{ __('All Years') }}</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="subject" class="form-label">{{ __('Subject') }}</label>
                    <select name="subject" id="subject" class="form-select">
                        <option value="">{{ __('All Subjects') }}</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject }}" {{ request('subject') == $subject ? 'selected' : '' }}>
                                {{ $subject }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="search" class="form-label">{{ __('Search') }}</label>
                    <input type="text" name="search" id="search" class="form-control" 
                           placeholder="{{ __('User name or email...') }}" value="{{ request('search') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>
                            {{ __('Filter') }}
                        </button>
                        <a href="{{ route('admin.educational-card-orders.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>
                            {{ __('Clear') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('Orders List') }}</h6>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-warning" onclick="bulkAction('mark_processing')">
                    <i class="fas fa-cog me-1"></i>
                    {{ __('Mark Processing') }}
                </button>
                <button type="button" class="btn btn-sm btn-success" onclick="bulkAction('mark_completed')">
                    <i class="fas fa-check me-1"></i>
                    {{ __('Mark Completed') }}
                </button>
                <button type="button" class="btn btn-sm btn-danger" onclick="bulkAction('delete')">
                    <i class="fas fa-trash me-1"></i>
                    {{ __('Delete Selected') }}
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="ordersTable">
                        <thead>
                            <tr>
                                <th width="50">
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('User') }}</th>
                                <th>{{ __('Academic Year') }}</th>
                                <th>{{ __('Subject') }}</th>
                                <th>{{ __('Teacher') }}</th>
                                <th>{{ __('Total') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th width="120">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="order-checkbox" value="{{ $order->id }}">
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.educational-card-orders.show', $order) }}" 
                                           class="text-primary font-weight-bold">
                                            #{{ $order->id }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <div class="avatar-title bg-primary rounded-circle">
                                                    {{ substr($order->user->name, 0, 1) }}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold">{{ $order->user->name }}</div>
                                                <small class="text-muted">{{ $order->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $order->academic_year }}</td>
                                    <td>{{ $order->subject }}</td>
                                    <td>{{ $order->teacher }}</td>
                                    <td class="font-weight-bold">${{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $order->status_badge }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.educational-card-orders.show', $order) }}" 
                                               class="btn btn-sm btn-outline-primary" title="{{ __('View') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.educational-card-orders.destroy', $order) }}" 
                                                  method="POST" class="d-inline" 
                                                  onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        title="{{ __('Delete') }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        {{ __('Showing') }} {{ $orders->firstItem() }} {{ __('to') }} {{ $orders->lastItem() }} 
                        {{ __('of') }} {{ $orders->total() }} {{ __('results') }}
                    </div>
                    {{ $orders->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-list-alt fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">{{ __('No orders found') }}</h5>
                    <p class="text-gray-500">{{ __('Orders will appear here when users submit them.') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.order-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Bulk actions
function bulkAction(action) {
    const selectedOrders = [];
    document.querySelectorAll('.order-checkbox:checked').forEach(checkbox => {
        selectedOrders.push(checkbox.value);
    });

    if (selectedOrders.length === 0) {
        alert('{{ __("Please select at least one order") }}');
        return;
    }

    if (!confirm('{{ __("Are you sure you want to perform this action?") }}')) {
        return;
    }

    fetch('{{ route("admin.educational-card-orders.bulk-update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            order_ids: selectedOrders,
            action: action
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        alert('{{ __("Error performing bulk action") }}');
    });
}
</script>
@endpush
@endsection