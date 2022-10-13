@extends('errors.master')
@section('title', '403')

@section('content')
    <img src="{{ asset('all-assets/errors/403.gif') }}" alt="403" class="img-fluid">
@endsection


{{-- @extends('errors::illustrated-layout')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden')) --}}