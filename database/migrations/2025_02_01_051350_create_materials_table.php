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
        Schema::create('materials', function (Blueprint $table) {
            $table->id(); // Changed from $table->id('material_id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->where('role', 'tutor');
            $table->boolean('visible_to_all')->default(false);
            $table->enum('category', ['primary', 'lower_secondary', 'upper_secondary'])->nullable();
            $table->foreignId('section_id')->nullable()->constrained('sections')->cascadeOnDelete();
            $table->string('message');
            $table->string('image')->nullable();
            $table->string('video')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('tag', ['past-year', 'assignment', 'quiz', 'exam', 'notes', 'announcement']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
