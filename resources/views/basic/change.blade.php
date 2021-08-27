@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<<<<<<< HEAD
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('/admin/js/app.js') }}"></script>
<!-- <script src="{{ asset('js/popupNotice.js') }}"></script> -->
<!--for notifications pop up -->
@endsection

@section('content')
<h1>基礎信息</h1>
<hr/>

@if ($choose === 'factory')
<h2>廠別</h2>
=======
<!--for this page's sepcified js -->
@endsection

@section('content')
<h1>{!! __('basicInfoLang.basicInfo') !!}</h1>
<hr/>

@if ($choose === 'factory')
<h2>{!! __('basicInfoLang.factory') !!}</h2>
>>>>>>> 0827tony
<hr/>
@foreach($factorys as $factory)
<h3>{{ $loop->index + 1 }}.{{ $factory->廠別 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'client')
<<<<<<< HEAD
<h2>客戶別</h2>
=======
<h2>{!! __('basicInfoLang.client') !!}</h2>
>>>>>>> 0827tony
<hr/>
@foreach($clients as $client)
<h3>{{ $loop->index + 1 }}.{{ $client->客戶 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'machine')
<<<<<<< HEAD
<h2>機種</h2>
=======
<h2>{!! __('basicInfoLang.machine') !!}</h2>
>>>>>>> 0827tony
<hr/>
@foreach($machines as $machine)
<h3>{{ $loop->index + 1 }}.{{ $machine->機種 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'production')
<<<<<<< HEAD
<h2>製程</h2>
=======
<h2>{!! __('basicInfoLang.process') !!}</h2>
>>>>>>> 0827tony
<hr/>
@foreach($productions as $production)
<h3>{{ $loop->index + 1 }}.{{ $production->製程 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'line')
<<<<<<< HEAD
<h2>線別</h2>
=======
<h2>{!! __('basicInfoLang.line') !!}</h2>
>>>>>>> 0827tony
<hr/>
@foreach($lines as $line)
<h3>{{ $loop->index + 1 }}.{{ $line->線別 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'use')
<<<<<<< HEAD
<h2>領用部門</h2>
=======
<h2>{!! __('basicInfoLang.usedep') !!}</h2>
>>>>>>> 0827tony
<hr/>
@foreach($uses as $use)
<h3>{{ $loop->index + 1 }}.{{ $use->領用部門 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'usereason')
<<<<<<< HEAD
<h2>領用原因</h2>
=======
<h2>{!! __('basicInfoLang.usereason') !!}</h2>
>>>>>>> 0827tony
<hr/>
@foreach($usereasons as $usereason)
<h3>{{ $loop->index + 1 }}.{{ $usereason->領用原因 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'inreason')
<<<<<<< HEAD
<h2>入庫原因</h2>
=======
<h2>{!! __('basicInfoLang.inreason') !!}</h2>
>>>>>>> 0827tony
<hr/>
@foreach($inreasons as $inreason)
<h3>{{ $loop->index + 1 }}.{{ $inreason->入庫原因 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'position')
<<<<<<< HEAD
<h2>儲位</h2>
=======
<h2>{!! __('basicInfoLang.loc') !!}</h2>
>>>>>>> 0827tony
<hr/>
@foreach($positions as $position)
<h3>{{ $loop->index + 1 }}.{{ $position->儲存位置 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'send')
<<<<<<< HEAD
<h2>發料部門</h2>
=======
<h2>{!! __('basicInfoLang.senddep') !!}</h2>
>>>>>>> 0827tony
<hr/>
@foreach($sends as $send)
<h3>{{ $loop->index + 1 }}.{{ $send->發料部門 }}</h3>
<hr/>
@endforeach

<<<<<<< HEAD
=======
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

>>>>>>> 0827tony
@else


@endif
<<<<<<< HEAD
<a type="submit" class="btn btn-lg btn-primary"  href = "{{ route('basic.index') }}">基礎信息</a>
=======
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('basic.index')}}'">{!! __('basicInfoLang.basicInfo') !!}</button>
>>>>>>> 0827tony

@endsection
