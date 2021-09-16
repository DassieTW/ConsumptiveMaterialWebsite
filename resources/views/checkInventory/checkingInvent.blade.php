@extends('layouts.adminTemplate')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
<link rel="stylesheet" href="{{ asset('/css/animate.min.css') }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('/js/checkInventory/checkInvent.js') }}"></script>
@endsection


@section('content')
<div class="container-fluid p-0">

    <div class="row mb-2 mb-xl-3 justify-content-between">
        <div class="col-auto">
            <h2 class="pb-3">{!! __('checkInvLang.check') !!}</h2>
        </div>

        {{-- this div will not be visible if screen is smaller than lg --}}
        <div class="col-auto ml-auto text-right mt-n1 d-none d-lg-block">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mt-1 mb-0">
                    <li class="breadcrumb-item"><a href="#">{!! __('templateWords.websiteName') !!}</a></li>
                    <li class="breadcrumb-item"><a href="#">{!! __('checkInvLang.page_name') !!}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{!! __('checkInvLang.check') !!}</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6 col-xxl-12">
            <div class="card flex-fill w-100">
                <div class="card-header pb-0">
                    <h1 class="card-title">{!! __('checkInvLang.serial_number') !!} :&nbsp;&nbsp;
                        <div class="btn-group col col-auto">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="continueT"
                                data-bs-toggle="dropdown" data-bs-auto-close="inside" aria-expanded="false" data-serial-no="">@php
                                    if( $serialNums->first() !== null ){
                                        echo $serialNums->first()->單號 ;
                                    } // if
                                @endphp</button>
                            <ul class="dropdown-menu" aria-labelledby="continueT" id="serialList">
                                @foreach ($serialNums as $serialNum)
                                @if ($serialNum->單號 === $serialNums->first()->單號)
                                <li><a class="serialNum dropdown-item active" href="#">{{ $serialNum->單號 }}</a></li>
                                @else
                                <li><a class="serialNum dropdown-item" href="#">{{ $serialNum->單號 }}</a></li>
                                @endif
                                @endforeach
                            </ul>
                        </div>
                    </h1>
                </div>
                <div class="card-body">
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                    <div class="row ms-auto p-0 m-0">
                        <label class="col col-auto col-form-label p-0 m-0">{!! __('checkInvLang.search')
                            !!}：</label>
                        <div class="form-check form-switch col col-auto">
                            <input class="form-check-input" type="checkbox" id="toggle-state">
                            <label class="form-check-label" for="toggle-state" id="toggle-state-text"></label>
                        </div>
                    </div>
                    <form class="inp text-center needs-validation" id="inp" method="post" novalidate autocomplete="off">
                        @csrf
                        <input type="text" id="texBox" name="texBox" class="form-control form-control-lg"
                            style="text-align: center;" autocomplete="off" required autofocus>
                        <input type="submit" class="form-control" name="hiddensub" id="hiddensub"
                            style="position: absolute; left: -9999px; width: 1px; height: 1px;" tabindex="-1">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="scrollToView" class="message row align-items-center p-0 m-0" style="color: black;">
        <!-- message added here by js-->
    </div>
    <div class="dynamic">
        <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-touch="true" data-bs-ride="carousel"
            data-bs-interval="false">
            <div class="carousel-inner">
                <!-- things that are generated dynamically added here -->
            </div>
        </div>
    </div>
    <div id="pageCount" class="row w-100 p-0 m-0 center justify-content-evenly align-items-center">
        <!-- things that are generated dynamically added here -->
    </div>

</div>
@endsection