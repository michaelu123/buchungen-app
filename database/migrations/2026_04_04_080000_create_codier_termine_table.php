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
        Schema::create('codier_termine', function (Blueprint $table) {
            $table->id();
            $table->text('notiz')->nullable();
            $table->date('datum');
            $table->time('beginn');
            $table->time('ende');
            $table->text("kommentar")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codier_termine');
    }
};
