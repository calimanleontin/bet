@extends('app')
@section('title')
    Add New Category
@endsection
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>tinymce.init({ selector:'textarea' });</script>

@section('content')
    <form action="/new-category" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <input required="required" value="{{ old('title') }}" placeholder="Enter name here" type="text" name = "title"class="form-control" />
        </div>
        <div class="form-group">
            <textarea name='description'class="form-control">{{ old('description') }}</textarea>
        </div>

        <input type="submit" name='publish' class="btn btn-success" value = "Publish"/>
    </form>
@endsection