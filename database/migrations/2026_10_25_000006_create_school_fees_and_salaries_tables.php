<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ভর্তি ফি, মাসিক বেতন, পরীক্ষা ফি, সেশন ফি
            $table->decimal('default_amount', 10, 2)->default(0.00);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('student_fee_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // INV-2026-0001
            $table->foreignId('student_profile_id')->constrained('student_profiles')->cascadeOnDelete();
            $table->foreignId('fee_type_id')->constrained('fee_types')->cascadeOnDelete();
            $table->string('title');
            $table->decimal('amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0.00);
            $table->date('due_date');
            $table->enum('status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->date('payment_date')->nullable();
            $table->string('payment_method')->nullable(); // Cash, bKash, Nagad, Bank
            $table->string('transaction_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('staff_salary_payments', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_number')->unique(); // SAL-2026-0001
            $table->foreignId('staff_profile_id')->constrained('staff_profiles')->cascadeOnDelete();
            $table->string('month'); // e.g. "জানুয়ারি", "ফেব্রুয়ারি" / "January"
            $table->year('year')->default(2026);
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('bonus', 10, 2)->default(0.00);
            $table->decimal('deductions', 10, 2)->default(0.00);
            $table->decimal('net_salary', 10, 2);
            $table->date('payment_date');
            $table->string('payment_method')->default('Cash');
            $table->enum('status', ['paid', 'pending'])->default('paid');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_salary_payments');
        Schema::dropIfExists('student_fee_invoices');
        Schema::dropIfExists('fee_types');
    }
};
