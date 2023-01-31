@extends('layouts.app')
@section('title', 'Главная || LBoard')

@section('header')
    @parent
@endsection

@section('content')

<div class="list-group mb-3">
    @foreach ($categories_top as $category)
        <a href="{{ route('category_items_list', ['category' => $category->id]) }}" class="list-group-item list-group-item-action">{{ $category->name }} <span class="badge bg-primary">{{ $category->count_posts }}</span></a>
    @endforeach
</div>

<div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">

    @if (count($posts) > 0)
    
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
                        <li class="list-group-item">
                        Категории:&nbsp; 
                        @foreach ($categories_posts as $k => $cat)
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

    @endif
    
</div>
  
@endsection

@section('footer')
    @parent
@endsection