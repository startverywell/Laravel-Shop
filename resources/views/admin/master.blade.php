@extends('layouts.app')
@push('stylesheets')
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@endpush
@section('header')
    @include('admin.includes.header')
@endsection
@section('main')

    <main id="fs-main" class="">
        {{--        @if (Auth::user())--}}
        @include('admin.includes.sidebar')
        {{--		@endif--}}
        <div class="fs-content">
        @yield('content')
        </div>
    </main>

@endsection

