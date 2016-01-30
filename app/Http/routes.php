<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/',['as' => 'home', 'uses' => 'PostController@index']);
Route::get('/home',['as' => 'home', 'uses' => 'PostController@index']);
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
Route::group(['middleware' => ['auth']], function()
{
	// show new post form
	Route::get('new-post','PostController@create');

	// save new post
	Route::post('new-post','PostController@store');

	// edit post form
	Route::get('edit/{slug}','PostController@edit');

	//show new category form
	Route::get('new-category','CategoryController@create');

	//save new category
	Route::post('new-category','CategoryController@store');

	// update post
	Route::post('update/{slug}','PostController@update');

	//update category
	Route::post('category/update','CategoryController@update');

	// delete post
	Route::get('delete/{id}','PostController@destroy');

	// display user's all posts
	Route::get('my-all-posts','UserController@user_posts_all');

	// display user's drafts
	Route::get('my-drafts','UserController@user_posts_draft');

	//edit category
	Route::get('/category/edit/{slug}','CategoryController@edit');

	// add comment
	Route::post('comment/add','CommentController@store');

	// delete comment
	Route::get('/comment/delete/{id}','CommentController@destroy');

	//delete category
	Route::get('/category/delete/{id}','CategoryController@destroy');

	//edit privileges
	Route::post('/edit-privileges', 'UserController@editUsers');

	Route::get('/show-privileges', 'UserController@showUsers');

	Route::get('/search','PostController@search');

	Route::get('/sort','PostController@sort');

});
//users profile
Route::get('user/{id}','UserController@profile')->where('id', '[0-9]+');
// display list of posts
Route::get('user/{id}/posts','UserController@user_posts')->where('id', '[0-9]+');
// display single post
Route::get('/{slug}',['as' => 'post', 'uses' => 'PostController@show'])->where('slug', '[A-Za-z0-9-_]+');
//display all posts from a category
Route::get('/category/{slug}','CategoryController@show')->where('slug', '[A-Za-z0-9-_]+');
