<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Comments;
use App\Http\Controllers\Controller;

use App\Posts;
use Illuminate\Http\Request;

class CommentController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$input['from_user'] = $request->user()->id;
		$input['on_post'] = $request->input('on_post');
		$input['body'] = $request->input('body');
		$slug = $request->input('slug');
		Comments::create( $input );
		return redirect($slug)->with('message', 'Comment published');

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		$comment = Comments::find($id);
		$post = Posts::find($comment->on_post);

		$slug = $post->slug;


		if($comment && ($comment->from_user == $request->user()->id || $request->user()->is_admin() || $request->user()->is_moderator()))
		{
			$comment->delete();
			$data['message'] = 'Comment deleted Successfully';
		}
		else
		{
			$data['errors'] = 'Invalid Operation. You have not sufficient permissions';
		}
		return redirect('/'.$slug)->with($data);
	}

}
