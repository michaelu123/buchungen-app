<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rfsfp_kurse', function (Blueprint $table) {
            $table->id();
            $table->text('notiz')->nullable();
            $table->string('nummer')->unique();
            $table->date('datum');
            $table->date('ersatztermin');
            $table->string('uhrzeit');
            // $table->string('kursort');
            $table->unsignedTinyInteger('kursplätze');
            $table->unsignedTinyInteger('restplätze');
            $table->text('trainer')->nullable();
            $table->text('co_trainer')->nullable();
            $table->text('hospitant')->nullable();
            $table->text('liste_verschicken')->nullable();
            $table->text('abgesagt_am')->nullable();
            $table->text('abgesagt_wg')->nullable();
            $table->text('status')->nullable();
            $table->text("kommentar")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rfsfp_kurse');
    }
};
