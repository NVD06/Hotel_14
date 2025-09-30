<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('customer_id')
                  ->constrained('customers')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->enum('status', ['pending','confirmed','checked_in','checked_out','cancelled','no_show'])->default('pending');
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['customer_id','status']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('bookings');
    }
};
