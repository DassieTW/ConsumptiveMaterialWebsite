@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
@endsection
@section('content')

<hr />

<?php
    $username = session('username');
    $database = session('database');
    echo __('templateWords.nowuser') .' '. $username.'<br>'.$database;
?>


@endsection
