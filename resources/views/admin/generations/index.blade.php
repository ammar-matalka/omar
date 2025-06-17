@extends('layouts.admin')

@section('title', 'إدارة الأجيال')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">إدارة الأجيال</h1>
        <a href="{{ route('admin.generations.create') }}" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">إضافة جيل جديد</span>
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
            <h6 class="m-0 font-weight-bold text-primary">قائمة الأجيال</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>الاسم</th>
                            <th>السنة</th>
                            <th>الوصف</th>
                            <th>عدد المواد</th>
                            <th>عدد الطلبات</th>
                            <th>الحالة</th>
                            <th>الترتيب</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($generations as $generation)
                            <tr>
                                <td>{{ $generation->id }}</td>
                                <td>{{ $generation->name }}</td>
                                <td>{{ $generation->year }}</td>
                                <td>{{ $generation->description ? Str::limit($generation->description, 50) : 'لا يوجد وصف' }}</td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $generation->subjects_count ?? 0 }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">
                                        {{ $generation->orders_count ?? 0 }}
                                    </span>
                                </td>
                                <td>
                                    @if($generation->is_active)
                                        <span class="badge badge-success">فعال</span>
                                    @else
                                        <span class="badge badge-danger">غير فعال</span>
                                    @endif
                                </td>
                                <td>{{ $generation->order }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.generations.show', $generation) }}" 
                                           class="btn btn-info btn-sm" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.generations.edit', $generation) }}" 
                                           class="btn btn-warning btn-sm" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.generations.toggle-status', $generation) }}" 
                                              method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-{{ $generation->is_active ? 'secondary' : 'success' }} btn-sm"
                                                    title="{{ $generation->is_active ? 'إلغاء تفعيل' : 'تفعيل' }}">
                                                <i class="fas fa-{{ $generation->is_active ? 'ban' : 'check' }}"></i>
                                            </button>
                                        </form>
                                        @if($generation->orders_count == 0)
                                            <form action="{{ route('admin.generations.destroy', $generation) }}" 
                                                  method="POST" style="display: inline;"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا الجيل؟')">
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
                                <td colspan="9" class="text-center">لا توجد أجيال</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($generations->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $generations->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection