@extends('layouts.app')
@section('title', 'Товары категории || LBoard')

@php
    $phrase_new = 'по новизне';
    $phrase_price = 'по цене';

    if ($sort == 'created_at') {
        $phrase_new = (($order == 'asc') ? 'сначала старые' : 'сначала новые');
    }
    if ($sort == 'price') {
        $phrase_price = (($order == 'asc') ? 'сначала дешёвые' : 'сначала дорогие');
    }
@endphp

@section('content')
@if ($posts->count() > 0)
    <div class="row ">
        <div class="col-8">
            <h1>Категория: {{ $category->name }}</h1>
        </div>
        <div class="col-4">
            <div class="row">
                <div class="col-6 d-flex justify-content-end">
                    <div class="dropdown">
                        <button class="btn btn-outline-success dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $phrase_new }}
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                          <li><a class="dropdown-item" href="?sort=created_at&order=desc">сначала новые</a></li>
                          <li><a class="dropdown-item" href="?sort=created_at&order=asc">сначала старые</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <div class="dropdown">
                        <button class="btn btn-outline-success dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $phrase_price }}
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                          <li><a class="dropdown-item" href="?sort=price&order=desc">сначала дорогие</a></li>
                          <li><a class="dropdown-item" href="?sort=price&order=asc">сначала дешёвые</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
        @foreach ($posts as $post)
            <div class="col">
                <a href="{{ route('detail', ['post' => $post->id]) }}" class="card-link">
                <div class="card h-100">
                    <div class="col-md-4 w-100 card-img-detail card-img-list" style="background-image: url('{{ Storage::url($post->img_preview) }}')">
                    </div>
                    <div class="row list_photos--gallery">
                        @foreach ($photos as $photo)
                            @if ($photo->post_id == $post->id)
                            <div class="col-3 mt-2 h-100">
                                <a href="{{ Storage::url($photo->file) }}">
                                    <div class="detail__photo w-100 h-100" style="background: url('{{ Storage::url($photo->file) }}')"></div>
                                </a>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p class="card-text">{{ $post->content }}</p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Цена: {{ $post->price }} руб.</li>
                        <li class="list-group-item">Категории:&nbsp;
                            @foreach ($categories as $cat)
                                @if ($cat->post_id == $post->id)
                                    {{ $cat->name }}<br>
                                @endif
                            @endforeach
                        </li>
                    </ul>
                </div>
            </a>
            </div>
        @endforeach
        {{ $posts->links() }}
    </div>
    @else
        <p class="text-danger">В категории нет объявлений</p>
    @endif
@endsection
