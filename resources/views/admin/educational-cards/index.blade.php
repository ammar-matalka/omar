@extends('layouts.admin')

@section('title', 'إدارة البطاقات التعليمية')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">إدارة البطاقات التعليمية</h1>
        <a href="{{ route('admin.educational-cards.create') }}" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">إضافة بطاقة جديدة</span>
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">قائمة البطاقات التعليمية</h6>
        </div>
        <div class="card-body">
            <div class="text-center">
                <i class="fas fa-graduation-cap fa-3x text-gray-300 mb-3"></i>
                <h4>البطاقات التعليمية</h4>
                <p class="text-muted">هذا القسم قيد التطوير...</p>
                <p class="text-info">يمكن إدارة البطاقات التعليمية من هنا في المستقبل</p>
            </div>
        </div>
    </div>
</div>
@endsection