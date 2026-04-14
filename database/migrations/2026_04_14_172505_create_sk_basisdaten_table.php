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
        // this table has only one record!
        Schema::create('sk_basisdaten', function (Blueprint $table) {
            $table->id();
            $table->integer('jahr')->nonnull();
            $table->integer('betrag')->nonnull();
            $table->boolean('offen')->nonnull();
            $table->integer('sknummer')->nonnull();
            $table->string('gueltigab')->nonnull();
            $table->string('gueltigbis')->nonnull();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sk_basisdaten');
    }
};
