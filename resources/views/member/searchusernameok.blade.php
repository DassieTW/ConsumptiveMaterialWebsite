@extends('layouts.adminTemplate')
@section('css')
    <style>
        td,
        th {
            text-align: center;
            vertical-align: middle;
        }


        #siteListPicker::-webkit-scrollbar-track {
            -webkit-box-shadow: 0 0 1px hsla(0, 0%, 100%, .5);
            border-radius: 4px;
            background-color: #F5F5F5;
        }

        #siteListPicker::-webkit-scrollbar {
            width: 4px;
            -webkit-appearance: none;
        }

        #siteListPicker::-webkit-scrollbar-thumb {
            border-radius: 4px;
            -webkit-box-shadow: 0 0 1px hsla(0, 0%, 100%, .5);
            background-color: rgba(0, 0, 0, 0.3);
        }
    </style>
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
                {{-- <vue-bread-crumb></vue-bread-crumb> --}}
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
                            <th>{!! __('loginPageLang.priority') !!}</th>
                            <th>{!! __('loginPageLang.name') !!}</th>
                            <th>{!! __('loginPageLang.dep') !!}</th>
                            <th>{!! __('loginPageLang.mail') !!}</th>
                            @can('canAddSitesToUser', App\Models\Login::class)
                                <th>{!! __('loginPageLang.database_list') !!}</th>
                            @endcan
                        </tr>
                        @foreach ($data as $data)
                            <tr class="isnRows">
                                <td><input type="hidden" id="username{{ $loop->index }}"
                                        name="username{{ $loop->index }}"
                                        value="{{ $data->username }}">{{ $data->username }}</td>
                                <td>
                                    <select class="form-select" id="priority{{ $loop->index }}" style="width: 10ch">
                                        <option selected>{{ $data->priority }}</option>
                                    </select>
                                </td>
                                <td>{{ $data->姓名 }}</td>
                                <td>{{ $data->部門 }}</td>
                                <td>{{ $data->email }}</td>
                                @can('canAddSitesToUser', App\Models\Login::class)
                                    <td class="justify-content-center">
                                        <button id="{{ $data->username . '_' . $data->姓名 }}"
                                            value="{{ $data->available_dblist }}" type="button"
                                            class="btn btn-outline-info dbInfo" data-bs-toggle="modal"
                                            data-bs-target="#siteListPicker">Info</button>
                                    </td>
                                @endcan
                            </tr>
                            <input type="hidden" id="count" name="count" value="{{ $loop->count }}">
                        @endforeach
                    </table>
                </div>
            </form>

            @can('canAddSitesToUser', App\Models\Login::class)
                {{-- Modal --}}
                <div class="modal fade" id="siteListPicker" aria-hidden="true" aria-labelledby="siteListPicker" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title">Available Databases</h3>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row justify-content-center">
                                    @for ($i = 1; $i < count($db_list); $i++)
                                        <div class="form-check form-switch col-9">
                                            <input class="form-check-input dbCheckbox" type="checkbox" role="switch"
                                                id="{{ trim(str_replace('Consumables management', '', $db_list[$i])) }}"
                                                value="{{ trim(str_replace('Consumables management', '', $db_list[$i])) }}">
                                            <label class="form-check-label"
                                                for="{{ trim(str_replace('Consumables management', '', $db_list[$i])) }}">{{ $db_list[$i] }}</label>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="DeleteUser" class="btn btn-danger" data-bs-target="#AreYouSureModal"
                                    data-bs-toggle="modal">Delete
                                    This User</button>
                                <button type="button" id="ListConfirm" class="btn btn-success">Save
                                    Changes</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="AreYouSureModal" aria-hidden="true" aria-labelledby="AreYouSureModal"
                    tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body row justify-content-center">
                                <span class="col col-auto">Are You Sure ?</span>
                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                <button id="imsure" class="col col-auto btn btn-outline-danger" data-bs-dismiss="modal">YES</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
@endsection
