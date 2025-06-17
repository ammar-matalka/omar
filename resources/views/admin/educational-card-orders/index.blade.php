@extends('layouts.admin')

@section('title', 'إدارة طلبات البطاقات التعليمية')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">إدارة طلبات البطاقات التعليمية</h1>
        <div>
            <a href="{{ route('admin.educational-card-orders.export') }}" class="btn btn-success btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-download"></i>
                </span>
                <span class="text">تصدير CSV</span>
            </a>
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">إجمالي الطلبات</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_orders'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">قيد الانتظار</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_orders'] }}</div>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">قيد المعالجة</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['processing_orders'] }}</div>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">مكتملة</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completed_orders'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- الفلاتر -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.educational-card-orders.index') }}">
                <div class="row">
                    <div class="col-md-2">
                        <label for="status">الحالة</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">جميع الحالات</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتملة</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغاة</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="generation_id">الجيل</label>
                        <select name="generation_id" id="generation_id" class="form-control">
                            <option value="">جميع الأجيال</option>
                            @foreach($generations as $generation)
                                <option value="{{ $generation->id }}" {{ request('generation_id') == $generation->id ? 'selected' : '' }}>
                                    {{ $generation->display_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="search">البحث</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               value="{{ request('search') }}" placeholder="اسم الطالب أو المستخدم">
                    </div>
                    <div class="col-md-2">
                        <label for="date_from">من تاريخ</label>
                        <input type="date" name="date_from" id="date_from" class="form-control" 
                               value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="date_to">إلى تاريخ</label>
                        <input type="date" name="date_to" id="date_to" class="form-control" 
                               value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-1">
                        <label>&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">قائمة طلبات البطاقات التعليمية</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>اسم الطالب</th>
                            <th>المستخدم</th>
                            <th>الجيل</th>
                            <th>الفصل</th>
                            <th>الكمية</th>
                            <th>المجموع</th>
                            <th>الحالة</th>
                            <th>التاريخ</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->student_name }}</td>
                                <td>
                                    <small>{{ $order->user->name }}</small><br>
                                    <small class="text-muted">{{ $order->user->email }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">
                                        {{ $order->generation->display_name }}
                                    </span>
                                </td>
                                <td>{{ $order->semester_text }}</td>
                                <td>{{ $order->quantity }}</td>
                                <td>{{ $order->formatted_total }}</td>
                                <td>
                                    <span class="badge badge-{{ $order->status_color }}">
                                        {{ $order->status_text }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.educational-card-orders.show', $order) }}" 
                                           class="btn btn-info btn-sm" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($order->status !== 'completed')
                                            <button type="button" class="btn btn-warning btn-sm" 
                                                    data-toggle="modal" data-target="#statusModal{{ $order->id }}" title="تغيير الحالة">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Modal لتغيير الحالة -->
                                    <div class="modal fade" id="statusModal{{ $order->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">تغيير حالة الطلب #{{ $order->id }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('admin.educational-card-orders.update-status', $order) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="status{{ $order->id }}">الحالة الجديدة</label>
                                                            <select name="status" id="status{{ $order->id }}" class="form-control">
                                                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                                                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>مكتملة</option>
                                                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>ملغاة</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="admin_notes{{ $order->id }}">ملاحظات الأدمن</label>
                                                            <textarea name="admin_notes" id="admin_notes{{ $order->id }}" 
                                                                      class="form-control" rows="3" 
                                                                      placeholder="ملاحظات إضافية (اختياري)">{{ $order->admin_notes }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                                                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">لا توجد طلبات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection