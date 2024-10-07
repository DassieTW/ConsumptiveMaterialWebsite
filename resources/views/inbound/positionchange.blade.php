@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <!--for this page's sepcified js -->
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
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.inbound') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                {{-- <vue-bread-crumb></vue-bread-crumb> --}}
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="card w-75">
                <div class="card-header">
                    <h3>{!! __('inboundpageLang.locationchange') !!}</h3>
                </div>
                <div class="row justify-content-center">
                    <div class="card-body">
                        <form action="{{ route('inbound.change') }}" method="POST">
                            @csrf
                            <div class="row w-100 justify-content-center mb-3">

                                <label class="col col-auto form-label">{!! __('inboundpageLang.isn') !!}</label>
                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                <div class="col-lg-6  col-md-12 col-sm-12">
                                    <input class="form-control form-control-lg @error('number') is-invalid @enderror"
                                        type="text" id="number" name="number" placeholder="{!! __('inboundpageLang.enterisn') !!}"
                                        oninput="if(value.length>12)value=value.slice(0,12)">
                                    @error('number')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                <label class="col col-auto form-label">{!! __('inboundpageLang.loc') !!}</label>
                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                <div class="col-lg-6  col-md-12 col-sm-12">
                                    <select class="form-select form-select-lg" id="position" name="position">
                                        <option style="display: none" disabled selected>{!! __('inboundpageLang.enterloc') !!}
                                        </option>
                                        @foreach ($position as $position)
                                            <option>{{ trim($position->儲存位置) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                                <label class="col col-auto form-label">{!! __('inboundpageLang.senddep') !!}</label>
                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                <div class="col-lg-6  col-md-12 col-sm-12">
                                    <select class="form-select form-select-lg" id="send" name="send">
                                        <option style="display: none" disabled selected>{!! __('inboundpageLang.entersenddep') !!}</option>
                                        @foreach ($send as $send)
                                            <option>{{ $send->發料部門 }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->



                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                                <div class="row w-100 justify-content-center">
                                    <div class="col col-auto">
                                        <input type="submit" id="search" name="search" class="btn btn-lg btn-primary"
                                            value="{!! __('inboundpageLang.search') !!}">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
