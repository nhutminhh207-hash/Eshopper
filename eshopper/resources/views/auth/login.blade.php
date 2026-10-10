@extends('layouts.store')
@section('content')
<div class="row justify-content-center"><div class="col-md-6"><h1 class="h3">Đăng nhập</h1><form method="POST" action="{{ route('login.store') }}">@csrf
<div class="form-group"><label for="email">Email</label><input required class="form-control" id="email" name="email" type="email" value="{{ old('email') }}">@error('email')<small class="text-danger">{{ $message }}</small>@enderror</div>
<div class="form-group"><label for="password">Mật khẩu</label><input required class="form-control" id="password" name="password" type="password">@error('password')<small class="text-danger">{{ $message }}</small>@enderror</div>
<button class="btn btn-primary">Đăng nhập</button></form></div></div>
@endsection
