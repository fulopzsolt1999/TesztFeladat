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
        Schema::connection('mysql_carservice')->create('cars', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->unsignedInteger('car_id');
            $table->string('type');
            $table->dateTime('registered')->date_format('Y-m-d H:i:s');
            $table->string('ownbrand');
            $table->string('accidents');
            $table->unique(['client_id', 'car_id']);
            $table->index('car_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_carservice')->dropIfExists('cars');
    }
};
