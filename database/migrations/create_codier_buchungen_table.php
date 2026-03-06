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
        Schema::create('codier_buchungen', function (Blueprint $table) {
            $table->id();
            $table->text('notiz')->nullable();
            $table->foreignId('termin_id')->constrained("codier_termine")->onDelete("cascade");
            $table->time('uhrzeit');
            $table->string("anrede")->nullable();
            $table->string("vorname");
            $table->string("nachname");
            $table->integer("postleitzahl");
            $table->string("ort");
            $table->string("strasse_nr");
            $table->string("telefonnr");
            $table->string("email");
            $table->unsignedInteger("mitgliedsnummer")->nullable();
            $table->datetime("anmeldebestätigung")->nullable();
            $table->text("kommentar")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codier_buchungen');
    }
};
