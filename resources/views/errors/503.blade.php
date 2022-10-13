@extends('errors.master')
@section('title', '503')

@section('content')
    <img src="{{ asset('all-assets/errors/503.gif') }}" alt="503" class="img-fluid">
    
@endsection


{{-- @extends('errors::minimal')

@section('title', __('Service Unavailable'))
@section('code', '503')
@section('message', __('Service Unavailable')) --}}
