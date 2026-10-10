<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Product extends Model {
    protected $fillable = ['category_id', 'name', 'slug', 'description', 'image_path', 'is_active'];
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function variants(): HasMany { return $this->hasMany(ProductVariant::class); }
    protected function casts(): array { return ['is_active'=>'boolean']; }
}
