<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->string('password', 250);
            $table->string('email', 50)->unique();
            $table->string('phone', 10)->nullable()->unique();
            $table->string('address', 100)->nullable();
            $table->string('provider', 50)->nullable();
            $table->string('provider_id', 100)->nullable();
            $table->string('avt_url')->default('https://i.imgur.com/Tr9qnkI.jpeg');
            $table->boolean('account_non_locked')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};