<?php
namespace App\Http\Controllers;
use App\Models\{Category,Product,Order};
use Illuminate\Http\Request;
class FoundationController extends Controller {
    public function home() { return view('foundation.home'); }
    public function dashboard() { return view('foundation.module',['title'=>'Quản trị — Kiều','owner'=>'Kiều','rows'=>[]]); }
    public function categories() { return view('foundation.module',['title'=>'CN01 — Danh mục','owner'=>'Châu','rows'=>Category::orderBy('id')->get(['id','name','slug'])->toArray()]); }
    public function products() { return view('foundation.module',['title'=>'CN02 — Sản phẩm','owner'=>'Danh','rows'=>Product::with('variants')->get()->map(fn($p)=>['id'=>$p->id,'name'=>$p->name,'variants'=>$p->variants->count()])->toArray()]); }
    public function shop() { return view('foundation.shop',['products'=>Product::with(['category','variants'=>fn($q)=>$q->where('is_active',true)])->where('is_active',true)->paginate(12)]); }
    public function detail(Product $product) { abort_unless($product->is_active,404); return view('foundation.module',['title'=>$product->name,'owner'=>'Nhựt','rows'=>$product->variants()->where('is_active',true)->get(['id','sku','size','color','price','stock'])->toArray()]); }
    public function cart(Request $r) { return view('foundation.module',['title'=>'CN04 — Giỏ mẫu của bạn','owner'=>'Nhựt','rows'=>$r->user()->cartItems()->with('variant')->get()->map(fn($x)=>['id'=>$x->id,'sku'=>$x->variant->sku,'quantity'=>$x->quantity,'price'=>$x->variant->price])->toArray()]); }
    public function checkout(Request $r) { return view('foundation.module',['title'=>'CN05 — Nền checkout, cần làm form','owner'=>'Anh Tú','rows'=>$r->user()->cartItems()->get(['id','variant_id','quantity'])->toArray()]); }
    public function orders(Request $r) { return view('foundation.module',['title'=>'CN06 — Đơn cá nhân mẫu','owner'=>'Kiều','rows'=>$r->user()->orders()->get(['id','status','total_amount'])->toArray()]); }
    public function order(Request $r,Order $order) { abort_unless($order->user_id===$r->user()->id,403); return view('foundation.module',['title'=>'Đơn #'.$order->id,'owner'=>'Kiều','rows'=>$order->items()->get(['product_name','size','color','quantity','unit_price'])->toArray()]); }
    public function adminOrders() { return view('foundation.module',['title'=>'CN06 — Đơn Admin','owner'=>'Kiều','rows'=>Order::get(['id','user_id','status','total_amount'])->toArray()]); }
    public function adminOrder(Order $order) { return view('foundation.module',['title'=>'Admin — Đơn #'.$order->id,'owner'=>'Kiều','rows'=>$order->items()->get(['product_name','size','color','quantity','unit_price'])->toArray()]); }
    public function unfinished() { abort(501,'Đây là route đã chốt. Chủ module cần triển khai form/controller trước khi sử dụng.'); }
}
