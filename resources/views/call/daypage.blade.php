@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <script>
        $(window).on('beforeunload', function() {
            $('body').loadingModal({
                text: 'Loading...',
                animation: 'circle'
            });
            $('body').loadingModal('show');
        });
    </script>
@endsection
@section('content')
    @if ($num > 0)
        <audio controls autoplay hidden>
            <source id="audio_2" src="/sound/sluggish stock.mp3" type="audio/mpeg">
        </audio>
    @endif

    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('callpageLang.callsys') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                {{-- <vue-bread-crumb></vue-bread-crumb> --}}
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="card w-75">
            <div class="card-header">
                <h3>{!! __('callpageLang.dayalert') !!}</h3>
            </div>
            <div class="row justify-content-center">
                <div class="card-body">
                    <form action="{{ route('call.daysubmit') }}" method="POST">
                        @csrf
                        <div class="row w-100 justify-content-center mb-3">

                            <label class="col col-auto form-label">{!! __('callpageLang.senddep') !!}</label>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">
                                <select class="form-select form-select-lg" id="send" name="send">
                                    <option style="display: none" disabled selected>{!! __('callpageLang.entersenddep') !!}
                                    </option>
                                    @foreach ($sends as $send)
                                        <option>{{ $send->發料部門 }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <div class="row w-100 justify-content-center">
                                <div class="col col-auto">
                                    <input type="submit" id="" name="" class="btn btn-lg btn-primary"
                                        value="{!! __('callpageLang.change') !!}">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
