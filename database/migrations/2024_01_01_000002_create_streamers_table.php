<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('streamers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('douyin_url');
            $table->string('douyin_id')->nullable();
            $table->boolean('is_monitoring')->default(true);
            $table->boolean('is_live')->default(false);
            $table->timestamp('last_live_time')->nullable();
            $table->timestamp('last_check_time')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('streamers');
    }
};