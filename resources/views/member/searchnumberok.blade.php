@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <!--for this page's sepcified js -->
    <script src="{{ asset('js/login/numberchange.js?v=') . env('APP_VERSION') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.userManage') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3>{!! __('templateWords.PInfo') !!}</h3>
        </div>
        <div class="card-body">
            <form id="searchnumber" method="POST">
                <input type="submit" id="delete" name="delete" class="btn btn-lg btn-primary"
                    value="{!! __('loginPageLang.delete') !!}">
                &emsp;
                <input type="submit" id="change" name="change" class="btn btn-lg btn-primary"
                    value="{!! __('loginPageLang.change') !!}">
                @csrf
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>{!! __('loginPageLang.check') !!}</th>
                            <th>{!! __('loginPageLang.jobnumber') !!}</th>
                            <th>{!! __('loginPageLang.name') !!}</th>
                            <th>{!! __('loginPageLang.dep') !!}</th>
                        </tr>
                        @foreach ($data as $data)
                            <tr>
                                <td><input class="innumber" type="checkbox" id="innumber" name="innumber"
                                        style="width:20px;height:20px;" value="{{ $loop->index }}"></td>
                                <td><input type="hidden" id="number{{ $loop->index }}" name="number{{ $loop->index }}"
                                        value="{{ $data->工號 }}">{{ $data->工號 }}</td>
                                <td><input style="width:150px" type="text" id="name{{ $loop->index }}"
                                        name="name{{ $loop->index }}" value="{{ $data->姓名 }}"
                                        class="form-control form-control-lg"></td>
                                <td><input style="width:150px" type="text" id="department{{ $loop->index }}"
                                        name="department{{ $loop->index }}" value="{{ $data->部門 }}"
                                        class="form-control form-control-lg"></td>
                            </tr>
                            <input type="hidden" id="count" name="count" value="{{ $loop->count }}">
                        @endforeach

                    </table>
                </div>

                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->



            </form>
            {{-- <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line--> --}}

            {{-- <button class="btn btn-lg btn-primary" onclick="location.href='{{route('member.number')}}'">{!!
            __('loginPageLang.return') !!}</button> --}}
        </div>
    </div>
@endsection
