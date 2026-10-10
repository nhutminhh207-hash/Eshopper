<div class="container-fluid">
<div class="row bg-secondary py-2 px-xl-5"><div class="col-6">Thời trang mỗi ngày</div><div class="col-6 text-right">Fashion Shop</div></div>
<div class="row align-items-center py-3 px-xl-5">
<div class="col-lg-3 col-12 mb-3 mb-lg-0"><a href="{{ route('home') }}" class="text-decoration-none"><h1 class="m-0 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold border px-3 mr-1">E</span>Shopper</h1></a></div>
<div class="col-lg-6 col-8"><div class="input-group"><input type="search" class="form-control" placeholder="Tìm kiếm sản phẩm" disabled aria-label="Tìm kiếm sản phẩm, đang hoàn thiện"><div class="input-group-append"><button type="button" class="input-group-text bg-transparent text-primary" disabled title="Tìm kiếm đang hoàn thiện"><i class="fa fa-search"></i></button></div></div></div>
<div class="col-lg-3 col-4 text-right"><a href="{{ route('cart.index') }}" class="btn border" aria-label="Giỏ hàng"><i class="fas fa-shopping-cart text-primary"></i><span class="d-none d-md-inline ml-1">Giỏ hàng</span></a></div>
</div></div>
<div class="container-fluid"><div class="row border-top px-xl-5">
<div class="col-lg-3"><a class="btn shadow-none d-flex align-items-center justify-content-between bg-primary text-white w-100" data-toggle="collapse" href="#navbar-vertical" role="button" aria-expanded="false" style="height:65px;padding:0 30px"><h6 class="m-0">Danh mục</h6><i class="fa fa-angle-down text-dark"></i></a>
<nav class="collapse navbar navbar-vertical navbar-light p-0 border bg-light" id="navbar-vertical"><div class="navbar-nav w-100"><a class="nav-item nav-link" href="{{ route('shop.index') }}">Tất cả sản phẩm</a><span class="nav-item nav-link text-muted">Áo thun</span><span class="nav-item nav-link text-muted">Áo sơ mi</span><span class="nav-item nav-link text-muted">Quần</span></div></nav></div>
<div class="col-lg-9"><nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-0"><button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-label="Mở menu"><span class="navbar-toggler-icon"></span></button><div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
<div class="navbar-nav mr-auto py-0"><a class="nav-item nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Trang chủ</a><a class="nav-item nav-link {{ request()->routeIs('shop.*') ? 'active' : '' }}" href="{{ route('shop.index') }}">Sản phẩm</a>
@auth
@if(auth()->user()->role === 'admin')
<a class="nav-item nav-link" href="{{ route('admin.categories.index') }}">Danh mục</a><a class="nav-item nav-link" href="{{ route('admin.products.index') }}">Quản lý sản phẩm</a><a class="nav-item nav-link" href="{{ route('admin.orders.index') }}">Đơn hàng</a>
@else
<a class="nav-item nav-link" href="{{ route('cart.index') }}">Giỏ hàng</a><a class="nav-item nav-link" href="{{ route('checkout.index') }}">Thanh toán</a><a class="nav-item nav-link" href="{{ route('orders.index') }}">Đơn của tôi</a>
@endif
@endauth
</div><div class="navbar-nav ml-auto py-0">@guest<a class="nav-item nav-link" href="{{ route('login') }}">Đăng nhập</a><a class="nav-item nav-link" href="{{ route('register') }}">Đăng ký</a>@else<span class="nav-item nav-link">{{ auth()->user()->name }}</span><form method="POST" action="{{ route('logout') }}" class="d-flex align-items-center">@csrf<button class="btn btn-sm btn-outline-primary">Đăng xuất</button></form>@endguest</div>
</div></nav></div></div></div>
