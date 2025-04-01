<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('current_video_url')->nullable();
            $table->string('current_video_title')->nullable();
            $table->string('thumbnail')->default('https://i.imgur.com/Tr9qnkI.jpeg');
            $table->string('owner_username');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};