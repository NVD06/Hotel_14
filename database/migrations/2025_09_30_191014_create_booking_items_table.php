<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('booking_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnUpdate()->restrictOnDelete();
            $table->decimal('rate', 10, 2);
            $table->unsignedInteger('nights');
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            $table->unique(['booking_id','room_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('booking_items');
    }
};
