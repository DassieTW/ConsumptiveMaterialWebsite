@extends('layouts.adminTemplate')
@section('content')
人員信息
<hr />
@foreach($logins as $login)
    username : {{ $login->username }} <br>
    priority : {{ $login->priority }} <br>
    姓名 : {{ $login->姓名 }} <br>
    部門 : {{ $login->部門 }} <br>
    <hr />
@endforeach
<?php
    $username = Session::get('username');
    echo "now login user name : " . $username;
?>

<form action="{{ route('member.call') }}" method="POST">
    @csrf
    <input type="submit" value="call">
</form>
<form action="{{ route('member.data') }}" method="POST">
    @csrf
    <input type="submit" value="data">
</form>
<form action="{{ route('member.shop') }}" method="POST">
    @csrf
    <input type="submit" value="shop">
</form>
<form action="{{ route('member.inwarehouse') }}" method="POST">
    @csrf
    <input type="submit" value="inwarehouse">
</form>
<form action="{{ route('member.outwarehouse') }}" method="POST">
    @csrf
    <input type="submit" value="outwarehouse">
</form>

<form action="{{ route('member.change') }}" method="GET">
    @csrf
    <input type="submit" value="Change">
</form>

<form action="{{ route('member.logout') }}" method="POST">
    @csrf
    <input type="submit" value="Logout">
</form>

@endsection
