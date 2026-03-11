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
        Schema::create('commistion_plans', function (Blueprint $table) {
            $table->id();

            $table->string('specialization')->nullable(); // cardiologist, dentist
            $table->unsignedBigInteger('doctor_id')->nullable(); // specific doctor

            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('commission_value', 8, 2); // 10 (%) OR 50 (₹)

            $table->boolean('status')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commistion_plans');
    }
};
