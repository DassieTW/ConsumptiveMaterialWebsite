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
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <main class="d-flex w-100 h-100">
        <form action="{{ route('member.search') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">工號</label>
                <input class="form-control form-control-lg @error('number') is-invalid @enderror" type="text" id ="number" name="number" placeholder="Enter job number" required/>
                @error('number')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="text-center mt-3">
                <input type = "submit" class="btn btn-lg btn-primary" value="Search">
                <!-- <button type="submit" class="btn btn-lg btn-primary">Sign in</button> -->
                <br><br>
                <a type="submit" class="btn btn-lg btn-primary"  href = "{{ route('member.index') }}">返回</a>
            </div>
        </form>
    </main>
</body>
</html>
@endsection
