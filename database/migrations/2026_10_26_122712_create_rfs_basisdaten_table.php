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
        Schema::create('rfs_basisdaten', function (Blueprint $table) {
            $table->id();
            $table->boolean("rfsa_warteliste")->nonnull(); // Anfänger
            $table->string("text_rfsa")->nonnull();
            $table->boolean("rfsfg_warteliste")->nonnull(); // Fahrsicherheit Grundkurs
            $table->string("text_rfsfg")->nonnull();
            $table->boolean("rfsfa_Warteliste")->nonnull(); // Fahrsicherheit Aufbaukurs
            $table->string("text_rfsfa")->nonnull();
            $table->boolean("rfsfs_Warteliste")->nonnull(); // Fahrsicherheit Seniorkurs
            $table->string("text_rfsfs")->nonnull();
            $table->boolean("rfsfp_Warteliste")->nonnull(); // Fahrpraxis 
            $table->string("text_rfsfp")->nonnull();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rfs_basisdaten');
    }
};
