@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
@endsection

@section('content')
<h1>{!! __('basicInfoLang.basicInfo') !!}</h1>
<hr/>

@if ($choose === 'factory')
<h2>{!! __('basicInfoLang.factory') !!}</h2>
<hr/>
@foreach($factorys as $factory)
<h3>{{ $loop->index + 1 }}.{{ $factory->廠別 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'client')
<h2>{!! __('basicInfoLang.client') !!}</h2>
<hr/>
@foreach($clients as $client)
<h3>{{ $loop->index + 1 }}.{{ $client->客戶 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'machine')
<h2>{!! __('basicInfoLang.machine') !!}</h2>
<hr/>
@foreach($machines as $machine)
<h3>{{ $loop->index + 1 }}.{{ $machine->機種 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'production')
<h2>{!! __('basicInfoLang.process') !!}</h2>
<hr/>
@foreach($productions as $production)
<h3>{{ $loop->index + 1 }}.{{ $production->製程 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'line')
<h2>{!! __('basicInfoLang.line') !!}</h2>
<hr/>
@foreach($lines as $line)
<h3>{{ $loop->index + 1 }}.{{ $line->線別 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'use')
<h2>{!! __('basicInfoLang.usedep') !!}</h2>
<hr/>
@foreach($uses as $use)
<h3>{{ $loop->index + 1 }}.{{ $use->領用部門 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'usereason')
<h2>{!! __('basicInfoLang.usereason') !!}</h2>
<hr/>
@foreach($usereasons as $usereason)
<h3>{{ $loop->index + 1 }}.{{ $usereason->領用原因 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'inreason')
<h2>{!! __('basicInfoLang.inreason') !!}</h2>
<hr/>
@foreach($inreasons as $inreason)
<h3>{{ $loop->index + 1 }}.{{ $inreason->入庫原因 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'position')
<h2>{!! __('basicInfoLang.loc') !!}</h2>
<hr/>
@foreach($positions as $position)
<h3>{{ $loop->index + 1 }}.{{ $position->儲存位置 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'send')
<h2>{!! __('basicInfoLang.senddep') !!}</h2>
<hr/>
@foreach($sends as $send)
<h3>{{ $loop->index + 1 }}.{{ $send->發料部門 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'o')
<h2>{!! __('basicInfoLang.obound') !!}</h2>
<hr/>
@foreach($os as $o)
<h3>{{ $loop->index + 1 }}.{{ $o->O庫 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'back')
<h2>{!! __('basicInfoLang.returnreason') !!}</h2>
<hr/>
@foreach($backs as $back)
<h3>{{ $loop->index + 1 }}.{{ $back->退回原因 }}</h3>
<hr/>
@endforeach

@else


@endif
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('basic.index')}}'">{!! __('basicInfoLang.basicInfo') !!}</button>

@endsection
