@extends('errors.master')
@section('title', '401')

@section('content')
    <img src="{{ asset('all-assets/errors/401.gif') }}" alt="401" class="img-fluid">
@endsection


{{-- @extends('errors::minimal')

@section('title', __('Unauthorized'))
@section('code', '401')
@section('message', __('Unauthorized')) --}}
