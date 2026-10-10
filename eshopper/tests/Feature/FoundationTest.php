<?php
namespace Tests\Feature;
use App\Models\{User,ProductVariant,Order};
use App\Services\{OrderService,CartService};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
class FoundationTest extends TestCase {
    use RefreshDatabase;
    protected function setUp(): void { parent::setUp(); $this->seed(); }
    private function customer(): User { return User::where('email','usera@example.test')->firstOrFail(); }
    public function test_seed_is_repeatable_without_resetting_stock(): void {
        $stock=ProductVariant::where('sku','DEMO-03-M')->value('stock'); $orders=Order::count();
        $this->seed();
        $this->assertSame($orders,Order::count()); $this->assertEquals($stock,ProductVariant::where('sku','DEMO-03-M')->value('stock'));
        $this->assertDatabaseCount('products',15); $this->assertDatabaseCount('product_variants',30);
    }
    public function test_auth_and_admin_access_are_separated(): void {
        $this->get('/cart')->assertRedirect('/login');
        $this->actingAs($this->customer())->get('/admin/products')->assertForbidden();
        $admin=User::where('email','admin@example.test')->firstOrFail();
        $this->actingAs($admin)->get('/admin/products')->assertOk();
        $this->get('/cart')->assertForbidden();
    }
    public function test_register_cannot_promote_to_admin(): void {
        $this->post('/register',['name'=>'Demo','email'=>'new@example.test','password'=>'DemoShop2026!','password_confirmation'=>'DemoShop2026!','role'=>'admin'])->assertRedirect('/shop');
        $this->assertDatabaseHas('users',['email'=>'new@example.test','role'=>'user']);
    }
    public function test_customer_cannot_read_another_order(): void {
        $a=$this->customer(); $b=User::where('email','userb@example.test')->firstOrFail();
        $this->actingAs($b)->get('/orders/'.$a->orders()->firstOrFail()->id)->assertForbidden();
    }
    public function test_checkout_and_cancel_are_idempotent(): void {
        $u=$this->customer(); $v=ProductVariant::where('sku','DEMO-05-M')->firstOrFail(); $stock=$v->stock;
        $quote=$u->cartItems()->with('variant')->get()->mapWithKeys(fn($x)=>[$x->variant_id=>$x->variant->price])->all();
        $service=app(OrderService::class); $token='20000000-0000-4000-8000-000000000001';
        $delivery=['recipient_name'=>'User A','phone'=>'0900000000','address'=>'Địa chỉ demo'];
        $order=$service->checkout($u,$delivery,$token,$quote);
        $again=$service->checkout($u,$delivery,$token,$quote);
        $this->assertSame($order->id,$again->id); $this->assertEquals($stock-1,$v->fresh()->stock);
        $this->assertSame(0,$u->cartItems()->count());
        $service->transition($u,$order->id,'cancelled'); $service->transition($u,$order->id,'cancelled');
        $this->assertEquals($stock,$v->fresh()->stock);
    }
    public function test_price_change_rolls_back_order_and_cart(): void {
        $u=$this->customer(); $count=Order::count(); $cartCount=$u->cartItems()->count();
        try { app(OrderService::class)->checkout($u,['recipient_name'=>'A','phone'=>'0900000000','address'=>'Demo'],'30000000-0000-4000-8000-000000000001',[]); $this->fail('Expected validation error'); }
        catch (ValidationException $e) { $this->assertArrayHasKey('price',$e->errors()); }
        $this->assertSame($count,Order::count()); $this->assertSame($cartCount,$u->cartItems()->count());
    }
    public function test_cart_accumulates_variant_and_rejects_overstock(): void {
        $u=$this->customer(); $v=ProductVariant::where('sku','DEMO-05-M')->firstOrFail(); $service=app(CartService::class);
        $item=$service->add($u,$v->id,1); $this->assertSame(2,$item->quantity);
        $this->expectException(ValidationException::class); $service->add($u,$v->id,1000);
    }
    public function test_shipping_order_cannot_be_cancelled(): void {
        $admin=User::where('email','admin@example.test')->firstOrFail(); $order=$this->customer()->orders()->firstOrFail(); $s=app(OrderService::class);
        $s->transition($admin,$order->id,'confirmed'); $s->transition($admin,$order->id,'shipping');
        $this->expectException(ValidationException::class); $s->transition($admin,$order->id,'cancelled');
    }
}
