<?php
namespace Database\Seeders;
use App\Models\{User,Category,Product,ProductVariant,CartItem,Order};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{DB,Hash};
use Illuminate\Support\Str;
class FashionDemoSeeder extends Seeder {
    public function run(): void {
        if (!app()->environment(['local','testing'])) throw new \RuntimeException('Seeder demo chỉ chạy trong local/testing.');
        DB::transaction(function () {
            $admin=User::firstOrCreate(['email'=>'admin@example.test'], ['name'=>'Admin demo','role'=>'admin','password'=>Hash::make('DemoShop2026!')]);
            $a=User::firstOrCreate(['email'=>'usera@example.test'], ['name'=>'User A demo','role'=>'user','password'=>Hash::make('DemoShop2026!')]);
            $b=User::firstOrCreate(['email'=>'userb@example.test'], ['name'=>'User B demo','role'=>'user','password'=>Hash::make('DemoShop2026!')]);
            $categories=[];
            foreach (['Áo thun','Áo sơ mi','Quần'] as $name) $categories[]=Category::firstOrCreate(['slug'=>Str::slug($name)],['name'=>$name,'description'=>'Danh mục demo']);
            for ($i=1;$i<=15;$i++) {
                $product=Product::firstOrCreate(['slug'=>'san-pham-demo-'.$i],['category_id'=>$categories[($i-1)%3]->id,'name'=>'Sản phẩm demo '.$i,'description'=>'Dữ liệu mẫu để phát triển song song','image_path'=>'products/demo.jpg','is_active'=>true]);
                foreach ([['M','Đen'],['L','Trắng']] as $j=>[$size,$color]) ProductVariant::firstOrCreate(['sku'=>sprintf('DEMO-%02d-%s',$i,$size)],['product_id'=>$product->id,'size'=>$size,'color'=>$color,'price'=>150000+$i*1000+$j*10000,'stock'=>$i===1?0:($i===2?1:20),'is_active'=>true]);
            }
            // Seed đơn một lần; kho giảm đúng lượng đã đặt. Chạy lại không reset kho hoặc hồi sinh giỏ.
            foreach ([$a,$b] as $offset=>$user) {
                $token=$offset===0?'10000000-0000-4000-8000-000000000001':'10000000-0000-4000-8000-000000000002';
                if (Order::where('checkout_token',$token)->exists()) continue;
                $v=ProductVariant::where('sku',sprintf('DEMO-%02d-M',3+$offset))->lockForUpdate()->firstOrFail();
                if ($v->stock<2) throw new \RuntimeException('Không đủ kho để tạo đơn demo.');
                $order=Order::create(['user_id'=>$user->id,'checkout_token'=>$token,'recipient_name'=>$user->name,'phone'=>'0900000000','address'=>'Địa chỉ demo, không phải thông tin thật','payment_method'=>'cod','status'=>'pending','total_amount'=>$v->price*2]);
                $order->items()->create(['variant_id'=>$v->id,'product_name'=>$v->product->name,'sku'=>$v->sku,'size'=>$v->size,'color'=>$v->color,'unit_price'=>$v->price,'quantity'=>2,'line_total'=>$v->price*2]);
                $v->decrement('stock',2);
                foreach (['DEMO-05-M','DEMO-05-L'] as $sku) CartItem::firstOrCreate(['user_id'=>$user->id,'variant_id'=>ProductVariant::where('sku',$sku)->firstOrFail()->id],['quantity'=>1]);
            }
        });
    }
}
