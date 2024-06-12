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
        Schema::create('medical_records', function (Blueprint $table) {
            // Primary key
            $table->id('id');
            
            // Foreign keys
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');

            // Other columns
            $table->timestamp('record_date');
            $table->string('description');
            
            // Timestamps
            $table->timestamps(); // This adds both created_at and updated_at columns

            // Foreign key constraints
            $table->foreign('patient_id')->references('user_id')->on('patients')->onDelete('cascade');
            $table->foreign('doctor_id')->references('user_id')->on('doctors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
