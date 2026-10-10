<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'EShopper — Fashion Shop')</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('template/eshopper/lib/owlcarousel/assets/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ asset('template/eshopper/css/style.css') }}">
<style>.product-img img{width:100%;height:280px;object-fit:cover}.foundation{background:#fff4ce;padding:16px;border-left:4px solid #b98300;margin-bottom:20px}pre{white-space:pre-wrap}.table-responsive{overflow-x:auto}</style>
</head>
<body>
@include('partials.header')
<main><div class="container-fluid px-xl-5">@include('partials.alerts')</div><div class="@yield('content_wrapper', 'container py-4')">@yield('content')</div></main>
@include('partials.footer')
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('template/eshopper/lib/easing/easing.min.js') }}"></script>
<script src="{{ asset('template/eshopper/lib/owlcarousel/owl.carousel.min.js') }}"></script>
<script src="{{ asset('template/eshopper/js/main.js') }}"></script>
</body></html>
