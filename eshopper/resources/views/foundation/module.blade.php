@extends('layouts.store')
@section('content')
<h1 class="h3">{{ $title }}</h1><div class="foundation">Người phụ trách: <strong>{{ $owner }}</strong>. Đây là màn hình nền chỉ đọc. Tiếp tục làm form, validation và thao tác nghiệp vụ theo README của bạn.</div>
<div class="table-responsive">@if(count($rows))<table class="table table-bordered"><thead><tr>@foreach(array_keys($rows[0]) as $key)<th>{{ $key }}</th>@endforeach</tr></thead><tbody>@foreach($rows as $row)<tr>@foreach($row as $value)<td>{{ $value }}</td>@endforeach</tr>@endforeach</tbody></table>@else<p>Chưa có dữ liệu cho màn hình này.</p>@endif</div>
@endsection
