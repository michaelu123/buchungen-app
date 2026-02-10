<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('technik_kurse', function (Blueprint $table) {
            $table->id();
            $table->string('nummer')->unique();
            $table->text('notiz')->nullable();
            $table->text('titel');
            $table->date("datum");
            $table->unsignedTinyInteger('kursplätze');
            $table->unsignedTinyInteger('restplätze');
            $table->text('leiter');
            $table->text('leiter2')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technik_kurse');
    }
};
