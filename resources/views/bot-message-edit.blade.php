@extends('admin-dashboard')

@section('content')

    @include('bot-message-form')
    @yield('sub-content')
@endsection
