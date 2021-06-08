@extends('layouts.adminTemplate')

@section('content')
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Success</title>
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
            </div>
        </form>
        </main>
    </body>
</html>
@endsection
