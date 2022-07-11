@extends('layouts.app')

@section('content')

    <h1>Status for Conversion Job Id: {{ $conversionJobId }}</h1>

    @if($isConversionJobComplete)

        <h2 class="text-success">Complete</h2>

        <form method="POST" action="{{ route('download-video-output') }}" class="mt-2">
            @csrf
            <input name="fileKey" value="{{ $videoOutputFileKey }}" type="hidden" />
            <button type="submit" class="btn btn-success">Download Video Output</button>
        </form>

        @foreach($videoThumbnailsFileKeys as $videoThumbnailFileKey)
            <form method="POST" action="{{ route('download-video-thumbnail') }}" class="mt-2">
                @csrf
                <input name="fileKey" value="{{ $videoThumbnailFileKey }}" type="hidden" />
                <button type="submit" class="btn btn-success">Download Video Thumbnail #{{ $loop->iteration }}</button>
            </form>
        @endforeach

    @else

        <h2 class="text-danger">Conversion is not yet complete</h2>
        <div>
            <a href="{{ url()->current() }}"><button type="button" class="btn btn-primary">Click here to refresh</button></a>
        </div>

    @endif

    <div>
        <button type="button" class="btn btn-info mt-2" onclick="$('#job-status-full').toggle();">Show / Hide Full Status Information</button>

        <a href="{{ route('show-upload-form') }}">
            <button type="button" class="btn btn-warning mt-2">Back to home</button>
        </a>
    </div>

    <pre id="job-status-full" class="mt-5" style="display:none;">{{ $conversionJobStatusToHtml }}</pre>

@endsection
