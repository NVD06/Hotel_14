<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_number')->unique();
            $table->foreignId('room_type_id')->constrained('room_types')->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedTinyInteger('floor')->nullable();
            $table->enum('status', ['available','occupied','cleaning','maintenance'])->default('available');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['room_type_id','status']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('rooms');
    }
};
