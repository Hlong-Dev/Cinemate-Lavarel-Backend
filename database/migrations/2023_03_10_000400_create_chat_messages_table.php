<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->text('content')->nullable();
            $table->string('sender');
            $table->foreignId('room_id')->constrained('rooms');
            $table->string('image')->nullable();
            $table->enum('type', ['CHAT', 'JOIN', 'LEAVE', 'IMAGE'])->default('CHAT');
            $table->string('avtUrl')->nullable();
            $table->foreignId('reply_to_id')->nullable()->constrained('chat_messages');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};