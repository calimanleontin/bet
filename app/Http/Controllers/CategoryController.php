<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Categories;
use App\Http\Controllers\Controller;

use App\Posts;
use Illuminate\Http\Request;

class CategoryController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($id)
	{
		$posts = Posts::where('active',1)->where('category_id', $id)->orderBy('created_at', 'desc')->paginate(5);
		$title = 'Lates news';
		return view('home')->withPosts($posts)->withTitle($title);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		if($request->user()->can_create_category())
			return view('categories.create');
		return redirect('/')->withErrors('You have not sufficient permissions to make a new category; ');

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$category = new Categories();
		$category->title = $request->get('title');
		$category->description = $request->get('description');
		$category->slug = str_slug($category->title);
		$message = 'New category added';
		$user_id = $request->user()->id;
		$category->author_id = $user_id;
		$category->save();
		return redirect('/')->withMessage($message);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $slug)
	{
		$category = Categories::where('slug',$slug)->first();
		$posts = Posts::where('category_id',$category->id)->orderBy('created_at','desc')->paginate(5);
		$title= 'Posts from category '.$category->title;
		return view('categories.show')->withPosts($posts)->withCategory($category)->withTitle($title);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request, $slug)
	{
		$category = Categories::where('slug',$slug)->first();
		if($category &&$request->user()->can_create_category())
			return view('categories.edit')->with('category',$category);
		return redirect('/');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request)
	{
		$category_id = $request->input('category_id');
		$category = Categories::find($category_id);
		if ($category && $request->user()->is_admin()) {
			$title = $request->input('title');
			$slug = str_slug($title);
			$duplicate = Categories::where('slug', $slug)->first();
			if ($duplicate) {
				if ($duplicate->id != $category_id) {
					return redirect('category/edit/' . $category->slug)->withErrors('Title already exists.')->withInput();
				} else {
					$category->slug = $slug;
				}
			}

			$category->title = $title;
			$category->description = $request->input('description');
			$message = 'Post updated successfully';
			$landing = $category->slug;
			$category->save();
			$category->slug = str_slug($category->title);
			$category->save();
			return redirect('/category/edit/' . $landing)->withMessage($message);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$category = Categories::where('id', $id);
		$category->delete();
		return redirect('/')->withMessage('Category deleted successfully');
	}

}
