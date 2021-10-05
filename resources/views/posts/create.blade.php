新增文章
<p>
<form method="POST" action="{{ route('posts.store') }}">
    @csrf
    <div class="form-group row">
        <label for="content" class="col-md-4 col-form-label text-md-right">{{ __('內容:') }}</label>

        <div class="col-md-6">
            <textarea id="content" type="content" class="form-control @error('content') is-invalid @enderror" name="content" value="{{ old('content') }}" required autofocus></textarea>

            @error('content')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>

    <div class="form-group row mb-0">
        <div class="col-md-8 offset-md-4">
            <button type="submit" class="btn btn-primary">
                {{ __('送出文章') }}
            </button>
        </div>
    </div>
</form>