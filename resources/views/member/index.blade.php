@extends('layouts.adminTemplate')
@section('content')
首頁


<br><a type="submit" class="btn btn-lg btn-primary"  href = "{{ route('member.login') }}">Sign in</a>
@endsection
