@extends('layouts.store')
@section('content')
<div class="row justify-content-center"><div class="col-md-6"><h1 class="h3">Đăng ký</h1><form method="POST" action="{{ route('register.store') }}">@csrf
<div class="form-group"><label for="name">Họ tên</label><input required class="form-control" id="name" name="name" type="text" value="{{ old('name') }}">@error('name')<small class="text-danger">{{ $message }}</small>@enderror</div>
<div class="form-group"><label for="email">Email</label><input required class="form-control" id="email" name="email" type="email" value="{{ old('email') }}">@error('email')<small class="text-danger">{{ $message }}</small>@enderror</div>
<div class="form-group"><label for="password">Mật khẩu</label><input required class="form-control" id="password" name="password" type="password">@error('password')<small class="text-danger">{{ $message }}</small>@enderror</div>
<div class="form-group"><label for="password_confirmation">Nhập lại mật khẩu</label><input required class="form-control" id="password_confirmation" name="password_confirmation" type="password">@error('password_confirmation')<small class="text-danger">{{ $message }}</small>@enderror</div>
<button class="btn btn-primary">Tạo tài khoản</button></form></div></div>
@endsection
