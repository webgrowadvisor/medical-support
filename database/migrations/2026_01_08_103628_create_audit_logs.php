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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            $table->string('actor_type'); 
            // user | doctor | admin

            $table->unsignedBigInteger('actor_id')->nullable();

            $table->string('event'); 
            // login | logout | signup

            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();

            $table->json('meta')->nullable(); 
            // extra data (email, mobile etc)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
