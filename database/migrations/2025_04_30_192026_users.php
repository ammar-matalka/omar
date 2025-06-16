<?php
// استبدل محتوى ملف 2025_04_30_192026_users.php بهذا:

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // تحقق من وجود العمود قبل إضافته
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('customer');
            }
            
            // أضف الأعمدة الأخرى المطلوبة للنظام التعليمي
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'profile_image')) {
                $table->string('profile_image')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // احذف الأعمدة فقط إذا كانت موجودة
            $columns = ['role', 'phone', 'address', 'profile_image'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};