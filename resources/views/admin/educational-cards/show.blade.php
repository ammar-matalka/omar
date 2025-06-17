@extends('layouts.admin')

@section('title', 'عرض البطاقة التعليمية #' . $id)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">عرض البطاقة التعليمية #{{ $id }}</h1>
        <div>
            <a href="{{ route('admin.educational-cards.edit', $id) }}" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-edit"></i>
                </span>
                <span class="text">تعديل</span>
            </a>
            <a href="{{ route('admin.educational-cards.index') }}" class="btn btn-secondary btn-icon-split">
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
        <!-- معلومات البطاقة -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">معلومات البطاقة التعليمية</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ID:</strong></td>
                                    <td>{{ $id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>العنوان:</strong></td>
                                    <td>بطاقة تعليمية نموذجية</td>
                                </tr>
                                <tr>
                                    <td><strong>الفئة:</strong></td>
                                    <td>
                                        <span class="badge badge-primary">الرياضيات</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>المستوى الدراسي:</strong></td>
                                    <td>الصف 5</td>
                                </tr>
                                <tr>
                                    <td><strong>مستوى الصعوبة:</strong></td>
                                    <td>
                                        <span class="badge badge-warning">متوسط</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>الحالة:</strong></td>
                                    <td>
                                        <span class="badge badge-success">فعال</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>تاريخ الإنشاء:</strong></td>
                                    <td>{{ now()->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>آخر تحديث:</strong></td>
                                    <td>{{ now()->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <img src="https://via.placeholder.com/200x150/007bff/ffffff?text=البطاقة+التعليمية" 
                                     class="img-fluid rounded shadow" alt="صورة البطاقة">
                                <p class="mt-2 text-muted">صورة البطاقة التعليمية</p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <h6><strong>الوصف:</strong></h6>
                            <div class="alert alert-light">
                                هذه بطاقة تعليمية نموذجية تحتوي على محتوى تعليمي مفيد للطلاب في مادة الرياضيات للصف الخامس. 
                                تتضمن البطاقة تمارين متنوعة ومناسبة لمستوى الطلاب.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- محتوى البطاقة -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">محتوى البطاقة التعليمية</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="fas fa-question-circle"></i> سؤال 1</h6>
                                </div>
                                <div class="card-body">
                                    <p>ما هو ناتج جمع 25 + 37؟</p>
                                    <div class="mt-3">
                                        <button class="btn btn-outline-primary btn-sm">أ) 52</button>
                                        <button class="btn btn-success btn-sm">ب) 62</button>
                                        <button class="btn btn-outline-primary btn-sm">ج) 72</button>
                                        <button class="btn btn-outline-primary btn-sm">د) 82</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="fas fa-lightbulb"></i> نصيحة</h6>
                                </div>
                                <div class="card-body">
                                    <p>عند جمع الأرقام، ابدأ بجمع الآحاد ثم العشرات:</p>
                                    <p>7 + 5 = 12 (اكتب 2 واحمل 1)</p>
                                    <p>2 + 3 + 1 = 6</p>
                                    <p>النتيجة: 62</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- إحصائيات وإجراءات -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">إحصائيات البطاقة</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">مرات المشاهدة</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">125</div>
                    </div>
                    <hr>
                    <div class="text-center mb-3">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">مرات التحميل</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">89</div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">التقييم</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="far fa-star text-warning"></i>
                            (4.2/5)
                        </div>
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
                        <button type="button" class="btn btn-success btn-block mb-2">
                            <i class="fas fa-check"></i> تفعيل البطاقة
                        </button>
                        
                        <button type="button" class="btn btn-info btn-block mb-2">
                            <i class="fas fa-download"></i> تحميل البطاقة
                        </button>
                        
                        <button type="button" class="btn btn-warning btn-block mb-2">
                            <i class="fas fa-copy"></i> نسخ البطاقة
                        </button>
                        
                        <form action="{{ route('admin.educational-cards.destroy', $id) }}" 
                              method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه البطاقة؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fas fa-trash"></i> حذف البطاقة
                            </button>
                        </form>
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
                        <strong>المنشئ:</strong> المشرف العام<br>
                        <strong>حجم الملف:</strong> 1.2 MB<br>
                        <strong>تاريخ آخر تعديل:</strong> {{ now()->format('Y-m-d') }}<br>
                        <strong>عدد الأسئلة:</strong> 10 أسئلة<br>
                        <strong>الوقت المقدر:</strong> 15 دقيقة
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection