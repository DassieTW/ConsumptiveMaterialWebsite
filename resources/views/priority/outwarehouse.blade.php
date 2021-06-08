@extends('layouts.adminTemplate')
@section('content')
@if($pr === 4)
<button type="button" >領料</button>
@elseif($pr === 3)
<button type="button" >領料</button>
<button type="button" >領料單</button>
<button type="button" >查詢</button>
@else
<button type="button" >領料</button>
<button type="button" >領料單</button>
<button type="button" >查詢</button>
<button type="button" >刪除</button>
@endif
<form action="{{ route('member.login') }}" method="POST">
    @csrf
    <input type="submit" value="home">
</form>
@endsection
