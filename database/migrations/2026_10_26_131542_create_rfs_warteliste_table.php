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
        Schema::create('rfs_warteliste', function (Blueprint $table) {
            $table->id();
            $table->string('kursart');
            $table->string('email');
            $table->string('anrede')->nullable();
            $table->string('vorname');
            $table->string('nachname');
            $table->string('telefonnr');
            $table->text('mitteilung')->nullable();
            $table->text('kommentar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rfs_warteliste');
    }
};
