<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dossier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'generation_id',
        'subject_id',
        'teacher_id',
        'platform_id',
        'semester',
        'price',
        'description',
        'pages_count',
        'file_size',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    /**
     * Scope للدوسيات النشطة
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope للترتيب
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('name', 'asc');
    }

    /**
     * Scope للجيل
     */
    public function scopeForGeneration($query, $generationId)
    {
        return $query->where('generation_id', $generationId);
    }

    /**
     * Scope للمادة
     */
    public function scopeForSubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    /**
     * Scope للمعلم
     */
    public function scopeForTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    /**
     * Scope للمنصة
     */
    public function scopeForPlatform($query, $platformId)
    {
        return $query->where('platform_id', $platformId);
    }

    /**
     * Scope للفصل
     */
    public function scopeForSemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }

    /**
     * العلاقات
     */
    public function generation()
    {
        return $this->belongsTo(EducationalGeneration::class);
    }

    public function subject()
    {
        return $this->belongsTo(EducationalSubject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function orderItems()
    {
        return $this->hasMany(EducationalOrderItem::class);
    }

    /**
     * Accessors
     */
    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'نشط' : 'غير نشط';
    }

    public function getStatusColorAttribute()
    {
        return $this->is_active ? 'success' : 'danger';
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2) . ' JD';
    }

    public function getSemesterTextAttribute()
    {
        return match($this->semester) {
            'first' => 'الفصل الأول',
            'second' => 'الفصل الثاني',
            'both' => 'كلا الفصلين',
            default => $this->semester
        };
    }

    public function getDisplayNameAttribute()
    {
        return $this->name . ' - ' . $this->teacher->name . ' (' . $this->platform->name . ')';
    }

    /**
     * حساب السعر النهائي مع نسبة المنصة
     */
    public function getFinalPriceAttribute()
    {
        return $this->platform->calculatePrice($this->price);
    }

    public function getFormattedFinalPriceAttribute()
    {
        return number_format($this->final_price, 2) . ' JD';
    }
}