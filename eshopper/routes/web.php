<?php
use App\Http\Controllers\{AuthController,FoundationController};
use Illuminate\Support\Facades\Route;
Route::get('/',[FoundationController::class,'home'])->name('home');
Route::get('/shop',[FoundationController::class,'shop'])->name('shop.index');
Route::get('/shop/{product}',[FoundationController::class,'detail'])->name('shop.show');
Route::middleware('guest')->group(function () {
    Route::get('/login',[AuthController::class,'loginForm'])->name('login');
    Route::post('/login',[AuthController::class,'login'])->middleware('throttle:5,1')->name('login.store');
    Route::get('/register',[AuthController::class,'registerForm'])->name('register');
    Route::post('/register',[AuthController::class,'register'])->middleware('throttle:5,1')->name('register.store');
});
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth')->name('logout');
Route::middleware(['auth','role:user'])->group(function () {
    Route::get('/cart',[FoundationController::class,'cart'])->name('cart.index');
    Route::post('/cart',[FoundationController::class,'unfinished'])->name('cart.store');
    Route::patch('/cart/{cartItem}',[FoundationController::class,'unfinished'])->name('cart.update');
    Route::delete('/cart/{cartItem}',[FoundationController::class,'unfinished'])->name('cart.destroy');
    Route::get('/checkout',[FoundationController::class,'checkout'])->name('checkout.index');
    Route::post('/checkout',[FoundationController::class,'unfinished'])->name('checkout.store');
    Route::get('/orders',[FoundationController::class,'orders'])->name('orders.index');
    Route::get('/orders/{order}',[FoundationController::class,'order'])->name('orders.show');
    Route::patch('/orders/{order}/cancel',[FoundationController::class,'unfinished'])->name('orders.cancel');
});
Route::prefix('admin')->name('admin.')->middleware(['auth','role:admin'])->group(function () {
    Route::get('/',[FoundationController::class,'dashboard'])->name('dashboard');
    foreach (['categories','products'] as $module) {
        $parameter=$module==='categories'?'category':'product';
        Route::get('/'.$module,[FoundationController::class,$module])->name($module.'.index');
        Route::get('/'.$module.'/create',[FoundationController::class,'unfinished'])->name($module.'.create');
        Route::post('/'.$module,[FoundationController::class,'unfinished'])->name($module.'.store');
        Route::get('/'.$module.'/{'.$parameter.'}/edit',[FoundationController::class,'unfinished'])->name($module.'.edit');
        Route::get('/'.$module.'/{'.$parameter.'}',[FoundationController::class,'unfinished'])->name($module.'.show');
        Route::patch('/'.$module.'/{'.$parameter.'}',[FoundationController::class,'unfinished'])->name($module.'.update');
        Route::delete('/'.$module.'/{'.$parameter.'}',[FoundationController::class,'unfinished'])->name($module.'.destroy');
    }
    Route::get('/orders',[FoundationController::class,'adminOrders'])->name('orders.index');
    Route::get('/orders/{order}',[FoundationController::class,'adminOrder'])->name('orders.show');
    Route::patch('/orders/{order}/status',[FoundationController::class,'unfinished'])->name('orders.update');
});
