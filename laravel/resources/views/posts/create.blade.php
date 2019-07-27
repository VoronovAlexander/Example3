@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Создание нового объявления</div>

                <div class="card-body">
                    
                    <form action="{{ route('posts.store') }}" method="post">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Название объявления</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="content" class="col-md-4 col-form-label text-md-right">Содержание</label>

                            <div class="col-md-6">
                                <textarea class="form-control @error('content') is-invalid @enderror" name="content" id="content" cols="30" rows="10">{{ old('content')}}</textarea>


                                @error('content')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <button class="btn btn-success">Отправить</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
