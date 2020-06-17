<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $posts =$user->posts;

        $popularPosts = $user->posts()
                        ->withCount('comments')
                        ->withCount('favoritePostUser')
                        ->orderBy('view_count','desc')
                        ->orderBy('comments_count')
                        ->orderBy('favorite_Post_User_count')
                        ->take(5)->get();

        $pendingPost = $posts->where('is_approved',false)->count();
        $publishedPost =$posts->where('status',true)->count();
        $allViews = $posts->sum('view_count');
        return view('author.dashboard',compact('user','posts','popularPosts','pendingPost','allViews', 'publishedPost'));
        //return view('author.dashboard');
    }
}
