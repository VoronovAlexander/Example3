@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <span>Объявления</span>
                    <div>
                        <a target="_blank" href="{{ route('posts.download') }}">Скачать все в csv</a>
                    </div>
                </div>

                <div class="card-body">
                    
                    <table>
                        <thead>
                            <th>Название</th>
                            <th>Содержание</th>
                            <th>Действия</th>
                        </thead>
                        <tbody>
                        @foreach ($posts as $post)
                        <tr>
                            <td>
                                {{ $post->name }}
                            </td>
                            <td>
                                {{ $post->content }}
                            </td>
                            <td>
                                <a href="{{route('posts.show', [$post])}}">Подробнее</a>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    
                    <div class="mt-3">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
