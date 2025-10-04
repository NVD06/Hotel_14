<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) Thêm cột (nullable để không vỡ dữ liệu cũ)
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->after('email');
            } else {
                // nếu cột đã tồn tại nhưng là NOT NULL, chuyển về NULL
                $table->string('phone', 20)->nullable()->change();
            }
        });

        // 2) Chuyển các giá trị rỗng thành NULL để tránh đụng unique
        DB::statement("UPDATE users SET phone = NULL WHERE phone = '' OR phone IS NULL");

        // 3) Thêm chỉ mục unique
        Schema::table('users', function (Blueprint $table) {
            // tránh lỗi nếu đã có
            $table->unique('phone', 'users_phone_unique');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // rollback: bỏ unique rồi drop cột
            $table->dropUnique('users_phone_unique');
            $table->dropColumn('phone');
        });
    }
};
