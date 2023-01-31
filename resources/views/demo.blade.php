@extends('layouts.app')
@section('title', 'демо')

@section('header')
    @parent
@endsection

@section('content')

<div class="mb-3">
    @include('include.include1')
</div>

<div class="mb-3">
    <x-external-api/>
</div>

@endsection

@section('footer')
    @parent
@endsection