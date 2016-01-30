<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\PostFormRequest;
use App\Categories;
use App\Posts;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class PostController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$categories = Categories::all();
		$posts = Posts::where('active',1)->orderBy('created_at', 'desc')->paginate(5);
		$titlepost = 'Lates news';
		$titlecategory = 'All the categories';
		return view('home')->withPosts($posts)->withTitlepost($titlepost)->withTitlecategory($titlecategory)->withCategories($categories);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		$categories = Categories::all();
		if($request->user()->can_post())
			return view('posts.create')->withCategories($categories);
		return redirect('/')->withErrors('You have not sufficient permissions to make a new article; ');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(PostFormRequest $request)
	{
		$title = $request->get('title');
		$duplicate = \DB::table('posts')->where('title',$title)->get();
//		var_dump($duplicate);
//		die();
		if($duplicate != null)
			return redirect ('/new-post')->withErrors('A post with this title already exists');
		$post = new Posts();
		$post->title = $request->get('title');
		$post->body = $request->get('body');
		$post->slug = str_slug($post->title);
		$post->author_id = $request->user()->id;
		$category_id = $request->get('category');
//		var_dump($category_id);
//		die();

		$post->category_id = $category_id;
		if($request->has('save'))
		{
			$post->active = 0;
			$message = 'Post saved successfully';
		}
		else
		{
			$post->active = 1;
			$message = 'Post published successfully';
		}
		$post->save();
		return redirect('edit/'.$post->slug)->withMessage($message);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($slug)
	{
		$post = Posts::where('slug',$slug)->first();
		$post->visits =$post->visits + 1;
		$post->save();
		if(!$post)
		{
			return redirect('/')->withErrors('requested page not found');
		}
		$comments = $post->comments;
		return view('posts.show')->withPost($post)->withComments($comments);

	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request, $slug)
	{
		$categories = Categories::all();
		$post = Posts::where('slug',$slug)->first();
		if($post && ($request->user()->id == $post->author_id || $request->user()->is_admin() || $request->user()->is_moderator()))
			return view('posts.edit')->with('post',$post)->withCategories($categories);
		return redirect('/')->withErrors('you have not sufficient permissions');

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $slug)
	{
		$post_id = $request->input('post_id');
		$post = Posts::find($post_id);
		if($request->has('category'))
		{
			$category_id = $request->get('category');
//			var_dump($category_slug);
//			die();
			$post->category_id = $category_id;
		}

		if($post && ($post->author_id == $request->user()->id || $request->user()->is_admin() || $request->user()->is_moderator()))
		{
			$title = $request->input('title');
			$slug = str_slug($title);
			$duplicate = Posts::where('slug',$slug)->first();
			if($duplicate)
			{
				if($duplicate->id != $post_id)
				{
					return redirect('edit/'.$post->slug)->withErrors('Title already exists.')->withInput();
				}
				else
				{
					$post->slug = $slug;
				}
			}
			$post->title = $title;
			$post->body = $request->input('body');
			if($request->has('save'))
			{
				$post->active = 0;
				$message = 'Post saved successfully';
				$landing = 'edit/'.$post->slug;
			}
			else {
				$post->active = 1;
				$message = 'Post updated successfully';
				$landing = $post->slug;
			}

			$post->save();
			return redirect($landing)->withMessage($message);
		}
		else
		{
			return redirect('/')->withErrors('you have not sufficient permissions');
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		$post = Posts::find($id);
		if($post && ($post->author_id == $request->user()->id || $request->user()->is_admin()))
		{
			$post->delete();
			$data['message'] = 'Post deleted Successfully';
		}
		else
		{
			$data['errors'] = 'Invalid Operation. You have not sufficient permissions';
		}
		return redirect('/')->with($data);
	}



	public function search(Request $request)
	{
		$q = $request->get('q');
		$categories = Categories::where('title','LIKE','%'.$q.'%')->get();
		$posts = Posts::where('title','LIKE','%'.$q.'%')
			->where('active',1)
			->orderBy('created_at','desc')
			->paginate(5);

		$titlecategory = 'Categories you searched for';
		$titlepost = 'Articles you searched for';
//		var_dump($categories->first()->title);
//		die();
		return view('home')
			->withPosts($posts)
			->withTitlepost($titlepost)
			->withTitlecategory($titlecategory)
			->withCategories($categories);


	}

	public function sort(Request $request)
	{
		$criterion = $request->get('criterion');
		$order = $request->get('order');
		$posts = Posts::where('active',1)->orderBy($criterion,$order)->paginate(5);
		$titlepost = 'News';
		$titlecategory='Categories';
		$categories = Categories::all();
		return view('home')
			->withPosts($posts)
			->withCategories($categories)
			->withTitlepost($titlepost)
			->withTitlecategory($titlecategory)
			->withCriterion($criterion)
			->withOrder($order);
//		var_dump($posts[0]->title);
//		die();


	}
}
