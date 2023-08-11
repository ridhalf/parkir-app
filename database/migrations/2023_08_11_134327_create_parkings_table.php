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
        Schema::create('parkings', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('category_id');
            $table->string('parking_code', 8);
            $table->date('date_in');
            $table->date('date_out')->nullable(true);
            $table->time('check_in');
            $table->time('check_out')->nullable(true);
            $table->enum('status', ['IN', 'OUT']);
            $table->integer('total_payment')->nullable(true);
            $table->tinyInteger('duration')->nullable(true);
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parkings');
    }
};
