@extends('layouts.app')
@section('title', 'Удаление объявления || LBoard')

@section('header')
    @parent
@endsection

@section('content')
    <div class="card m-auto w-75">
        <div class="row g-0">
            <div class="col-md-4">
                <img src="{{ Storage::url($post->img_preview) }}" class="img-fluid rounded-start" alt="...">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">{{ $post->title }}</h5>
                    <p class="card-text">{{ $post->content }}</p>
                    <p class="card-text">
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Цена: {{ $post->price }} руб</li>
                    <li class="list-group-item">Категории:&nbsp;
                        @foreach ($categories as $cat)
                            {{ $cat->name }}<br> 
                        @endforeach
                    </li>
                    <li class="list-group-item">Продавец: {{ $post->user->name }}</li>
                </ul> 
            </div>
        </div>
        <form action="{{ route('post.destroy', ['post' => $post->id]) }}" method="post">
            @csrf
            @method('DELETE')
            <input type="submit" value="Удалить" class="btn btn-danger">
        </form>
    </div>
    <a href="{{ route('home') }}">Назад в личный кабинет</a>
@endsection
