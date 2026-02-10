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
        Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('sku')->unique(); // PRD-XXXXX
    $table->string('name');
    $table->text('description')->nullable();
    $table->string('category'); // Category name
    $table->string('subcate')->nullable(); // Subcategory name
    $table->json('tags')->nullable(); // Array of tag IDs or names
    $table->json('images')->nullable(); // Array of image paths
    $table->string('pdf')->nullable(); // PDF file path
    $table->decimal('price', 10, 2)->nullable();
    $table->integer('stock')->default(0);
    $table->string('status')->default('actif'); // actif, inactif
    $table->timestamps();
});

// Optional: product_tag pivot table for many-to-many relationship
Schema::create('product_tag', function (Blueprint $table) {
    $table->foreignId('product_id')->constrained()->onDelete('cascade');
    $table->foreignId('tag_id')->constrained()->onDelete('cascade');
    $table->primary(['product_id', 'tag_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
