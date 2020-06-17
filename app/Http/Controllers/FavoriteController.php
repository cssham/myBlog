<?php

namespace App\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class FavoriteController extends Controller
{
    public function add($post){
        $user = Auth::user();
        $isFavorite = $user->userFavoritePost()->where('post_id',$post)->count();
        if($isFavorite == 0){
            $user->userFavoritePost()->attach($post);
            Toastr::success('Post successfully added to your favorite list !','Success');
            return redirect()->back();
        }
        else{
            $user->userFavoritePost()->detach($post);
            Toastr::success('Post successfully removed from your favorite list !','Success');
            return redirect()->back();
        }
   }
}
