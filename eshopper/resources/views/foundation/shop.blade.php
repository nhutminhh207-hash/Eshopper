@extends('layouts.store')
@section('title', 'Sản phẩm — EShopper')
@section('content_wrapper', '')
@section('content')
<div class="container-fluid bg-secondary mb-5"><div class="d-flex flex-column align-items-center justify-content-center" style="min-height:300px"><h1 class="font-weight-semi-bold text-uppercase mb-3">Sản phẩm</h1><div class="d-inline-flex"><a href="{{ route('home') }}">Trang chủ</a><span class="px-2">-</span><span>Sản phẩm</span></div></div></div>
<div class="container-fluid pt-5"><div class="row px-xl-5">
<aside class="col-lg-3 col-md-12"><p class="small text-muted">Bộ lọc đang hoàn thiện.</p>
@foreach(['price' => ['Khoảng giá', ['Tất cả mức giá', 'Dưới 200.000 đ', '200.000 – 500.000 đ', 'Trên 500.000 đ']], 'color' => ['Màu sắc', ['Tất cả màu', 'Đen', 'Trắng', 'Xanh']], 'size' => ['Kích thước', ['Tất cả size', 'S', 'M', 'L', 'XL']]] as $group => $filter)
<div class="border-bottom mb-4 pb-4"><h5 class="font-weight-semi-bold mb-4">{{ $filter[0] }}</h5>@foreach($filter[1] as $label)<div class="custom-control custom-checkbox mb-3"><input type="checkbox" class="custom-control-input" id="{{ $group }}-{{ $loop->index }}" disabled><label class="custom-control-label" for="{{ $group }}-{{ $loop->index }}">{{ $label }}</label></div>@endforeach</div>
@endforeach
</aside>
<div class="col-lg-9 col-md-12"><div class="row pb-3"><div class="col-12 pb-1"><div class="d-flex align-items-center justify-content-between mb-4"><span>{{ $products->total() }} sản phẩm</span><span class="text-muted small">Bộ sưu tập thời trang</span></div></div>
@forelse($products as $product)
<div class="col-lg-4 col-md-6 col-sm-6 pb-1"><div class="card product-item border-0 mb-4"><div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0"><a href="{{ route('shop.show', $product) }}"><img class="img-fluid" src="{{ asset('template/eshopper/img/product-' . (($product->id - 1) % 8 + 1) . '.jpg') }}" alt="Ảnh minh họa {{ $product->name }}"></a></div><div class="card-body border-left border-right text-center p-0 pt-4 pb-3"><h6 class="text-truncate mb-2 px-2">{{ $product->name }}</h6><p class="small text-muted mb-2">{{ $product->category->name }}</p><div class="d-flex justify-content-center"><h6>Từ {{ number_format($product->variants->min('price') ?? 0, 0, ',', '.') }} đ</h6></div></div><div class="card-footer d-flex justify-content-between bg-light border"><a href="{{ route('shop.show', $product) }}" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>Xem chi tiết</a><button class="btn btn-sm text-muted p-0" disabled title="Chức năng thêm vào giỏ đang hoàn thiện"><i class="fas fa-shopping-cart text-primary mr-1"></i>Thêm vào giỏ</button></div></div></div>
@empty<div class="col-12"><p>Chưa có sản phẩm.</p></div>@endforelse
<div class="col-12 pb-1"><nav aria-label="Phân trang"><ul class="pagination justify-content-center mb-3">@for($page=1; $page <= $products->lastPage(); $page++)<li class="page-item {{ $page === $products->currentPage() ? 'active' : '' }}"><a class="page-link" href="{{ $products->url($page) }}" @if($page === $products->currentPage()) aria-current="page" @endif>{{ $page }}</a></li>@endfor</ul></nav></div>
</div></div></div></div>
@endsection
