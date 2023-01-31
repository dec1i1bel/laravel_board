@extends('layouts.app')
@section('title', ' LBoard')

@section('header')
    @parent
@endsection

@section('content')

    <div class="card m-auto w-75">
        <div class="row g-0">
            <div class="col-md-4">
                @php
                    $post = $thisPost['post'];
                @endphp
                <div class="card-img-detail h-100" style="background-image: url('{{ Storage::url($post->img_preview) }}')">
                </div>
                <div class="row detail_photos--gallery">
                    @foreach ($thisPost['photos'] as $photo)
                        <div class="col-3 mt-2 h-100">
                            <a href="{{ Storage::url($photo->file) }}">
                                <div class="detail__photo w-100 h-100" style="background: url('{{ Storage::url($photo->file) }}')"></div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">{{ $post->title }}</h5>
                    <p class="card-text">{{ $post->content }}</p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Цена: {{ $post->price }} руб</li>
                    <li class="list-group-item">Категории:&nbsp;
                        @foreach ($thisPost['categories'] as $catName => $cat_id)
                           {{ $catName }} |
                        @endforeach
                    </li>
                    <li class="list-group-item">Продавец: {{ $post->user->name }}</li>
                    <li class="list-group-item">Телефон: {{ $post->user->phone }}</li>
                    <li class="list-group-item">Купить:
                        <form action="{{ route('cartAddPost', ['id' => $post->id]) }}"
                              method="post" class="form-inline d-flex">
                            @csrf
                            <input type="text" name="quantity" id="input-quantity" value="1"
                                   class="form-control mx-2" style="width: 50px">
                            <button type="submit" class="btn btn-success">Добавить в корзину</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

    </div>
    @if (count($otherPosts['posts']))
        <div id="detail__other-items--category" class="container">
            <h5>Другие товары тех же категорий</h5>
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
                @foreach ($otherPosts['posts'] as $post)
                <div class="col">
                        <a href="{{ route('detail', ['post' => $post->id]) }}" class="card-link">
                        <div class="card h-100">
                            <div class="col-md-4 w-100 card-img-detail card-img-list" style="background-image: url('{{ Storage::url($post->img_preview) }}')">
                            </div>
                            <div class="row list_photos--gallery">

                                @foreach ($otherPosts['photos'] as $photo)

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
                                <li class="list-group-item">
                                Категории:&nbsp;
                                @foreach ($otherPosts['categories'] as $cat)
                                    @if ($cat->post_id == $post->id)
                                       {{ $cat->name }} |
                                    @endif
                                @endforeach
                            </li>
                            </ul>
                        </div>
                    </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

@endsection
