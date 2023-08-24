@extends('layouts.adminTemplate')
@section('css')
    <style>
        /* for single line table with over-flow , SAP style as asked */
        table {
            /* table-layout: fixed; */
            /* width: 900px; */
        }

        .table-responsive {
            height: 600px;
            overflow: scroll;
        }

        thead tr:nth-child(1) th {
            background: white;
            position: sticky;
            top: 0;
            z-index: 10;
        }
    </style>
@endsection

@section('js')
    <!--for this page's sepcified js -->
    <script src="{{ asset('js/month/consumesearch.js?v=') . env('APP_VERSION') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.monthly') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('monthlyPRpageLang.isnConsumeUpdate') !!}</h3>
                <input class="form-control form-control-lg " type="text" id="numbersearch" name="numbersearch"
                    placeholder="{!! __('basicInfoLang.enterisn') !!}" oninput="if(value.length>12)value=value.slice(0,12)"
                    style="width: 200px">
            </div>
            <div class="card-body" id="consumebody">
                <form id="consume" method="POST">
                    @csrf
                    <input type="hidden" id="titlename" name="titlename" value="單耗">

                    <input type="submit" id="delete" name="delete" class="btn btn-lg btn-primary"
                        value="{!! __('monthlyPRpageLang.delete') !!}">
                    &nbsp;
                    <input type="submit" id="change" name="change" class="btn btn-lg btn-primary"
                        value="{!! __('monthlyPRpageLang.change') !!}">
                    &nbsp;
                    <input type="submit" id="download" name="download" class="btn btn-lg btn-primary"
                        value="{!! __('monthlyPRpageLang.download') !!}">
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                    <label class="form-label">{!! __('monthlyPRpageLang.surepeopleemail') !!}:</label>
                    <div class="input-group" style="width: 40ch;">
                        <input type="text" id="email" name="email" class="form-control form-control-lg"
                            style="text-align:center;" placeholder="{!! __('loginPageLang.enter_email') !!}">
                        <span class="input-group-text input-group-text-lg" id="emailTail">@pegatroncorp.com</span>
                    </div>
                    <div class="input-group">
                        <ul id="peoplemenu" style="display: none;" class="list-group">
                            @foreach ($people as $people)
                                <a class="peoplelist list-group-item list-group-item-action"
                                    href="#">{{ $people->姓名 }}</a>
                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            @endforeach
                        </ul>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{!! __('monthlyPRpageLang.check') !!}</th>
                                    <th><input type="hidden" id="title0" name="title0"
                                            value="客戶別">{!! __('monthlyPRpageLang.client') !!}</th>
                                    <th><input type="hidden" id="title1" name="title1"
                                            value="機種">{!! __('monthlyPRpageLang.machine') !!}</th>
                                    <th><input type="hidden" id="title2" name="title2"
                                            value="製程">{!! __('monthlyPRpageLang.process') !!}</th>
                                    <th><input type="hidden" id="title3" name="title3"
                                            value="料號">{!! __('monthlyPRpageLang.isn') !!}</th>
                                    <th><input type="hidden" id="title4" name="title4"
                                            value="品名">{!! __('monthlyPRpageLang.pName') !!}</th>
                                    <th><input type="hidden" id="title5" name="title5"
                                            value="規格">{!! __('monthlyPRpageLang.format') !!}</th>
                                    <th><input type="hidden" id="title6" name="title6"
                                            value="單耗">{!! __('monthlyPRpageLang.consume') !!}</th>
                                    <th><input type="hidden" id="title7" name="title7"
                                            value="畫押信箱">{!! __('monthlyPRpageLang.email') !!}</th>
                                    <th><input type="hidden" id="title8" name="title8"
                                            value="備註">{!! __('monthlyPRpageLang.remark') !!}</th>
                                </tr>
                                <input type="hidden" id="titlecount" name="titlecount" value="9">

                            </thead>
                            <tbody>
                                @foreach ($data as $data)
                                    <?php
                                    $name = DB::table('consumptive_material')
                                        ->where('料號', $data->料號)
                                        ->value('品名');
                                    $format = DB::table('consumptive_material')
                                        ->where('料號', $data->料號)
                                        ->value('規格');
                                    
                                    $unitConsume = abs((float) $data->單耗) < 1e-20 ? '0' : rtrim(sprintf('%.10F', ((float) $data->單耗)), '0');
                                    // result should be 0 or 1.8392832 or 14.
                                    
                                    if (strpos($unitConsume, '.') === strlen($unitConsume) - 1) {
                                        // if the result is 5.  (should be like 5.0)
                                        $data->單耗 = sprintf('%.1F', ((float) $data->單耗));
                                    }
                                    // if
                                    else {
                                        $data->單耗 = $unitConsume;
                                    } // else
                                    ?>

                                    <tr id="{{ $loop->index }}" @class([
                                        'isnRows',
                                        'table-success' => $data->狀態 === '已完成',
                                        'table-danger' => $data->狀態 !== '已完成',
                                    ])>
                                        <td><input class="innumber" type="checkbox" id="innumber" name="innumber"
                                                style="width:20px;height:20px;" value="{{ $loop->index }}"></td>
                                        <td><input type="hidden" id="client{{ $loop->index }}"
                                                name="client{{ $loop->index }}"
                                                value="{{ $data->客戶別 }}">{{ $data->客戶別 }}</td>
                                        <td><input type="hidden" id="machine{{ $loop->index }}"
                                                name="machine{{ $loop->index }}"
                                                value="{{ $data->機種 }}">{{ $data->機種 }}</td>
                                        <td><input type="hidden" id="production{{ $loop->index }}"
                                                name="production{{ $loop->index }}"
                                                value="{{ $data->製程 }}">{{ $data->製程 }}</td>
                                        <td><input type="hidden" id="number{{ $loop->index }}"
                                                name="number{{ $loop->index }}"
                                                value="{{ $data->料號 }}">{{ $data->料號 }}</td>
                                        <td><input type="hidden" id="name{{ $loop->index }}"
                                                name="name{{ $loop->index }}"
                                                value="{{ $name }}">{{ $name }}</td>
                                        <td><input type="hidden" id="format{{ $loop->index }}"
                                                name="format{{ $loop->index }}"
                                                value="{{ $format }}">{{ $format }}</td>
                                        <td><input style="width: 200px;" class="form-control form-control-lg "
                                                type="text" id="amount{{ $loop->index }}" required
                                                name="amount{{ $loop->index }}" value="{{ $data->單耗 }}">
                                        </td>
                                        <td><input type="hidden" id="email{{ $loop->index }}"
                                                name="email{{ $loop->index }}"
                                                value="{{ $data->畫押信箱 }}">{{ $data->畫押信箱 }}</td>
                                        <td><input type="hidden" id="status{{ $loop->index }}"
                                                name="status{{ $loop->index }}"
                                                value="{{ $data->狀態 }}">{{ $data->狀態 }}</td>
                                    </tr>
                            </tbody>
                            <input type="hidden" id="count" name="count" value="{{ $loop->count }}">
                            @endforeach

                        </table>
                    </div>

                    {{-- <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line--> --}}
                    {{-- <label class="form-label">{!! __('monthlyPRpageLang.surepeople') !!}</label>
                <input type="text" id="jobnumber" name="jobnumber" class="form-control form-control"
                    style="width: 250px" placeholder="{!! __('monthlyPRpageLang.nopeople') !!}">
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line--> --}}
                    {{-- <label class="form-label">{!! __('monthlyPRpageLang.surepeopleemail') !!}</label>
                <div class="input-group" style="width: 410px">
                    <input type="text" id="email" name="email" class="form-control form-control" style="width: 150px"
                        placeholder="{!! __('loginPageLang.enter_email') !!}">
                    <div class="input-group-text"><span class="col col-auto">@pegatroncorp.com</span></div>
                </div>

                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line--> --}}

                </form>
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            </div>
        </div>
    </div>
@endsection
