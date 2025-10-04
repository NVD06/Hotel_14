<?php
use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            // 1) Bỏ UNIQUE cũ chỉ cho room_number
            // Tên index đúng như lỗi: rooms_room_number_unique
            $table->dropUnique('rooms_room_number_unique');

            // 2) Tạo UNIQUE mới theo bộ bạn muốn
            // a) nếu muốn duy nhất theo (room_number, floor):
            // $table->unique(['room_number','floor'], 'rooms_unique_room_floor');

            // b) nếu muốn duy nhất theo (room_number, floor, room_type_id):
            $table->unique(['room_number','floor','room_type_id'], 'rooms_unique_room_floor_type');
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            // rollback: bỏ unique mới, trả unique cũ
            // $table->dropUnique('rooms_unique_room_floor'); // nếu dùng phương án (a)
            $table->dropUnique('rooms_unique_room_floor_type'); // nếu dùng phương án (b)

            $table->unique('room_number', 'rooms_room_number_unique');
        });
    }
};
