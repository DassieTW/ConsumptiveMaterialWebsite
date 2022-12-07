@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <!--for this page's sepcified js -->
@endsection
@section('content')
    <hr />

    <?php
    use Carbon\Carbon;
    $username = session('username');
    $database = session('database');
    echo __('templateWords.nowuser') . ' ' . $username . '<br>' . $database;
    ?>

    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
@endsection
