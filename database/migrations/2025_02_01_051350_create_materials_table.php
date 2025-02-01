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
            $table->id('material_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->cascadeOnDelete()->where('role', 'tutor');
            $table->boolean('visible_to_all')->default(false);
            $table->foreignId('section_id')->nullable()->constrained('sections', 'section_id')->cascadeOnDelete();
            $table->string('message');
            $table->string('image')->nullable();
            $table->string('video')->nullable();
            $table->string('file_path')->nullable();
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
