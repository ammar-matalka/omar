<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    // يسمح بعملية mass assignment على الحقول المحددة هنا
    protected $fillable = ['name'];

    // إذا كانت هناك خصائص أخرى، اتركها كما هي
}
