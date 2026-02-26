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
        Schema::create('rfsfp_buchungen', function (Blueprint $table) {
            $table->id();
            $table->text('notiz')->nullable();
            $table->string("email");
            $table->unsignedInteger("mitgliedsnummer")->nullable();
            // aktuell kursnummer von Hand von bigint auf varchar ändern!
            // $table->foreignId("kursnummer")->constrained("rfsfp_kurse", "nummer")->onDelete("cascade");
            $table->string("kursnummer")->constrained("rfsfp_kurse", "nummer")->onDelete("cascade");
            $table->string("anrede")->nullable();
            $table->string("vorname");
            $table->string("nachname");
            $table->integer("postleitzahl");
            $table->string("ort");
            $table->string("strasse_nr");
            $table->string("telefonnr");
            $table->string("kontoinhaber");
            $table->string("iban");
            $table->boolean("lastschriftok");
            $table->datetime("verified")->nullable();
            $table->datetime("eingezogen")->nullable();
            $table->unsignedInteger("betrag")->nullable();
            $table->text("kommentar")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rfsfp_buchungen');
    }
};
