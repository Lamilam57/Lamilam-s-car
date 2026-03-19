<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id');
            $table->foreignId('owner_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();

            $table->unique(['car_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('car_views');
    }
};
