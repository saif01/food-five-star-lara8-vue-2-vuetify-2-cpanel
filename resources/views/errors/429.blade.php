@extends('errors.master')
@section('title', '429')

@section('content')
    <img src="{{ asset('all-assets/errors/429.gif') }}" alt="429" class="img-fluid">
@endsection


{{-- @extends('errors::minimal')

@section('title', __('Too Many Requests'))
@section('code', '429')
@section('message', __('Too Many Requests')) --}}
