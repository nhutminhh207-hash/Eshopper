<?php
namespace App\Services;
use App\Models\{User,Order,ProductVariant};
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class OrderService {
    public const TRANSITIONS=['pending'=>['confirmed','cancelled'],'confirmed'=>['shipping','cancelled'],'shipping'=>['completed'],'completed'=>[],'cancelled'=>[]];
    /** $quotedPrices do controller lấy từ SESSION máy chủ của lượt checkout, không từ form. */
    public function checkout(User $user,array $delivery,string $token,array $quotedPrices): Order {
        abort_unless($user->role==='user',403);
        return DB::transaction(function () use ($user,$delivery,$token,$quotedPrices) {
            User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            $existing=Order::where('checkout_token',$token)->first();
            if ($existing) { abort_unless($existing->user_id===$user->id,403); return $existing; }
            $cart=$user->cartItems()->orderBy('variant_id')->get();
            if ($cart->isEmpty()) throw ValidationException::withMessages(['cart'=>'Giỏ hàng trống.']);
            $variants=ProductVariant::with('product')->whereIn('id',$cart->pluck('variant_id'))->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $total=0;
            foreach ($cart as $item) {
                $v=$variants->get($item->variant_id);
                if (!$v || !$v->is_active || !$v->product->is_active || $item->quantity<1 || $item->quantity>$v->stock) throw ValidationException::withMessages(['cart'=>'Có sản phẩm đã ẩn hoặc không đủ kho.']);
                if (!array_key_exists($v->id,$quotedPrices) || (int)$quotedPrices[$v->id]!==$v->price) throw ValidationException::withMessages(['price'=>'Giá hoặc giỏ đã thay đổi. Mở lại checkout và xác nhận giá mới.']);
                $total+=$v->price*$item->quantity;
            }
            $order=Order::create(['user_id'=>$user->id,'checkout_token'=>$token,'recipient_name'=>$delivery['recipient_name'],'phone'=>$delivery['phone'],'address'=>$delivery['address'],'payment_method'=>'cod','status'=>'pending','total_amount'=>$total]);
            foreach ($cart as $item) {
                $v=$variants[$item->variant_id];
                $order->items()->create(['variant_id'=>$v->id,'product_name'=>$v->product->name,'sku'=>$v->sku,'size'=>$v->size,'color'=>$v->color,'unit_price'=>$v->price,'quantity'=>$item->quantity,'line_total'=>$v->price*$item->quantity]);
                $v->decrement('stock',$item->quantity);
            }
            $user->cartItems()->delete();
            return $order->load('items');
        });
    }
    public function transition(User $actor,int $orderId,string $target): Order {
        return DB::transaction(function () use ($actor,$orderId,$target) {
            $order=Order::whereKey($orderId)->lockForUpdate()->firstOrFail();
            if ($actor->role!=='admin') {
                abort_unless($actor->role==='user' && $order->user_id===$actor->id,403);
                abort_unless($target==='cancelled' && in_array($order->status,['pending','cancelled'],true),403);
            }
            if ($order->status===$target) return $order;
            if (!in_array($target,self::TRANSITIONS[$order->status]??[],true)) throw ValidationException::withMessages(['status'=>'Không được chuyển trạng thái này.']);
            if ($target==='cancelled') {
                $quantities=$order->items()->get()->groupBy('variant_id')->map(fn ($items)=>$items->sum('quantity'));
                $variants=ProductVariant::whereIn('id',$quantities->keys())->orderBy('id')->lockForUpdate()->get();
                foreach ($variants as $v) $v->increment('stock',$quantities[$v->id]);
            }
            $order->update(['status'=>$target]);
            return $order;
        });
    }
}
