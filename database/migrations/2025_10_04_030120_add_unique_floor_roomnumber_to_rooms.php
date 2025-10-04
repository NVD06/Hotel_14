<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            // Nếu muốn unique theo floor + room_number:
            $table->unique(['floor', 'room_number'], 'uniq_floor_roomnumber');

            // Nếu muốn unique theo floor + room_number + room_type_id thì dùng dòng dưới thay cho dòng trên:
            // $table->unique(['floor','room_number','room_type_id'], 'uniq_floor_roomnumber_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropUnique('uniq_floor_roomnumber');
            // hoặc: $table->dropUnique('uniq_floor_roomnumber_type');
        });
    }
};
