@extends('app')
@section('title')
    Edit Post
@endsection
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>tinymce.init({ selector:'textarea' });</script>

@section('content')
    <form method="post" action='{{ url("/update/".$post->slug) }}'>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="post_id" value="{{ $post->id }}{{ old('post_id') }}">
        <div class="form-group">
            <input required="required" placeholder="Enter title here" type="text" name = "title" class="form-control" value="@if(!old('title')){{$post->title}}@endif{{ old('title') }}"/>
        </div>
        <div class="form-group">
        <textarea name='body'class="form-control">
          @if(!old('body'))
                {!! $post->body !!}
            @endif
            {!! old('body') !!}
        </textarea>
        <div class="form-group">
            @foreach($categories as $category)
                <input type="radio" name="category" value={{$category->id}}>{{$category->title}}<br>
            @endforeach
        </div>
        </div>
        @if($post->active == '1')
            <input type="submit" name='publish' class="btn btn-success" value = "Update"/>
        @else
            <input type="submit" name='publish' class="btn btn-success" value = "Publish"/>
        @endif
        <input type="submit" name='save' class="btn btn-default" value = "Save As Draft" />
        <a href="{{  url('delete/'.$post->id.'?_token='.csrf_token()) }}" class="btn btn-danger">Delete</a>
    </form>
@endsection