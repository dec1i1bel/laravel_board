@extends('layouts.app')
@section('title', ' LBoard')

@section('header')
    @parent
@endsection

@section('content')
    <div class="card m-auto w-75">
        <div class="row g-0">
            <div class="col-md-4">
                <div class="card-img-detail h-100" style="background-image: url('{{ Storage::url($post->img_preview) }}')">
                </div>
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">{{ $post->title }}</h5>
                    <p class="card-text">{{ $post->content }}</p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Цена: {{ $post->price }} руб</li>
                    <li class="list-group-item">Продавец: {{ $post->user->name }}</li>
                    <li class="list-group-item">Телефон: {{ $post->user->phone }}</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
