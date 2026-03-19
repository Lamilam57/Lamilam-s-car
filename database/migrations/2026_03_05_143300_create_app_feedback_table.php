<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('app_feedback', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->unsignedTinyInteger('rating')->nullable(); 
            // 1-5 stars

            $table->enum('type', [
                'review',
                'complaint',
                'suggestion',
                'bug',
                'general'
            ])->default('general');

            $table->string('subject')->nullable();

            $table->text('message');

            $table->enum('status', [
                'pending',
                'reviewed',
                'resolved',
                'rejected'
            ])->default('pending');

            $table->timestamps();
            $table->string('ip_address')->nullable();
            $table->index(['type','status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('app_feedback');
    }
};