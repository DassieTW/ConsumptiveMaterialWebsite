@extends('layouts.adminTemplate')

@section('css')
@endsection

@section('js')
    <script src="{{ asset('/js/editBulletin.js?v=') . env('APP_VERSION') }}"></script>
@endsection


@section('content')
    <div id="mountingPoint" class="container-fluid p-0">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <div class="col-auto">
                <h2 class="pb-3">{!! __('templateWords.editNews') !!}</h2>
            </div>

            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block" id="mountingPoint">
                {{-- <vue-bread-crumb></vue-bread-crumb> --}}
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card flex-fill w-100">
                    <div class="card-header">
                        <h1 class="card-title mb-0">{!! __('templateWords.addNews') !!}</h1>
                    </div>
                    <div class="card-body pt-3 pb-3">
                        <form id="addBulletinForm" class="row g-3">
                            <div class="col-md-6">
                                <label for="titleField" class="form-label">{!! __('templateWords.title') !!}</label>
                                <input type="text" class="form-control" id="titleField" name="titleField" required>
                            </div>
                            <div class="col-md-6">
                                <label for="levelField" class="form-label">{!! __('templateWords.level') !!}</label>
                                <select id="levelField" class="form-select">
                                    <option value="normal" selected>{!! __('templateWords.normal') !!}</option>
                                    <option class="text-warning-emphasis" value="important">{!! __('templateWords.important') !!}</option>
                                    <option class="text-danger-emphasis" value="urgent">{!! __('templateWords.urgent') !!}</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="contentField" class="form-label">{!! __('templateWords.content') !!}</label>
                                <textarea type="text" class="form-control" id="contentField" name="contentField" required></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="levelField" class="form-label">{!! __('templateWords.cat') !!}</label>
                                <select id="levelField" class="form-select">
                                    @for ($i = 1; $i < count($cat_list); $i++)
                                        <option value="{{ $cat_list[$i] }}">{{ $cat_list[$i] }}</option>
                                    @endfor
                                    <option value="newCat">{!! __('templateWords.addNewCat') !!}</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="inputState" class="form-label">{!! __('templateWords.site') !!}</label>
                                <select id="inputState" class="form-select">
                                    @for ($i = 1; $i < count($database_list); $i++)
                                        @if ($database_list[$i] === \DB::connection()->getDatabaseName())
                                            <option value="{{ $database_list[$i] }}" selected>
                                                {{ $database_names[$i] }}
                                            </option>
                                        @else
                                            <option value="{{ $database_list[$i] }}">
                                                {{ $database_names[$i] }}
                                            </option>
                                        @endif
                                    @endfor
                                </select>
                            </div>
                            <div class="w-100" style="height: 2ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="row justify-content-center align-items-center mt-3">
                                <button class="btn btn-primary col-auto" type="submit">{!! __('templateWords.addNews') !!}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <button type="hidden" id="QueryFlag" name="QueryFlag" value="Posting" style="display: none;"></button>
        <div class="row">
            <div class="card w-100 flex-fill">
                <div class="card-header">
                    <h1 class="card-title mb-0">{!! __('templateWords.editNews') !!}</h1>
                </div>
                <news-table></news-table>
            </div>
        </div>
    </div>
@endsection
