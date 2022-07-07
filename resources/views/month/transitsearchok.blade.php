@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
<style>
    /* hide scrollbar but still scrollable */
    .scrollableWithoutScrollbar {
        -ms-overflow-style: none !important;
        /* IE and Edge */
        scrollbar-width: none !important;
        /* FireFox */
    }

    .scrollableWithoutScrollbar::-webkit-scrollbar {
        /* Chrome, Safari and Opera */
        display: none !important;
    }
</style>
@endsection

@section('js')
<!--for this page's sepcified js -->
@endsection
@section('content')
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>
<div id="mountingPoint">
    <div class="row mb-2 mb-xl-3 justify-content-between">
        <h2 class="col-auto">{!! __('templateWords.monthly') !!}</h2>
        <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
            <vue-bread-crumb></vue-bread-crumb>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3>{!! __('monthlyPRpageLang.on_the_way_search') !!}</h3>

        </div>
        <div class="card-body">

            <transit-search-table></transit-search-table>

            {{-- <button class="btn btn-lg btn-primary" onclick="location.href='{{route('month.transit')}}'">{!!
                __('monthlyPRpageLang.return') !!}</button> --}}
        </div>
    </div>
</div>

</html>
@endsection
