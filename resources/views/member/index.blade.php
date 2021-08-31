@extends('layouts.adminTemplate')
@section('content')
首頁
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('/admin/js/app.js') }}"></script>
<!-- <script src="{{ asset('js/popupNotice.js') }}"></script> -->
<!--for notifications pop up -->
@endsection

<br><a type="submit" class="btn btn-lg btn-primary"  href = "{{ route('member.login') }}">Sign in</a>
@endsection
