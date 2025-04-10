<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('knowledges_languages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('knowledge_id')->constrained('knowledges')->onDelete('cascade');
            $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knowledges_languages');
    }
};
