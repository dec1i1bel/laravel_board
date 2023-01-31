@extends('layouts.app')
@section('title', ' явная привязка модели к маршруту')

@section('header')
    @parent
@endsection

@section('content')

    <div class="card m-auto w-75">
        <div class="row g-0">
            <div class="col-md-4">
                <div class="card-img-detail h-100" style="background-image: url('{{ Storage::url($post2->img_preview) }}')">
                </div>
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">{{ $post2->title }}</h5>
                    <p class="card-text">{{ $post2->content }}</p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Цена: {{ $post2->price }} руб</li>
                    <li class="list-group-item">Продавец: {{ $post2->user->name }}</li>
                    <li class="list-group-item">Телефон: {{ $post2->user->phone }}</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
