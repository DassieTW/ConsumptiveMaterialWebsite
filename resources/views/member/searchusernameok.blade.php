@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <!--for this page's sepcified js -->
    <script src="{{ asset('js/login/usernamechange.js?v=') . env('APP_VERSION') }}"></script>
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
            <h3>{!! __('templateWords.UserInfo') !!}</h3>
        </div>
        <div class="card-body">
            <input class="form-control form-control-lg " type="text" id="numbersearch" name="numbersearch"
                placeholder="{!! __('loginPageLang.username_placeholder') !!}" oninput="if(value.length>12)value=value.slice(0,9)"
                style="width: 200px">
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <form id="searchusername" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table" id="test">
                        <tr>
                            {{-- <th>{!! __('loginPageLang.change') !!}</th> --}}
                            <th>{!! __('loginPageLang.username') !!}</th>
                            <th>{!! __('loginPageLang.password') !!}</th>
                            <th>{!! __('loginPageLang.priority') !!}</th>
                            <th>{!! __('loginPageLang.name') !!}</th>
                            <th>{!! __('loginPageLang.dep') !!}</th>
                            <th>{!! __('loginPageLang.mail') !!}</th>
                        </tr>
                        @foreach ($data as $data)
                            <tr class="isnRows">
                                <td><input type="hidden" id="username{{ $loop->index }}"
                                        name="username{{ $loop->index }}"
                                        value="{{ $data->username }}">{{ $data->username }}</td>
                                <td>{{ $data->password }}</td>
                                <td>
                                    <select class="form-select" id="priority{{ $loop->index }}" style="width: 10ch">
                                        <option selected>{{ $data->priority }}</option>
                                    </select>
                                </td>
                                <td>{{ $data->姓名 }}</td>
                                <td>{{ $data->部門 }}</td>
                                <td>{{ $data->email }}</td>
                            </tr>
                            <input type="hidden" id="count" name="count" value="{{ $loop->count }}">
                            <input type="hidden" id="department" name="department" value="{{ $department }}">
                        @endforeach
                    </table>
                </div>
            </form>
        </div>
    </div>
@endsection
