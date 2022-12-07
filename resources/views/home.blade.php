@extends('layouts.adminTemplate')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . env('APP_VERSION') }}">
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

    <div class="row">
        <div class="col col-auto">
            <a href="http://eip.tw.pegatroncorp.com/" target="_blank">{!! __('templateWords.taipei') !!}</a>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <a href="http://eip.tw.pegatroncorp.com/DeptSiteMap.aspx" target="_blank">{!! __('templateWords.dep') !!}</a>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <a href="http://project.eip.tw.pegatroncorp.com/default.aspx" target="_blank">{!! __('templateWords.project') !!}</a>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <a href="http://eip.sh.pegatroncorp.com/" target="_blank">{!! __('templateWords.east') !!}</a>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <a href="http://eip.sz.pegatroncorp.com/eip/" target="_blank">{!! __('templateWords.middle') !!}</a>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <a href="http://eip.cq.pegatroncorp.com/Home.aspx" target="_blank">{!! __('templateWords.west') !!}</a>
        </div>

        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

        <div class="card w-100" style="height: 100%;">
            <div class="card-header" style="color: rgb(103, 67, 0);">
                <strong>{!! __('templateWords.clear_cache') !!}</strong>
            </div>
            <div class="card-body w-100">
                <div class="w-100" style="font-size: 2rem;"><strong>{!! __('templateWords.chrome') !!}</strong></div>
                <iframe class="w-100" src="{{ asset('/download/Chrome設定.pdf') }}" width="100%"
                    style="height:25cm; border: 3px solid black;">
                    This browser does not support embedded PDFs.
                    <div class="w-100" style="height: 0px;"></div><!-- </div>breaks cols to a new line-->
                    Please download the PDF to view it: <a href="../download/Chrome設定.pdf" download="">Download PDF</a>
                </iframe>

                <div class="w-100" style="height: 2cm;"></div><!-- </div>breaks cols to a new line-->

                <div class="w-100" style="font-size: 2rem;"><strong>{!! __('templateWords.edge') !!}</strong></div>
                <iframe class="w-100" src="{{ asset('/download/Edge設定.pdf') }}" width="100%"
                    style="height:25cm; border: 3px solid black;">
                    This browser does not support embedded PDFs.
                    <div class="w-100" style="height: 0px;"></div><!-- </div>breaks cols to a new line-->
                    Please download the PDF to view it: <a href="../download/Edge設定.pdf" download="">Download PDF</a>
                </iframe>
            </div>
        </div>
    </div>
@endsection
