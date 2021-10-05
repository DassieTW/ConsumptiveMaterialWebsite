@extends('layouts.adminTemplate')
@section('content')
@if ($pr === 3)
<button type="button" >備註說明</button>

@elseif($pr === 4)
您沒有這功能的權限
@else
<button type="button" >備註說明</button>
<button type="button" >取消</button>
@endif
<form action="{{ route('member.login') }}" method="POST">
    @csrf
    <input type="submit" value="home">
</form>
@endsection
