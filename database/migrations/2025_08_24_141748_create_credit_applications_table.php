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
        Schema::create('credit_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');

            $table->date('application_date');
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected', 'canceled'])->default('draft');

            $table->decimal('dp_amount', 15, 2);
            $table->decimal('loan_amount', 15, 2);
            $table->integer('tenor_months');
            $table->decimal('interest_rate', 5, 2);
            $table->decimal('monthly_installment', 15, 2)->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_applications');
    }
};
