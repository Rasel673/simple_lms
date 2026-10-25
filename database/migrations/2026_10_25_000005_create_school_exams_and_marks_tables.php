<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_exams', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. ১ম সাময়িক পরীক্ষা ২০২৬, বার্ষিক পরীক্ষা ২০২৬
            $table->year('session_year')->default(2026);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });

        Schema::create('grade_rules', function (Blueprint $table) {
            $table->id();
            $table->string('grade_name'); // A+, A, A-, B, C, D, F
            $table->decimal('min_mark', 5, 2); // 80.00
            $table->decimal('max_mark', 5, 2); // 100.00
            $table->decimal('grade_point', 3, 2); // 5.00
            $table->string('remarks')->nullable(); // Excellent, Very Good, Failed...
            $table->timestamps();
        });

        Schema::create('student_marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_exam_id')->constrained('school_exams')->cascadeOnDelete();
            $table->foreignId('student_profile_id')->constrained('student_profiles')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->decimal('marks_obtained', 6, 2)->default(0.00);
            $table->string('grade')->nullable(); // A+, A...
            $table->decimal('grade_point', 3, 2)->default(0.00);
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['school_exam_id', 'student_profile_id', 'subject_id'], 'exam_student_subject_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_marks');
        Schema::dropIfExists('grade_rules');
        Schema::dropIfExists('school_exams');
    }
};
