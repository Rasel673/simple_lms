<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->enum('attendance_type', ['student', 'staff']);
            $table->foreignId('student_profile_id')->nullable()->constrained('student_profiles')->cascadeOnDelete();
            $table->foreignId('staff_profile_id')->nullable()->constrained('staff_profiles')->cascadeOnDelete();
            $table->date('date');
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->enum('status', ['present', 'late', 'absent', 'half_day'])->default('present');
            $table->string('source')->default('biometric'); // biometric, manual
            $table->string('device_id')->nullable(); // e.g. FP-DEVICE-01
            $table->string('remarks')->nullable();
            $table->timestamps();

            $table->index(['attendance_type', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
