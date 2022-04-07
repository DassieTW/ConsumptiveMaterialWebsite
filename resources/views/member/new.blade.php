@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/login/new.js') }}"></script>
@endsection
@section('content')
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>

<div id="mountingPoint">
    <div class="row mb-2 mb-xl-3 justify-content-between">
        <h2 class="col-auto">{!! __('templateWords.userManage') !!}</h2>
        <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
            <vue-bread-crumb></vue-bread-crumb>
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="card w-75">
        <div class="card-header">
            <h3>{!! __('templateWords.newPInfo') !!}</h3>
        </div>
        <div class="row justify-content-center">
            <div class="card-body">
                <div class="w-100">
                    <form id="new_people">
                        @csrf
                        <div class="row w-100 justify-content-center mb-3">
                            <label class="col col-auto form-label">{!! __('loginPageLang.jobnumber') !!}</label>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">
                                <input class="form-control form-control-lg " type="text" id="number" name="number"
                                    required placeholder="{!! __('loginPageLang.enterjobsearch') !!}"
                                    oninput="if(value.length>9)value=value.slice(0,9)">
                                <div id="message" style="display:none; color:red;">
                                    {!! __('loginPageLang.jobrepeat') !!}
                                </div>
                                <div id="message1" style="display:none; color:red;">
                                    {!! __('loginPageLang.joblength') !!}
                                </div>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <label class="col col-auto form-label">{!! __('loginPageLang.name') !!}</label>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">
                                <input class="form-control form-control-lg " type="text" id="name" name="name" required
                                    placeholder="{!! __('loginPageLang.entername') !!}">
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <label class="col col-auto form-label">{!! __('loginPageLang.dep') !!}</label>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">
                                <input class="form-control form-control-lg " type="text" id="department"
                                    name="department" required placeholder="{!! __('loginPageLang.enterdep') !!}">
                            </div>

                        </div>
                </div>
                <div class="row w-100 justify-content-center">
                    <div class="col col-auto">
                        <input type="submit" id="submit" name="submit" class="btn btn-lg btn-primary"
                            value="{!! __('loginPageLang.new') !!}">
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

    <div class="card w-75">
        <div class="card-header">
            <h3>{!! __('monthlyPRpageLang.upload') !!}</h3>
        </div>

        <div class="row justify-content-center">
            <div class="card-body">
                <div class=" w-100">
                    <form method="post" enctype="multipart/form-data" action="{{ route('member.uploadpeople') }}">
                        @csrf
                        <div class="row w-100 justify-content-center mb-3">
                            <div class="col col-auto ">
                                <a href="{{asset('download/PeopleInformationExample.xlsx')}}" download>{!!
                                    __('loginPageLang.exampleExcel') !!}</a>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <label class="col col-auto form-label">{!! __('loginPageLang.plz_upload') !!}</label>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <div class="col col-auto">
                                <input class="form-control @error('select_file') is-invalid @enderror" type="file"
                                    name="select_file" />
                                @error('select_file')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="row w-100 justify-content-center">
                                <div class="col col-auto">
                                    <input type="submit" name="upload" class="btn btn-lg btn-primary"
                                        value="{!! __('loginPageLang.upload') !!}">
                                </div>
                            </div>
                    </form>
                </div>
            </div>


        </div>
    </div>
</div>

</html>
@endsection
