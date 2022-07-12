@extends('layouts.app')

@section('content')

    <h1>Conversion Example - exception</h1>

    <div class="alert alert-danger" role="alert">Exception occurred</div>

    <pre>
        @php
            /** @var Exception $exception */
            print_r($exception)
        @endphp
    </pre>

    <div>
        <a href="{{ route('show-upload-form') }}">
            <button type="button" class="btn btn-warning mt-2">Back to home</button>
        </a>
    </div>
@endsection
