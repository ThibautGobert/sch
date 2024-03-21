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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('active')->default(0);
            $table->unsignedInteger('order')->nullable();
            $table->foreignId('category_id')->nullable()->references('id')->on('categories')->onDelete('set null');
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->longText('intro')->nullable();
            $table->text('image')->nullable();
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->references('id')->on('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
