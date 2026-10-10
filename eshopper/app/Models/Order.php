<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Order extends Model {
    protected $fillable = ['user_id', 'checkout_token', 'recipient_name', 'phone', 'address', 'payment_method', 'status', 'total_amount'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function items(): HasMany { return $this->hasMany(OrderItem::class); }
    protected function casts(): array { return ['total_amount'=>'integer']; }
}
