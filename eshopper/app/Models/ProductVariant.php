<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class ProductVariant extends Model {
    protected $fillable = ['product_id', 'sku', 'size', 'color', 'price', 'stock', 'is_active'];
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function cartItems(): HasMany { return $this->hasMany(CartItem::class,'variant_id'); }
    public function orderItems(): HasMany { return $this->hasMany(OrderItem::class,'variant_id'); }
    protected function casts(): array { return ['price'=>'integer','stock'=>'integer','is_active'=>'boolean']; }
}
