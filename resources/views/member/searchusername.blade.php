@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <!--for this page's sepcified js -->
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.userManage') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                {{-- <vue-bread-crumb></vue-bread-crumb> --}}
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="card w-75">
            <div class="card-header">
                <h3>{!! __('templateWords.UserInfo') !!}</h3>
            </div>
            <div class="row justify-content-center">
                <div class="card-body">
                    <div class="w-100">
                        <form action="{{ route('member.searchusername') }}" method="POST">
                            @csrf
                            <div class="row w-100 justify-content-center mb-3">
                                <label class="col col-auto form-label">{!! __('loginPageLang.loginsearch') !!}</label>
                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                <div class="col-lg-6  col-md-12 col-sm-12">
                                    <input class="form-control form-control-lg @error('number') is-invalid @enderror"
                                        type="text" id="username" name="username"
                                        placeholder="{!! __('loginPageLang.enterlogin') !!}" />
                                </div>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <div class="row w-100 justify-content-center">
                                <div class="col col-auto">
                                    <input type="submit" class="btn btn-lg btn-primary" value="{!! __('loginPageLang.search') !!}">
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
