<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(){
        $authors= User::authors()
        ->withCount('posts')
        ->withCount('comments')
        ->withCount('userFavoritePost')
        ->get();

        return view('admin.authors',compact('authors'));
    }
    public function destroy($id){
        User::findOrFail($id)->delete();
        Toastr::success('Author deleted successfully','Success');
        return redirect()->back();
    }
}
