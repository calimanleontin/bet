<?php
namespace App\Http\Controllers;
use App\Categories;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Posts;
use App\Http\Controllers\DB;
use Illuminate\Http\Request;
class UserController extends Controller {
	/*
     * Display active posts of a particular user
     *
     * @param int $id
     * @return view
     */
	public function user_posts(Request $request, $id)
	{
		//
		$posts = Posts::where('author_id',$id)->where('active',1)->orderBy('created_at','desc')->paginate(5);
		$title = User::find($id)->name;
		$id = $request->user()->id;
		$categories = Categories::where('author_id',$id)->get();
		return view('home')
			->withPosts($posts)
			->withTitlepost($title)
			->withTitlecategory('Categories')
			->withCategories($categories);
	}
	/*
     * Display all of the posts of a particular user
     *
     * @param Request $request
     * @return view
     */
	public function user_posts_all(Request $request)
	{
		//
		$user = $request->user();
		$posts = Posts::where('author_id',$user->id)->orderBy('created_at','desc')->paginate(5);
		$title = $user->name;
		$id = $request->user()->id;
		$categories = Categories::where('author_id',$id);
		return view('home')->withPosts($posts)->withTitle($title)
			->withTitlecategory('Categories')
			->withCategories($categories);;
	}
	/*
     * Display draft posts of a currently active user
     *
     * @param Request $request
     * @return view
     */
	public function user_posts_draft(Request $request)
	{
		//
		$user = $request->user();
		$posts = Posts::where('author_id',$user->id)->where('active',0)->orderBy('created_at','desc')->paginate(5);
		$title = $user->name;
		return view('home')->withPosts($posts)->withTitle($title);
	}

	public function showUsers(Request $request)
	{
		$users = User::all();
		$roles = ['author','moderator','subscriber'];
		return view('admin.edit')->withUsers($users)->withRoles($roles);
	}

	public function editUsers(Request $request)
	{
		$name = $request->input('author');
		$role = $request->input('role');
		if($request->user()->is_admin())
		{
			\DB::table('users')
				->where('name', $name)
				->update(array('role' => $role));
			$message = 'The user '.$name. ' is now  ' . $role;
			return redirect('/show-privileges')->withMessage($message);

		}
	}
	/**
	 * profile for user
	 */
	public function profile(Request $request, $id)
	{
		$data['user'] = User::find($id);
		if (!$data['user'])
			return redirect('/');
		if ($request -> user() && $data['user'] -> id == $request -> user() -> id) {
			$data['author'] = true;
		} else {
			$data['author'] = null;
		}
		$data['comments_count'] = $data['user'] -> comments -> count();
		$data['posts_count'] = $data['user'] -> posts -> count();
		$data['posts_active_count'] = $data['user'] -> posts -> where('active', '1') -> count();
		$data['posts_draft_count'] = $data['posts_count'] - $data['posts_active_count'];
		$data['latest_posts'] = $data['user'] -> posts -> where('active', '1') -> take(5);
		$data['latest_comments'] = $data['user'] -> comments -> take(5);
		return view('admin.profile', $data);
	}

}