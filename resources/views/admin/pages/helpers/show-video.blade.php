@extends('admin.layout.app')

@section('title')
    @lang('nav.helpers')
@endsection

@section('css-vendor')
@endsection

{{-- main content --}}
@section('content')
    <div class="card mb-3">
        <div class="card-header border-bottom d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">{{ $helper->name }}</h5>
            <a href="{{ url()->previous() }}" class="btn btn-success"><i class="bx bx-arrow-back me-0 me-lg-2"></i><span
                    class="d-none d-lg-inline-block">@lang('general.return')</span></a>
        </div>
        <div class="card-body">
            <div class="filter-container mt-3">
                <h6 class="card-title mb-3">@lang('helpers.video')</h6>
                <video class="w-100" poster="{{ asset(Storage::url($helper->avatar)) }}" id="plyr-video-player" playsinline
                    controls>
                    <source src="{{ asset(Storage::url($helper->video)) }}" type="video/mp4" />
                </video>
            </div>
        </div>
    </div>
@endsection


@section('script-vendor')
@endsection
@section('script')
    <script>
        $('document').ready(function() {

        })
    </script>
@endsection
