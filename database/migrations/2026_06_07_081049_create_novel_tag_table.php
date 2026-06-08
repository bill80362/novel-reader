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
        Schema::create('novel_tag', function (Blueprint $table) {
            $table->bigInteger('novel_id')->constrained()->cascadeOnDelete();
            $table->bigInteger('tag_id')->constrained()->cascadeOnDelete();
            $table->primary(['novel_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('novel_tag');
    }
};
