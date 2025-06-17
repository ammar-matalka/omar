@extends('layouts.admin')

@section('title', 'عرض طلب البطاقة التعليمية #' . $educationalCardOrder->id)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">عرض طلب البطاقة التعليمية #{{ $educationalCardOrder->id }}</h1>
        <a href="{{ route('admin.educational-card-orders.index') }}" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-left"></i>
            </span>
            <span class="text">العودة للقائمة</span>
        </a>
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
        <!-- معلومات الطلب -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">تفاصيل الطلب</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>رقم الطلب:</strong></td>
                                    <td>{{ $educationalCardOrder->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>اسم الطالب:</strong></td>
                                    <td>{{ $educationalCardOrder->student_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>الجيل:</strong></td>
                                    <td>
                                        <span class="badge badge-secondary">
                                            {{ $educationalCardOrder->generation->display_name }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>الفصل:</strong></td>
                                    <td>{{ $educationalCardOrder->semester_text }}</td>
                                </tr>
                                <tr>
                                    <td><strong>الكمية:</strong></td>
                                    <td>{{ $educationalCardOrder->quantity }}</td>
                                </tr>
                                <tr>
                                    <td><strong>المجموع الكلي:</strong></td>
                                    <td>
                                        <span class="h5 text-success">{{ $educationalCardOrder->formatted_total }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>الحالة:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $educationalCardOrder->status_color }}">
                                            {{ $educationalCardOrder->status_text }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>رقم الهاتف:</strong></td>
                                    <td>{{ $educationalCardOrder->phone ?: 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>العنوان:</strong></td>
                                    <td>{{ $educationalCardOrder->address ?: 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>تاريخ الطلب:</strong></td>
                                    <td>{{ $educationalCardOrder->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>آخر تحديث:</strong></td>
                                    <td>{{ $educationalCardOrder->updated_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($educationalCardOrder->notes)
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h6><strong>ملاحظات الطالب:</strong></h6>
                                <div class="alert alert-info">
                                    {{ $educationalCardOrder->notes }}
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($educationalCardOrder->admin_notes)
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h6><strong>ملاحظات الأدمن:</strong></h6>
                                <div class="alert alert-warning">
                                    {{ $educationalCardOrder->admin_notes }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- معلومات المستخدم -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">معلومات المستخدم</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>الاسم:</strong></td>
                                    <td>{{ $educationalCardOrder->user->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>الإيميل:</strong></td>
                                    <td>{{ $educationalCardOrder->user->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>رقم الهاتف:</strong></td>
                                    <td>{{ $educationalCardOrder->user->phone ?: 'غير محدد' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>تاريخ التسجيل:</strong></td>
                                    <td>{{ $educationalCardOrder->user->created_at->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>إجمالي الطلبات:</strong></td>
                                    <td>{{ $educationalCardOrder->user->educationalCardOrders()->count() }}</td>
                                </tr>
                            </table>
                            <div class="text-center">
                                <a href="{{ route('admin.users.show', $educationalCardOrder->user) }}" 
                                   class="btn btn-info btn-sm">
                                    <i class="fas fa-user"></i> عرض ملف المستخدم
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- المواد المطلوبة -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">المواد المطلوبة</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>اسم المادة</th>
                                    <th>السعر الوحدة</th>
                                    <th>الكمية</th>
                                    <th>المجموع</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($educationalCardOrder->orderItems as $item)
                                    <tr>
                                        <td>{{ $item->subject_name }}</td>
                                        <td>{{ $item->formatted_price }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->formatted_subtotal }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-active">
                                    <td colspan="3"><strong>الإجمالي النهائي:</strong></td>
                                    <td><strong>{{ $educationalCardOrder->formatted_total }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- الإجراءات -->
        <div class="col-lg-4">
            @if($educationalCardOrder->status !== 'completed')
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-warning">تغيير حالة الطلب</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.educational-card-orders.update-status', $educationalCardOrder) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            
                            <div class="form-group">
                                <label for="status">الحالة الجديدة</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="pending" {{ $educationalCardOrder->status == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                    <option value="processing" {{ $educationalCardOrder->status == 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                                    <option value="completed" {{ $educationalCardOrder->status == 'completed' ? 'selected' : '' }}>مكتملة</option>
                                    <option value="cancelled" {{ $educationalCardOrder->status == 'cancelled' ? 'selected' : '' }}>ملغاة</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="admin_notes">ملاحظات الأدمن</label>
                                <textarea name="admin_notes" id="admin_notes" class="form-control" rows="3" 
                                          placeholder="ملاحظات إضافية (اختياري)">{{ $educationalCardOrder->admin_notes }}</textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save"></i> حفظ التغييرات
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-secondary">إجراءات أخرى</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="mailto:{{ $educationalCardOrder->user->email }}?subject=طلب البطاقة التعليمية رقم {{ $educationalCardOrder->id }}" 
                           class="btn btn-info btn-block mb-2">
                            <i class="fas fa-envelope"></i> مراسلة المستخدم
                        </a>
                        
                        @if($educationalCardOrder->status !== 'completed')
                            <form action="{{ route('admin.educational-card-orders.destroy', $educationalCardOrder) }}" 
                                  method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطلب؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-block">
                                    <i class="fas fa-trash"></i> حذف الطلب
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- معلومات إضافية -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">معلومات إضافية</h6>
                </div>
                <div class="card-body">
                    <small class="text-muted">
                        <strong>تاريخ الإنشاء:</strong><br>
                        {{ $educationalCardOrder->created_at->format('l, F j, Y \a\t g:i A') }}
                    </small>
                    
                    @if($educationalCardOrder->updated_at != $educationalCardOrder->created_at)
                        <hr>
                        <small class="text-muted">
                            <strong>آخر تحديث:</strong><br>
                            {{ $educationalCardOrder->updated_at->format('l, F j, Y \a\t g:i A') }}
                        </small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection