<?php
namespace App\Services;
use App\Models\{User,CartItem,ProductVariant};
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class CartService {
    public function add(User $user,int $variantId,int $quantity): CartItem { return $this->save($user,$variantId,$quantity,true); }
    public function update(User $user,int $itemId,int $quantity): CartItem {
        $item=$user->cartItems()->findOrFail($itemId);
        return $this->save($user,$item->variant_id,$quantity,false,$itemId);
    }
    private function save(User $user,int $variantId,int $quantity,bool $add,?int $itemId=null): CartItem {
        abort_unless($user->role==='user',403);
        return DB::transaction(function () use ($user,$variantId,$quantity,$add,$itemId) {
            User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            $variant=ProductVariant::with('product')->whereKey($variantId)->lockForUpdate()->firstOrFail();
            $item=$user->cartItems()->where('variant_id',$variantId)->first();
            if ($itemId!==null && (!$item || $item->id!==$itemId)) abort(404);
            $total=$add?($item?->quantity ?? 0)+$quantity:$quantity;
            if ($quantity<1 || $total>$variant->stock || !$variant->is_active || !$variant->product->is_active) throw ValidationException::withMessages(['quantity'=>'Số lượng không hợp lệ, vượt kho hoặc sản phẩm đã ẩn.']);
            return CartItem::updateOrCreate(['user_id'=>$user->id,'variant_id'=>$variantId],['quantity'=>$total]);
        });
    }
    public function remove(User $user,int $itemId): void {
        abort_unless($user->role==='user',403);
        DB::transaction(function () use ($user,$itemId) {
            User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            $user->cartItems()->findOrFail($itemId)->delete();
        });
    }
}
