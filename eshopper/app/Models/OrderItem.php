<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class OrderItem extends Model {
    protected $fillable = ['order_id', 'variant_id', 'product_name', 'sku', 'size', 'color', 'unit_price', 'quantity', 'line_total'];
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function variant(): BelongsTo { return $this->belongsTo(ProductVariant::class,'variant_id'); }
    protected function casts(): array { return ['unit_price'=>'integer','quantity'=>'integer','line_total'=>'integer']; }
}
