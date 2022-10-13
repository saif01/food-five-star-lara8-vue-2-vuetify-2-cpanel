@extends('errors.master')
@section('title', '500')

@section('content')
    <img src="{{ asset('all-assets/errors/500.gif') }}" alt="500" class="img-fluid">
@endsection

{{-- @extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('Server Error')) --}}
