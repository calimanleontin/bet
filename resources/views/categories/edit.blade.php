@extends('app')
@section('title')
    Edit Category
@endsection
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>tinymce.init({ selector:'textarea' });</script>

@section('content')
    <form method="post" action='{{ url("/category/update") }}'>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="category_id" value="{{ $category->id }}{{ old('category_id') }}">
        <div class="form-group">
            <input required="required" placeholder="Enter title here" type="text" name = "title" class="form-control" value="@if(!old('title')){{$category->title}}@endif{{ old('title') }}"/>
        </div>
        <div class="form-group">
    <textarea name='description'class="form-control">
      @if(!old('description'))
            {!! $category->description !!}
        @endif
    </textarea>
        </div>
            <input type="submit" name='publish' class="btn btn-success" value = "Update"/>

        <a href="{{  url('/category/delete/'.$category->id)}}" class="btn btn-danger">Delete</a>
    </form>
@endsection