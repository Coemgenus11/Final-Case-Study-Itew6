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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('patient_id');
            $table->timestamp('appointment_date');
            $table->enum('status', ['booked', 'approved', 'completed', 'canceled']);
            $table->timestamps();

            // Setting up foreign key constraints
            $table->foreign('doctor_id')->references('user_id')->on('doctors')->onDelete('cascade');
            $table->foreign('patient_id')->references('user_id')->on('patients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
