@extends('layouts.adminTemplate')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
    <!--for this page's sepcified js -->
    <script src="{{ asset('js/login/usernamechange.js') }}"></script>
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
    <div class="card">
        <div class="card-header">
            <h3>{!! __('templateWords.UserInfo') !!}</h3>
        </div>
        <div class="card-body">

            <form id="searchusername" method="POST">
                <input type="submit" id="delete" name="delete" class="btn btn-lg btn-primary"
                    value="{!! __('loginPageLang.delete') !!}">

                @csrf
                <div class="table-responsive">
                    <table class="table" id="test">
                        <tr>
                            <th>{!! __('loginPageLang.delete') !!}</th>
                            <th>{!! __('loginPageLang.username') !!}</th>
                            <th>{!! __('loginPageLang.password') !!}</th>
                            <th>{!! __('loginPageLang.priority') !!}</th>
                            <th>{!! __('loginPageLang.name') !!}</th>
                            <th>{!! __('loginPageLang.dep') !!}</th>
                            <th>{!! __('loginPageLang.mail') !!}</th>
                        </tr>
                        @foreach ($data as $data)
                            @if ($data->姓名 !== 'su')
                                <tr>
                                    <td><input class="innumber" type="checkbox" id="innumber" name="innumber"
                                            style="width:20px;height:20px;" value="{{ $loop->index }}"></td>
                                    <td><input type="hidden" id="username{{ $loop->index }}"
                                            name="username{{ $loop->index }}"
                                            value="{{ $data->username }}">{{ $data->username }}</td>
                                    <td>{{ $data->password }}</td>
                                    <td>{{ $data->priority }}</td>
                                    <td>{{ $data->姓名 }}</td>
                                    <td>{{ $data->部門 }}</td>
                                    <td>{{ $data->email }}</td>
                                </tr>
                                <input type="hidden" id="count" name="count" value="{{ $loop->count }}">
                            @endif
                        @endforeach

                    </table>
                </div>
            </form>
            {{-- <br> --}}
            {{-- <button class="btn btn-lg btn-primary" onclick="location.href='{{route('member.username')}}'">{!!
            __('loginPageLang.return') !!}</button> --}}
        </div>
    </div>

    </html>
@endsection
