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
        Schema::create('doctor_availabilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id'); // seller id
            $table->date('available_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->text('interval')->default(10);
            $table->text('other')->nullable();
            $table->timestamps();

            $table->foreign('doctor_id')->references('id')->on('sellers')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_availabilities');
    }
};
