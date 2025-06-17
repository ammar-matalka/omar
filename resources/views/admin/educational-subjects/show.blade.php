@extends('layouts.admin')

@section('title', 'عرض المادة: ' . $educationalSubject->name)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">عرض المادة: {{ $educationalSubject->name }}</h1>
        <div>
            <a href="{{ route('admin.educational-subjects.edit', $educationalSubject) }}" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-edit"></i>
                </span>
                <span class="text">تعديل</span>
            </a>
            <a href="{{ route('admin.educational-subjects.index') }}" class="btn btn-secondary btn-icon-split">
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
        <!-- معلومات المادة -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">معلومات المادة</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>ID:</strong></td>
                            <td>{{ $educationalSubject->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>اسم المادة:</strong></td>
                            <td>{{ $educationalSubject->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>الجيل:</strong></td>
                            <td>
                                <span class="badge badge-secondary">
                                    {{ $educationalSubject->generation->display_name }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>السعر:</strong></td>
                            <td>
                                <span class="h5 text-success">{{ $educationalSubject->formatted_price }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>الحالة:</strong></td>
                            <td>
                                @if($educationalSubject->is_active)
                                    <span class="badge badge-success">فعال</span>
                                @else
                                    <span class="badge badge-danger">غير فعال</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>ترتيب العرض:</strong></td>
                            <td>{{ $educationalSubject->order }}</td>
                        </tr>
                        <tr>
                            <td><strong>تاريخ الإنشاء:</strong></td>
                            <td>{{ $educationalSubject->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <td><strong>آخر تحديث:</strong></td>
                            <td>{{ $educationalSubject->updated_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- إحصائيات المادة -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">إحصائيات المادة</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">إجمالي الطلبات</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $orderStats['total_orders'] }}</div>
                    </div>
                    <hr>
                    <div class="text-center mb-3">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">إجمالي الكمية</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $orderStats['total_quantity'] }}</div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">إجمالي الإيرادات</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">{{ number_format($orderStats['total_revenue'], 2) }} JD</div>
                    </div>
                </div>
            </div>

            <!-- إجراءات سريعة -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-secondary">إجراءات سريعة</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form action="{{ route('admin.educational-subjects.toggle-status', $educationalSubject) }}" 
                              method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-{{ $educationalSubject->is_active ? 'warning' : 'success' }} btn-block">
                                <i class="fas fa-{{ $educationalSubject->is_active ? 'ban' : 'check' }}"></i>
                                {{ $educationalSubject->is_active ? 'إلغاء تفعيل' : 'تفعيل' }} المادة
                            </button>
                        </form>

                        @if($orderStats['total_orders'] == 0)
                            <form action="{{ route('admin.educational-subjects.destroy', $educationalSubject) }}" 
                                  method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه المادة؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-block">
                                    <i class="fas fa-trash"></i> حذف المادة
                                </button>
                            </form>
                        @else
                            <div class="alert alert-warning alert-sm">
                                <i class="fas fa-exclamation-triangle"></i>
                                لا يمكن حذف المادة لوجود طلبات عليها
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- معلومات الجيل -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">معلومات الجيل التابع له</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>اسم الجيل:</strong></td>
                            <td>{{ $educationalSubject->generation->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>السنة:</strong></td>
                            <td>{{ $educationalSubject->generation->year }}</td>
                        </tr>
                        <tr>
                            <td><strong>الوصف:</strong></td>
                            <td>{{ $educationalSubject->generation->description ?: 'لا يوجد وصف' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <div class="text-center">
                        <a href="{{ route('admin.generations.show', $educationalSubject->generation) }}" 
                           class="btn btn-info">
                            <i class="fas fa-eye"></i> عرض تفاصيل الجيل
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection