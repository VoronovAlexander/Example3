@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Объявление "{{ $post->name }}"</div>

                <div class="card-body">
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            Название:
                        </div>
                        <div class="col-md-8">
                            {{$post->name}}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            Содержание:
                        </div>
                        <div class="col-md-8">
                            {{$post->content}}
                        </div>
                    </div>

                    <a href="{{ url()->previous() }}" class="btn btn-success">Назад</a>
                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
