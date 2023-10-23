@extends('layouts.adminTemplate')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . env('APP_VERSION') }}">
@endsection

@section('js')
    <!--for this page's sepcified js -->
@endsection
@section('content')
    <div class="row">
        <div class="card w-100" style="height: 100%;">
            <div class="card-header" style="color: rgb(103, 67, 0);">
                <strong>{!! __('templateWords.clear_cache') !!}</strong>
            </div>
            <div class="card-body w-100">
                <div class="w-100" style="font-size: 1.5rem;"><strong>{!! __('templateWords.first_thing_first') !!}</strong></div>
                <img class="w-100" src="{{ asset('/admin/img/photos/keybd.png') }}" alt="keyboard">
                <div class="w-100" style="height: 2.5rem;"></div><!-- </div>breaks cols to a new line-->
                <div class="w-100" style="font-size: 1.5rem;"><strong>{!! __('templateWords.chrome') !!}</strong></div>
                <iframe class="w-100" src="{{ asset('/download/Chrome設定.pdf') }}" width="100%"
                    style="height:25cm; border: 3px solid black;">
                    This browser does not support embedded PDFs.
                    <div class="w-100" style="height: 0px;"></div><!-- </div>breaks cols to a new line-->
                    Please download the PDF to view it: <a href="../download/Chrome設定.pdf" download="">Download
                        PDF</a>
                </iframe>

                <div class="w-100" style="height: 2.5rem;"></div><!-- </div>breaks cols to a new line-->

                <div class="w-100" style="font-size: 1.5rem;"><strong>{!! __('templateWords.edge') !!}</strong></div>
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
