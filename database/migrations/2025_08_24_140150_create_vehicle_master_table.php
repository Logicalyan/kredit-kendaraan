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
        // Brands
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Vehicle Types
        Schema::create('vehicle_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Vehicle Types
        Schema::create('vehicle_body_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->constrained('vehicle_types')->cascadeOnDelete();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Vehicle Models
        Schema::create('vehicle_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained('brands')->cascadeOnDelete();
            $table->string('name'); // Avanza, Vario, Colt Diesel, dsb
            $table->foreignId('body_type_id')->constrained('vehicle_body_types')->cascadeOnDelete();
            $table->timestamps();
        });

        // Vehicle Variants
        Schema::create('vehicle_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('model_id')->constrained('vehicle_models')->cascadeOnDelete();
            $table->string('name'); // misal Avanza G, Veloz, Vario 125 CBS
            $table->string('transmission');
            $table->integer('engine_cc');
            $table->enum('fuel_type', ['gasoline', 'diesel', 'hybrid', 'electric'])->default('gasoline');
            $table->integer('otr_price');
            $table->year('model_year');
            $table->timestamps();
        });

        // Vehicle Units
        Schema::create('vehicle_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variant_id')->constrained('vehicle_variants')->cascadeOnDelete();
            $table->string('vin_number')->unique(); // Nomor rangka
            $table->string('engine_number')->unique(); // Nomor mesin
            $table->string('license_plate')->unique()->nullable(); // Plat nomor
            $table->string('production_code');
            $table->string('color');
            $table->enum('status', ['available', 'in_use', 'sold'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_units');
        Schema::dropIfExists('vehicle_variants');
        Schema::dropIfExists('vehicle_models');
        Schema::dropIfExists('vehicle_body_types');
        Schema::dropIfExists('vehicle_types');
        Schema::dropIfExists('brands');
    }
};
