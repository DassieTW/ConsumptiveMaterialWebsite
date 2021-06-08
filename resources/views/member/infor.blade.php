@extends('layouts.adminTemplate')
@section('content')
功能
<hr />

<?php
    $username = Session::get('username');
    echo "now login user name : " . $username;
?>

<form action="{{ route('member.change') }}" method="GET">
    @csrf
    <input type="submit" value="更改密碼">
</form>

<form action="{{ route('member.new') }}" method="POST">
    @csrf
    <input type="submit" value="新增人員">
</form>

<form action="{{ route('member.search') }}" method="POST">
    @csrf
    <input type="submit" value="查詢人員">
</form>

<form action="{{ route('member.logout') }}" method="POST">
    @csrf
    <input type="submit" value="Logout">
</form>



@endsection
