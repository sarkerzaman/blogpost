<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Post;
use App\Http\Resources\Post as PostResource;
use Illuminate\Http\Resources\Json\Resource;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Get Post
        $posts = Post::orderBy('created_at','desc')->paginate(5);

        //Return collection of articles as a resource
        return PostResource::collection($posts);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //save post
        $post = $request->isMethod('put')?Post::FindOrFail($request->post_id):new Post;

        $post->id = $request->input('post_id');
        $post->title = $request->input('title');
        $post->body = $request->input('body');

        if($post->save()){
            return new PostResource($post);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Get Single Post
        $post = Post::FindOrFail($id);

        return new PostResource($post);
    }   

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ///Delete a Post
        $post = Post::FindOrFail($id);

        if($post->delete()){
            return new PostResource($post);
        }
    }
}
