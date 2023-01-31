@extends('layouts.app')
@section('title', 'Личный кабинет || LBoard')

@section('content')
<h1>Личный кабинет</h1>
<ul class="list-group mb-4" style="max-width: 300px;">
    <li class="list-group-item"><strong>Имя:</strong> {{ Auth::user()->name }}</li>
    <li class="list-group-item"><strong>Логин:</strong> {{ Auth::user()->login }}</li>
    <li class="list-group-item"><strong>Телефон:</strong> {{ Auth::user()->phone }}</li>
  </ul>
<h2>Мои объявления</h2>
<div class="table-responsive-lg">

    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Товар</th>
                <th scope="col">Категории</th>
                <th scope="col">Цена</th>
                <th scope="col">Основное фото</th>
                <th scope="col">Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
                <tr class="account_item__row">
                    <td>{{ $post->title }}</td>
                    <td>
                        @foreach ($categories as $cs)
                            @if ($cs->post_id == $post->id)
                                {{ $cs->name }} <br>
                            @endif
                        @endforeach
                    </td>
                    <td>{{ $post->price }}</td>
                    <td><div class="account_item__img--preview" style="background-image: url('{{ Storage::url($post->img_preview) }}')"></div></td>
                    <td>
                        <a href="{{ route('detail', ['post' => $post->id]) }}">
                            открыть
                        </a>&nbsp;|&nbsp;
                        <a href="{{ route('post.edit', ['post' => $post->id]) }}">
                            править
                        </a>&nbsp;|&nbsp;
                        <a href="{{ route('post.delete', ['post' => $post->id]) }}">
                            удалить
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
