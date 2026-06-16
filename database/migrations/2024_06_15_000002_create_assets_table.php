<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_category_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('svg_content'); // Store raw SVG string
            $table->text('keywords')->nullable(); // For search optimization
            $table->timestamps();
        });
    }
};