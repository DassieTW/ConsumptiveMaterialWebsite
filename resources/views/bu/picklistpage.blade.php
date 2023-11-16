@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <!--for this page's sepcified js -->
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('bupagelang.bu') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                {{-- <vue-bread-crumb></vue-bread-crumb> --}}
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="card w-75">
            <div class="card-header">
                <h3>{!! __('bupagelang.picklist') !!}</h3>
            </div>
            <div class="row justify-content-center">
                <div class="card-body">
                    <form action="{{ route('bu.picklist') }}" method="POST">
                        @csrf
                        <div class="row w-100 justify-content-center mb-3">
                            <label class="col col-auto form-label">{!! __('bupagelang.picklist') !!}</label>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">
                                <select class="form-select form-select-lg @error('list') is-invalid @enderror"
                                    id="list" name="list">
                                    <option style="display: none" disabled selected value="">{!! __('bupagelang.enterpicklist') !!}
                                    </option>
                                    @foreach ($data as $data)
                                        <option>{{ $data->調撥單號 }}</option>
                                    @endforeach
                                </select>
                                @error('list')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong> {!! __('bupagelang.enterpicklist') !!}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <div class="row w-100 justify-content-center">
                                <div class="col col-auto">
                                    <input type="submit" id="submit" name="submit" class="btn btn-lg btn-primary"
                                        value="{!! __('bupagelang.searchpicklist') !!}">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
