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
        Schema::create('forecasts', function (Blueprint $table) {
            $table->id('id');
            $table->decimal('temperature', 5, 2);
            $table->string('description');
            $table->string('icon');
            $table->unsignedBigInteger('location_id');
            $table->dateTime('condition_at');

            $table->foreign('location_id')
                ->references('id')
                ->on('locations')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forecasts');
    }
};
