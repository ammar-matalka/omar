@extends('layouts.admin')

@section('title', 'إدارة المواد التعليمية')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">إدارة المواد التعليمية</h1>
        <a href="{{ route('admin.educational-subjects.create') }}" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">إضافة مادة جديدة</span>
        </a>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.educational-subjects.index') }}">
                <div class="row">
                    <div class="col-md-3">
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
                        <label for="status">الحالة</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">جميع الحالات</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>فعال</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير فعال</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="search">البحث</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               value="{{ request('search') }}" placeholder="البحث في أسماء المواد">
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> بحث
                            </button>
                            <a href="{{ route('admin.educational-subjects.index') }}" class="btn btn-secondary">
                                <i class="fas fa-refresh"></i>
                            </a>
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">قائمة المواد التعليمية</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>اسم المادة</th>
                            <th>الجيل</th>
                            <th>السعر</th>
                            <th>الحالة</th>
                            <th>الترتيب</th>
                            <th>تاريخ الإنشاء</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects as $subject)
                            <tr>
                                <td>{{ $subject->id }}</td>
                                <td>{{ $subject->name }}</td>
                                <td>
                                    <span class="badge badge-secondary">
                                        {{ $subject->generation->display_name }}
                                    </span>
                                </td>
                                <td>{{ $subject->formatted_price }}</td>
                                <td>
                                    @if($subject->is_active)
                                        <span class="badge badge-success">فعال</span>
                                    @else
                                        <span class="badge badge-danger">غير فعال</span>
                                    @endif
                                </td>
                                <td>{{ $subject->order }}</td>
                                <td>{{ $subject->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.educational-subjects.show', $subject) }}" 
                                           class="btn btn-info btn-sm" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.educational-subjects.edit', $subject) }}" 
                                           class="btn btn-warning btn-sm" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.educational-subjects.toggle-status', $subject) }}" 
                                              method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-{{ $subject->is_active ? 'secondary' : 'success' }} btn-sm"
                                                    title="{{ $subject->is_active ? 'إلغاء تفعيل' : 'تفعيل' }}">
                                                <i class="fas fa-{{ $subject->is_active ? 'ban' : 'check' }}"></i>
                                            </button>
                                        </form>
                                        @if($subject->orderItems()->count() == 0)
                                            <form action="{{ route('admin.educational-subjects.destroy', $subject) }}" 
                                                  method="POST" style="display: inline;"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذه المادة؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">لا توجد مواد</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($subjects->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $subjects->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection