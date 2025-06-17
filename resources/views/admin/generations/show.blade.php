@extends('layouts.admin')

@section('title', 'عرض الجيل: ' . $generation->name)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">عرض الجيل: {{ $generation->name }}</h1>
        <div>
            <a href="{{ route('admin.generations.edit', $generation) }}" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-edit"></i>
                </span>
                <span class="text">تعديل</span>
            </a>
            <a href="{{ route('admin.generations.index') }}" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">العودة للقائمة</span>
            </a>
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

    <div class="row">
        <!-- معلومات الجيل -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">معلومات الجيل</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>ID:</strong></td>
                            <td>{{ $generation->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>الاسم:</strong></td>
                            <td>{{ $generation->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>السنة:</strong></td>
                            <td>{{ $generation->year }}</td>
                        </tr>
                        <tr>
                            <td><strong>الوصف:</strong></td>
                            <td>{{ $generation->description ?: 'لا يوجد وصف' }}</td>
                        </tr>
                        <tr>
                            <td><strong>الحالة:</strong></td>
                            <td>
                                @if($generation->is_active)
                                    <span class="badge badge-success">فعال</span>
                                @else
                                    <span class="badge badge-danger">غير فعال</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>ترتيب العرض:</strong></td>
                            <td>{{ $generation->order }}</td>
                        </tr>
                        <tr>
                            <td><strong>تاريخ الإنشاء:</strong></td>
                            <td>{{ $generation->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <td><strong>آخر تحديث:</strong></td>
                            <td>{{ $generation->updated_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- إحصائيات -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">إحصائيات</h6>
                </div>
                <div class="card-body">
                    <div class="row no-gutters">
                        <div class="col-6 text-center">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">المواد</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $generation->subjects->count() }}</div>
                        </div>
                        <div class="col-6 text-center">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">الطلبات</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orderStats['total_orders'] }}</div>
                        </div>
                    </div>
                    <hr>
                    <div class="row no-gutters">
                        <div class="col-6 text-center">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">قيد الانتظار</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orderStats['pending_orders'] }}</div>
                        </div>
                        <div class="col-6 text-center">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">مكتملة</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orderStats['completed_orders'] }}</div>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">إجمالي الإيرادات</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($orderStats['total_revenue'], 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- المواد التابعة للجيل -->
    @if($generation->subjects->count() > 0)
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">المواد التابعة للجيل</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>اسم المادة</th>
                            <th>السعر</th>
                            <th>الحالة</th>
                            <th>الترتيب</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($generation->subjects as $subject)
                            <tr>
                                <td>{{ $subject->id }}</td>
                                <td>{{ $subject->name }}</td>
                                <td>${{ number_format($subject->price, 2) }}</td>
                                <td>
                                    @if($subject->is_active)
                                        <span class="badge badge-success">فعال</span>
                                    @else
                                        <span class="badge badge-danger">غير فعال</span>
                                    @endif
                                </td>
                                <td>{{ $subject->order }}</td>
                                <td>
                                    <a href="{{ route('admin.educational-subjects.show', $subject) }}" 
                                       class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.educational-subjects.edit', $subject) }}" 
                                       class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection