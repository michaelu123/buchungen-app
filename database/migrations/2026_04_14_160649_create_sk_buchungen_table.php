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
        Schema::create('sk_buchungen', function (Blueprint $table) {
            $table->id();
            $table->text('notiz')->nullable();
            $table->string("email");
            $table->string("mitgliedsname");
            $table->unsignedInteger("mitgliedsnummer");
            $table->integer("sknummer")->nullable();
            $table->string("kontoinhaber");
            $table->string("iban");
            $table->boolean("lastschriftok");
            $table->datetime("verified")->nullable();
            $table->datetime("gesendet")->nullable();
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
        Schema::dropIfExists('sk_buchungen');
    }
};
