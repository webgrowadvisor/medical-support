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
        Schema::create('libraries', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('short_description')->nullable();
            $table->longText('full_content')->nullable();

            $table->enum('type', ['free', 'paid'])->default('free');

            $table->string('cover_image')->nullable();
            $table->string('file_url')->nullable();

            $table->string('other')->nullable();
            $table->boolean('status')->default(true); // true = active
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('librarys');
    }
};
