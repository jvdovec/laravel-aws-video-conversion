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
@endsection
