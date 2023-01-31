@extends('layouts.app')
@section('title', 'Редактирование объявления || LBoard')

@section('content')
    <form action="{{ route('post.update', ['post' => $post->id]) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <h5>*Основные данные:</h5>
        <div class="form-group">
            <input class="form-control @error('title')
                is-invalid
            @enderror" type="text" placeholder="Post title" name="title" id="title" value="{{ old('title', $post->title) }}">
            @error('title')
                <span class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <textarea class="form-control @error('content')
                is-invalid
            @enderror" name="content" id="content" placeholder="Enter desciption" rows="10" cols="100">{{ old('content', $post->content) }}</textarea>
            @error('content')
                <span class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        Цена: <div class="form-group mb-3">
            <input class="form-control @error('price')
                is-invalid
            @enderror" type="number" name="price" id="price" value="{{ old('price', $post->price) }}">
            @error('price')
                <span class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group mb-3">
            <h5>Категории:</h5>
            @foreach ($categories_all as $cat)
                @php
                    $checked = ''
                @endphp
                @foreach ($categories_post as $cats_post)
                    @if ($cat->id == $cats_post->id)
                        @php
                            $checked = 'checked'
                        @endphp
                    @endif
                @endforeach
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="cat_{{  $cat->id }}" name="category[{{ $cat->id }}]" value="{{ $cat->id }}" {{ $checked }}>
                    <label class="form-check-label" for="cat_{{ $cat->id }}">{{ $cat->name }}</label>
                </div>
            @endforeach
        </div>
        <h5>Основное изображение:</h5>
        <div class="form-group">
            <input class="form-control @error('img_preview')
                is-invalid
            @enderror" type="file" name="img_preview" id="img_preview" value="{{ old('img_preview', $post->img_preview) }}">
            @error('img_preview')
                <span class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <h5>Доп. изображения:</h5>
        <div class="form-group">
            @for ($i = 1; $i <= 4; $i++)
                <input class="form-control @error('{photo_{{ $i }}')
                    is-invalid
                @enderror" type="file" name="photo_{{ $i }}" id="photo_{{ $i }}" value="{{ old('photo_'.$i) }}">
                @error('photo_{{ $i }}')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            @endfor
        </div>
        <input type="submit" value="Сохранить" class="btn btn-outline-success">
    </form>
    <a href="{{ route('home') }}">Назад в личный кабинет</a>
@endsection
