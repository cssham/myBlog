<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Post;
use App\Tag;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
   public function index(){
       $posts = Post::all();
       $favoritePosts = Post::withCount('comments')
                            ->withCount('favoritePostUser')
                            ->orderBy('view_count','desc')
                            ->orderBy('comments_count','desc')
                            ->orderBy('favorite_Post_User_count','desc')
                            ->take(5)->get();
        $totalPendingPosts = Post::where('is_approved',false)->count();
        $allViews = Post::sum('view_count');
        $totalAuthors =User::where('role_id',2)->count();
        $newAuthorsToday =User::where('role_id',2)
                                ->where('created_at',Carbon::today())->count();
        $activeAuthor =User::where('role_id',2)
                            ->withCount('posts')
                            ->withCount('comments')
                            ->withCount('userFavoritePost')
                            ->orderBy('posts_count','desc')
                            ->orderBy('comments_count','desc')
                            ->orderBy('user_Favorite_Post_count','desc')->take(10)->get();
        $categoryCount =Category::all()->count();
        $tagCount =Tag::all()->count();
       return view('admin.dashboard',compact('posts','favoritePosts','totalPendingPosts','allViews','totalAuthors','newAuthorsToday','activeAuthor','categoryCount','tagCount'));
   }
}
