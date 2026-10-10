<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('categories', function (Blueprint $t) {
            $t->id(); $t->string('name')->unique(); $t->string('slug')->unique(); $t->text('description')->nullable(); $t->timestamps();
        });
        Schema::create('products', function (Blueprint $t) {
            $t->id(); $t->foreignId('category_id')->constrained()->restrictOnDelete();
            $t->string('name'); $t->string('slug')->unique(); $t->text('description')->nullable();
            $t->string('image_path')->nullable(); $t->boolean('is_active')->default(true); $t->timestamps();
        });
        Schema::create('product_variants', function (Blueprint $t) {
            $t->id(); $t->foreignId('product_id')->constrained()->restrictOnDelete(); $t->string('sku')->unique();
            $t->string('size', 16); $t->string('color', 32); $t->decimal('price',12,0); $t->unsignedInteger('stock');
            $t->boolean('is_active')->default(true); $t->unique(['product_id','size','color']); $t->timestamps();
        });
        Schema::create('cart_items', function (Blueprint $t) {
            $t->id(); $t->foreignId('user_id')->constrained()->restrictOnDelete();
            $t->foreignId('variant_id')->constrained('product_variants')->restrictOnDelete();
            $t->unsignedInteger('quantity'); $t->unique(['user_id','variant_id']); $t->timestamps();
        });
        Schema::create('orders', function (Blueprint $t) {
            $t->id(); $t->foreignId('user_id')->constrained()->restrictOnDelete(); $t->uuid('checkout_token')->unique();
            $t->string('recipient_name'); $t->string('phone',20); $t->text('address');
            $t->string('payment_method',16)->default('cod'); $t->string('status',16)->default('pending');
            $t->decimal('total_amount',12,0); $t->timestamps();
        });
        Schema::create('order_items', function (Blueprint $t) {
            $t->id(); $t->foreignId('order_id')->constrained()->restrictOnDelete();
            $t->foreignId('variant_id')->constrained('product_variants')->restrictOnDelete();
            $t->string('product_name'); $t->string('sku'); $t->string('size',16); $t->string('color',32);
            $t->decimal('unit_price',12,0); $t->unsignedInteger('quantity'); $t->decimal('line_total',12,0); $t->timestamps();
        });
    }
    public function down(): void {
        foreach (['order_items','orders','cart_items','product_variants','products','categories'] as $table) Schema::dropIfExists($table);
    }
};
