<?php

class PostController extends \BaseController {

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
	public function store()
	{
		//
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
	public function destroy($id)
	{
		//
	}



	 public function listPost()
    {
        $posts = Post::orderBy('id','desc')->paginate(10);
        $this->layout->title = 'Post listings';
        $this->layout->main = View::make('dash')->nest('content','posts.list',compact('posts'));
    }

    public function showPost(Post $post){
    	$comments = $post->comments()->where('approved','=',1)->get();
    	$this->layout->title=$post->title;
    	$this->layout->main = View::make('home')->nest('content','posts.single', compact('post'));
    }

    public function newPost(){
    	$this->layout->title = 'New Post';
    	$this->layout->main = View::make('dash')->nest('content','posts.new');
    }

    public function editPost(Post $post){
    	$this->layout->title = 'Edit Post';
    	$this->layout->main=View::make('dash')->nest('content','post.edit', compact('post'));
    }

    public function deletePost(Post $post){
    	$post->delete();
    	return Redirect::route('post.list')->with('success', 'Post is deleted!');
    }

    public function savePost(){
    	$post = [
    		'title' => Input::get('title'),
    		'content' => Input::get('content'),
    	];

    	$rules =[
    		'title' => 'required',
    		'content' => 'required',
    	];

    	$valid = Validator::make($post, $rules);
    	if ($valid->passes()){
    		$post = new Post($post);
    		$post->comment_count = 0;
    		$post->read_more=(strlen($post->content) > 120) ? substr($post->content, 0, 120) : $post->content;
    		$post->save();
    		return Redirect::to('admin/dash-board')->with('success', 'Post is saved!');
    	} else
    		return Redirect::back()->withErrors($valid)->withInput();

    }


    public function updatePost(Post $post){

    	$data = [
    		'title' => Input::get('title'),
    		'content' => Input::get('content'),
    	];

    	$rules = [
    		'title' => 'required',
    		'content' => 'required',

    	];

    	$valid = Validator::make($data, $rules);
    	if ($valid->passes()){
    		$post->title = $data['title'];
    		$post->content = $data['content'];
    		$post->read_more = (strlen($post->content) > 120) ? substr($post->content, 0,120) : $post->content;
    		if (count($post->getDirty()) > 0) {
    			$post->save();
    			return Redirect::back()->with('success', 'Post is updated!');

    		} else

    			return Redirect::back()->with('success', 'Nothing to update');

    		} else
    			return Redirect::back()->withErrors($valid)->withInput();

    	}
    }
}
