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
        Schema::create('rfsa_kurse', function (Blueprint $table) {
            $table->id();
            $table->text('notiz')->nullable();
            $table->string('nummer')->unique();
            $table->string('uhrzeit');
            $table->date('tag1');
            $table->date('tag2');
            $table->date('tag3');
            $table->date('tag4');
            $table->date('tag5')->nullable();
            $table->date('tag6')->nullable();
            $table->date('tag7')->nullable();
            $table->date('tag8')->nullable();
            $table->date('ersatztermin1');
            $table->date('ersatztermin2');
            $table->unsignedTinyInteger('kursplätze');
            $table->unsignedTinyInteger('restplätze');
            $table->text('lehrer')->nullable();
            $table->text('co_lehrer')->nullable();
            $table->text('co_lehrer2')->nullable();
            $table->text('hospitant')->nullable();
            $table->text('hospitant2')->nullable();
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
        Schema::dropIfExists('rfsa_kurse');
    }
};
