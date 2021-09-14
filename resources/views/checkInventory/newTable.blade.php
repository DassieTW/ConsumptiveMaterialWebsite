@extends('layouts.adminTemplate')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
<link rel="stylesheet" href="{{ asset('/css/animate.min.css') }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('/js/checkInventory/newTable.js') }}"></script>
@endsection


@section('content')
<div class="container-fluid p-0">

    <div class="row mb-2 mb-xl-3 justify-content-between">
        <div class="col-auto">
            <h2 class="pb-3">{!! __('checkInvLang.create_new_table') !!}</h2>
        </div>

        {{-- this div will not be visible if screen is smaller than lg --}}
        <div class="col-auto ml-auto text-right mt-n1 d-none d-lg-block">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mt-1 mb-0">
                    <li class="breadcrumb-item"><a href="#">{!! __('templateWords.websiteName') !!}</a></li>
                    <li class="breadcrumb-item"><a href="#">{!! __('checkInvLang.page_name') !!}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{!! __('checkInvLang.create_new_table') !!}
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card flex-fill w-100">
                <div class="card-body pt-2 pb-3">
                    <div class="card-header pb-0">
                        <h1 class="card-title">{!! __('checkInvLang.serial_number') !!} :&nbsp;&nbsp;
                            <div class="btn-group col col-auto">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="continueT"
                                    data-bs-toggle="dropdown" data-bs-auto-close="inside" aria-expanded="false"
                                    data-serial-no="">@php
                                    if( $serialNums->first() !== null ){
                                    echo $serialNums->first()->單號 ;
                                    }
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

                    @if ( $serialNums->first() === null )
                    <form class="text-center needs-validation" method="post" novalidate>
                        <div class="row justify-content-center align-items-center">
                            <div class="col col-auto">
                                <span class="col col-auto">{!! __('checkInvLang.plz_create')
                                    !!}</span>
                            </div>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col col-auto">
                                <button class="btn btn-primary col-auto" id="submitBtn" type="submit">{!!
                                    __('checkInvLang.create_new_table')
                                    !!}</button>
                            </div>
                        </div>
                    </form>
                    @else
                    <form class="text-center needs-validation" method="post" novalidate>
                        @csrf
                        <div class="table-responsive">
                            <table class="table align-items-center">
                                <tbody>
                                    <tr id="tableHead" class="table-primary align-items-center">
                                        <th class="col col-auto align-items-center px-0 m-0"><span>&nbsp;</span>
                                        </th>
                                        <th class="col col-auto align-items-center px-0 m-0"><span>{!!
                                                __('barcodeGenerator.isn') !!}</span>
                                        </th>
                                        <th class="col col-auto align-items-center px-0 m-0"><span>{!!
                                                __('barcodeGenerator.print_amount') !!}</span>
                                        </th>
                                    </tr>
                                    {{-- the content here is generated by js --}}
                                    <tr id="tableHead2" class="table-secondary align-items-center">
                                        <th class="col col-auto align-items-center px-0 m-0"><span>&nbsp;</span>
                                        </th>
                                        <th class="col col-auto align-items-center px-0 m-0"><span>{!!
                                                __('barcodeGenerator.loc') !!}</span>
                                        </th>
                                        <th class="col col-auto align-items-center px-0 m-0"><span>{!!
                                                __('barcodeGenerator.print_amount') !!}</span>
                                        </th>
                                    </tr>
                                    {{-- the content here is generated by js --}}
                                </tbody>
                            </table>
                        </div>
                        <input type="hidden" name="sID" id="sID" value="{!! \Session::getId() !!}">
                        <div class="row justify-content-center align-items-center mt-3">
                            <button class="btn btn-primary col-auto" id="printBtn" type="submit">{!!
                                __('barcodeGenerator.print')
                                !!}</button>
                        </div>
                    </form>
                    @endif
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