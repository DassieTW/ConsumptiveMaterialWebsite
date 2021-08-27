@extends('layouts.adminTemplate')
@section('content')
@if($pr === 4)
您沒有這功能的權限
@elseif($pr === 3)
<button type="button" >新增</button>
<button type="button" >查詢</button>
@else
<button type="button" >新增</button>
<button type="button" >查詢</button>
<button type="button" >刪除</button>
<button type="button" >庫存查詢</button>
@endif
<form action="{{ route('member.login') }}" method="POST">
    @csrf
    <input type="submit" value="home">
</form>
@endsection
