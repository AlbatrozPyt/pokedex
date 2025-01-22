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
        Schema::create('pokemon', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('base');
            $table->string('species');
            $table->string('description');
            $table->string('evolution_prev');
            $table->string('evolution_next');
            $table->string('profile');
            $table->string('egg');
            $table->string('ability');
            $table->string('gender');
            $table->string('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pokemon');
    }
};
