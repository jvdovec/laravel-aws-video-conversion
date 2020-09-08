@extends('layouts.app')

@section('content')

    <h1>Conversion Example</h1>

    @if($errors)
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">{{ $error }}</div>
        @endforeach
    @endif

    <form method="post" action="{{ route('do-conversion') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <input type="file" name="file" class="form-control" aria-describedby="fileHelp">
            <small id="fileHelp" class="form-text text-muted">File which should be converted</small>
        </div>
        <button type="submit" class="btn btn-primary">Do Conversion</button>
    </form>
@endsection
