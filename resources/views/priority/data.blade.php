@extends('layouts.adminTemplate')
@section('content')
@if($pr === 4)
您沒有這功能的權限
@else
<button type="button" >基礎信息</button>
<button type="button"  >新增</button>
<button type="button"  >查詢與修改</button>
<button type="button"  >條碼查詢</button>
@endif
<form action="{{ route('member.login') }}" method="POST">
    @csrf
    <input type="submit" value="home">
</form>
@endsection
