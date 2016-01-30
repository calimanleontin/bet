@extends('app')
@section('title1')
	{{$titlecategory}}
@endsection
@section('content1')

	@if ( !$categories->count() )
		There are no categories.
	@else
		<div class="">
			@foreach( $categories as $category )
				<div class="list-group">
					<div class="list-group-item">
						<h3><a href="{{ url('/category/'.$category->slug) }}">{{ $category->title }}</a>
							@if(!Auth::guest() && Auth::user()->is_admin())
									<button class="btn" style="float: right"><a href="{{ url('category/edit/'.$category->slug)}}">Edit Category</a></button>
							@endif
						</h3>

				</div>
			@endforeach
		</div>
		</div>
	@endif
@endsection



@section('title')
	<p>
	<div class = 'left'>
		{{$titlepost}}
&nbsp;
		</div>

		<form method="get" action='{{ url("/sort") }}' role="form" class="form-inline mini">
			<div class="form-group">
				<select name = "criterion" class = 'form-control'>
					<option value = 'author_id'>Author</option>
					<option value = 'nr_comments'>Most Comments</option>
					<option value = 'visits'>Most Visited</option>
					<option value = 'created_at'>Date</option>
					<option value = 'title'>Title</option>
				</select>
			<select name="order" class="form-control">
					<option value="asc">ascending</option>
					<option value="desc">descending</option>
			</select>

				</div>
			<button type="submit" class="btn btn-default">Sort</button>
		</form>
	</p>
@endsection


	@section('content')

	@if ( !$posts->count() )
		There are no posts.
	@else
		<div class="">
			@foreach( $posts as $post )
				<div class="list-group">
					<div class="list-group-item">
						<h3><a href="{{ url('/'.$post->slug) }}">{{ $post->title }}</a>
							@if(!Auth::guest() && ($post->author_id == Auth::user()->id || Auth::user()->is_admin() || Auth::user()->is_moderator()))
								@if($post->active == '1')
									<button class="btn" style="float: right"><a href="{{ url('edit/'.$post->slug)}}">Edit Post</a></button>
								@else
									<button class="btn" style="float: right"><a href="{{ url('edit/'.$post->slug)}}">Edit Draft</a></button>
								@endif
							@endif
						</h3>
						<p>{{ $post->created_at->format('M d,Y \a\t h:i a') }} By <a href="{{ url('/user/'.$post->author_id)}}">{{ $post->author->name }}</a>
							visited
							@if($post->visits == 1)
									 one single time
							@else
									{{$post->visits}} times
							@endif
						</p>
					</div>
					<div class="list-group-item">
						<article>
							{!! str_limit($post->body, $limit = 1500, $end = '....... <a href='.url("/".$post->slug).'>Read More</a>') !!}
						</article>
					</div>
				</div>
			@endforeach
			@if(!empty($criterion))
				<?php echo $posts->appends(['criterion' => $criterion, 'order'=>$order])->render(); ?>
			@else
				{!! $posts->render() !!}
			@endif

		</div>
	@endif
@endsection