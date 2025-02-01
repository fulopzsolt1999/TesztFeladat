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
        Schema::connection('mysql_carservice')->create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->unsignedInteger('car_id');
            $table->foreign('car_id')->references('car_id')->on('cars')->onDelete('cascade');
            $table->string('log_number');
            $table->string('event');
            $table->dateTime('event_time')->date_format('Y-m-d H:i:s')->nullable();
            $table->string('document_id');
            $table->unique(['client_id', 'car_id', 'log_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_carservice')->dropIfExists('services');
    }
};
