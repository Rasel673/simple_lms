<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. ষষ্ঠ শ্রেণি (Class 6)
            $table->integer('numeric_value')->nullable(); // 6
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_class_id')->constrained('academic_classes')->cascadeOnDelete();
            $table->string('name'); // e.g. ক শাখা (Section A)
            $table->integer('capacity')->default(50);
            $table->timestamps();
        });

        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_class_id')->constrained('academic_classes')->cascadeOnDelete();
            $table->string('name'); // e.g. বাংলা ১ম পত্র
            $table->string('code')->nullable(); // e.g. BAN101
            $table->decimal('total_marks', 8, 2)->default(100.00);
            $table->decimal('pass_marks', 8, 2)->default(33.00);
            $table->timestamps();
        });

        Schema::create('designations', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. প্রধান শিক্ষক, সহকারী শিক্ষক
            $table->string('role_type')->default('teacher'); // teacher, staff
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('staff_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('employee_id')->unique(); // EMP-2026-001
            $table->string('name');
            $table->foreignId('designation_id')->constrained('designations')->cascadeOnDelete();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('qualification')->nullable();
            $table->date('joining_date')->nullable();
            $table->decimal('monthly_salary', 10, 2)->default(0.00);
            $table->string('status')->default('active'); // active, inactive
            $table->string('avatar')->nullable();
            $table->timestamps();
        });

        Schema::create('teacher_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_profile_id')->constrained('staff_profiles')->cascadeOnDelete();
            $table->foreignId('academic_class_id')->constrained('academic_classes')->cascadeOnDelete();
            $table->foreignId('section_id')->nullable()->constrained('sections')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_assignments');
        Schema::dropIfExists('staff_profiles');
        Schema::dropIfExists('designations');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('sections');
        Schema::dropIfExists('academic_classes');
    }
};
