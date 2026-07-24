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
        Schema::create('resume_analyses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('resume_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('score')->nullable();

            $table->text('summary')->nullable();

            $table->json('strengths')->nullable();

            $table->json('weaknesses')->nullable();

            $table->json('missing_skills')->nullable();

            $table->json('analysis')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resume_analyses');
    }
};
