@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
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
<hr/>
@foreach($factorys as $factory)
<h3>{{ $loop->index + 1 }}.{{ $factory->廠別 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'client')
<h2>客戶別</h2>
<hr/>
@foreach($clients as $client)
<h3>{{ $loop->index + 1 }}.{{ $client->客戶 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'machine')
<h2>機種</h2>
<hr/>
@foreach($machines as $machine)
<h3>{{ $loop->index + 1 }}.{{ $machine->機種 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'production')
<h2>製程</h2>
<hr/>
@foreach($productions as $production)
<h3>{{ $loop->index + 1 }}.{{ $production->製程 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'line')
<h2>線別</h2>
<hr/>
@foreach($lines as $line)
<h3>{{ $loop->index + 1 }}.{{ $line->線別 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'use')
<h2>領用部門</h2>
<hr/>
@foreach($uses as $use)
<h3>{{ $loop->index + 1 }}.{{ $use->領用部門 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'usereason')
<h2>領用原因</h2>
<hr/>
@foreach($usereasons as $usereason)
<h3>{{ $loop->index + 1 }}.{{ $usereason->領用原因 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'inreason')
<h2>入庫原因</h2>
<hr/>
@foreach($inreasons as $inreason)
<h3>{{ $loop->index + 1 }}.{{ $inreason->入庫原因 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'position')
<h2>儲位</h2>
<hr/>
@foreach($positions as $position)
<h3>{{ $loop->index + 1 }}.{{ $position->儲存位置 }}</h3>
<hr/>
@endforeach

@elseif($choose === 'send')
<h2>發料部門</h2>
<hr/>
@foreach($sends as $send)
<h3>{{ $loop->index + 1 }}.{{ $send->發料部門 }}</h3>
<hr/>
@endforeach

@else


@endif
<a type="submit" class="btn btn-lg btn-primary"  href = "{{ route('basic.index') }}">基礎信息</a>

@endsection
