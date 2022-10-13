@extends('errors.master')
@section('title', '404')

@section('content')
    <img src="{{ asset('all-assets/errors/404.gif') }}" alt="404" class="img-fluid">
@endsection


{{-- @extends('errors::minimal')

@section('title', __('Not Found'))
@section('code', '404')
@section('message', __('Not Found')) --}}
