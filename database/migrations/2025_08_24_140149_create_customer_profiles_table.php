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
        Schema::create('customer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('monthly_income')->nullable();
            $table->string('occupation')->nullable();
            $table->string('company_name');
            $table->string('nik')->nullable();
            $table->string('ktp_file')->nullable();
            $table->string('kk_file')->nullable();
            $table->string('npwp_number')->nullable();
            $table->string('npwp_file')->nullable();
            $table->string('slip_gaji_file')->nullable();
            $table->string('rekening_tabungan_file')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_profiles');
    }
};
