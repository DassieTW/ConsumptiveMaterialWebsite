@extends('layouts.adminTemplate')
@section('content')
@if($pr === 4)
您沒有這功能的權限
@else
<button type="button" >料號單號</button>
<button type="button" >站位人力</button>
<button type="button" >匯入非月請購資料表</button>
<button type="button" >匯入月請購資料表</button>
<button type="button" >詢價單號查詢與修改</button>
<button type="button" >在途數量查詢</button>
@endif
<form action="{{ route('member.login') }}" method="POST">
    @csrf
    <input type="submit" value="home">
</form>
@endsection
