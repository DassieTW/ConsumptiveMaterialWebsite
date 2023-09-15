@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <!--for this page's sepcified js -->
    <script src="{{ asset('js/month/consumeadd.js?v=') . env('APP_VERSION') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.monthly') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>
        <div class="card w-100" id="consumehead">
            <div class="card-header">
                <h3>{!! __('monthlyPRpageLang.isnConsumeAdd') !!}</h3>
            </div>
            <div class="card-body">
                <form id="consume" class="row gx-6 gy-1 align-items-center">
                    @csrf
                    <div class="col-auto">
                        <label class="col col-auto form-label">{!! __('monthlyPRpageLang.isn') !!}</label>
                        <input class="form-control form-control-lg " type="text" id="number" name="number"
                            placeholder="{!! __('monthlyPRpageLang.enterisn') !!}" oninput="if(value.length>12)value=value.slice(0,12)">
                        <div class="invalid-feedback" id="numbererror" style="display:none; color:red;">
                            {!! __('monthlyPRpageLang.isnlength') !!}
                        </div>
                        <div class="invalid-feedback" id="numbererror1" style="display:none; color:red;">
                            {!! __('monthlyPRpageLang.noisn') !!}
                        </div>
                    </div>
                    <div class="col-auto">
                        {{-- <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line--> --}}
                        <label class="col col-auto form-label">{!! __('monthlyPRpageLang.90isn') !!}</label>
                        <input class="form-control form-control-lg " type="text" id="90isn" name="90isn"
                            placeholder="{!! __('monthlyPRpageLang.enter90isn') !!}" oninput="if(value.length>12)value=value.slice(0,12)">
                        <div class="invalid-feedback" id="90error" style="display:none; color:red;">
                            {!! __('monthlyPRpageLang.isnlength') !!}
                        </div>
                        <div class="invalid-feedback" id="90error1" style="display:none; color:red;">
                            {!! __('monthlyPRpageLang.noisn') !!}
                        </div>

                    </div>

                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                    <div class="col-auto">
                        {{-- <label class="col col-auto form-label">&nbsp;</label> --}}
                        <input type="submit" id="add" name="add" style="width: 10ch;"
                            class="form-control form-control-lg btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.add') !!}">
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <button class="btn btn-lg btn-primary" style id="loadconsume">{!! __('monthlyPRpageLang.loadconsume') !!}</button>
                    </div>

                </form>
            </div>
        </div>
        <div class="card w-100" id="consumebody">
            <div class="card-body">
                <form id="consumeadd" style="display: none">
                    @csrf
                    <div class="table-responsive">
                        <table class="table" id="consumeaddtable">
                            <tbody id="consumeaddbody">
                                <tr>
                                    <th>{!! __('monthlyPRpageLang.delete') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.isn') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.pName') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.format') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.unit') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.lt') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.consume') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.client') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.machine') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.process') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.90isn') !!}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                    <div id="emailinputarea">
                        <label class="form-label col col-3">{!! __('monthlyPRpageLang.surepeopleemail') !!}:</label>
                        <div class="col col-8">
                            <div class="input-group" style="width: 30ch;">
                                <select class="form-select form-select-lg" id="email">
                                    <option style="display: none" disabled selected value="">
                                        {!! __('monthlyPRpageLang.noemail') !!}
                                    </option>
                                    @foreach ($people as $people)
                                        <option value="{{ $people->姓名 }}">{{ $people->email }}</option>
                                    @endforeach
                                </select>
                                <span class="input-group-text input-group-text-lg" id="emailTail"></span>

                            </div>
                        </div>
                    </div>

                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                    <div class="row w-100 justify-content-center">
                        <div class="col col-auto">
                            <input type="submit" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.submit') !!}"
                                style="width: 80px">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <div class="card w-75" id="consumeupload">
                <div class="card-header">
                    <h3>{!! __('monthlyPRpageLang.upload') !!}</h3>
                </div>
                <div class="row justify-content-center">
                    <div class="card-body">
                        <div class=" w-100">
                            <form method="post" enctype="multipart/form-data" action="{{ route('month.uploadconsume') }}">
                                @csrf
                                <div class="row w-100 justify-content-center mb-3">
                                    <div class="col col-auto ">
                                        <a href="{{ asset('download/ConsumeExample.xlsx') }}"
                                            download>{!! __('monthlyPRpageLang.exampleExcel') !!}</a>
                                    </div>

                                    <div class="w-100" style="height: 1ch;"></div>
                                    <!-- </div>breaks cols to a new line-->

                                    <label class="col col-auto form-label">{!! __('monthlyPRpageLang.plz_upload') !!}</label>
                                    <div class="w-100" style="height: 1ch;"></div>
                                    <!-- </div>breaks cols to a new line-->

                                    <div class="col col-auto">
                                        <input class="form-control @error('select_file') is-invalid @enderror"
                                            type="file" name="select_file" />
                                        @error('select_file')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="w-100" style="height: 1ch;"></div>
                                    <!-- </div>breaks cols to a new line-->
                                    <div class="row w-100 justify-content-center">
                                        <div class="col col-auto">
                                            <input type="submit" name="upload" class="btn btn-lg btn-primary"
                                                value="{!! __('monthlyPRpageLang.upload1') !!}">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
