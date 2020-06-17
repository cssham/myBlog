<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PostController extends Controller
{
    public function index(){
        $posts = Post::latest()->approved()->published()->paginate(12);
        return view('posts',compact('posts'));
    }


    public function details($slug){
         $post = Post::where('slug',$slug)->approved()->published()->first();
         $id = $post->id;

        $blogKey = 'blog_'.$id;
        if(!Session::has($blogKey)){
           $post->increment('view_count');
            Session::put($blogKey,1);
        }
        $randomPosts = Post::approved()->published()->take(3)->inRandomOrder()->get();
        return view('post',compact('post','randomPosts'));
    }
    public function postByCategory($slug){
        $category = Category::where('slug',$slug)->first();
        $posts = $category->posts()->approved()->published()->get();
        return view('postsByCategory',compact('category','posts'));
    }
    public function postByTag($slug)
    {
        $tag = Tag::where('slug', $slug)->first();
        $posts = $tag->posts()->approved()->published()->get();
        return view('postsByTag', compact('tag','posts'));
    }
}
