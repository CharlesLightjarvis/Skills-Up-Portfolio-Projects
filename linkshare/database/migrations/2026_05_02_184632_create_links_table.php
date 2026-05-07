<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained()->cascadeOnDelete();
            $table->string('url');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->string('site_name')->nullable();
            $table->string('domain')->nullable();
            $table->text('note')->nullable();
            $table->string('status')->default('active');
            $table->boolean('is_favorite')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
