<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class SettingsController extends Controller
{
    public function index()
    {
        return view('author.settings');
    }
    public function profileUpdate(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'image' => 'required|image',
        ]);
        $image = $request->file('image');
        $slug = Str::slug($request->name);
        $user = User::findOrFail(Auth::id());
        if (isset($image)) {
            $currentDate = Carbon::now()->toDateString();
            $imageName = $slug . '-' . $currentDate . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
            if(!file_exists('public/storage/profile/')) {
                mkdir('public/storage/profile/',0777,true);
            }
            //delete old image
             if(file_exists('public/storage/post/'.$user->image)) {
                unlink('public/storage/post/' . $user
                    ->image);
            }
            $profileImage = Image::make($image)->resize(500, 500)->save($imageName);
            $profileImage->move('public/storage/post/',$imageName);
        } else {
            $imageName = $user->image;
        }
        $user->name = $request->name;
        $user->image = $imageName;
        $user->email = $request->email;
        $user->about = $request->about;
        $user->save();
        Toastr::success('Profile updated successfully!', 'Success');
        return redirect()->back();
    }
    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|confirmed',
        ]);
        $CurrentPassword = Auth::user()->password;

        if (Hash::check($request->old_password, $CurrentPassword)) {

            if (!Hash::check($request->password, $CurrentPassword)) {

                $user = User::find(Auth::id());
                $user->password = Hash::make($request->password);
                $user->save();
                Toastr::success('Password changed successfully!', 'Success');
                Auth::logout();
                return redirect()->back();
            } else {
                Toastr::error('new password should not same as new password !', 'Error');
                return redirect()->back();
            }
        } else {
            Toastr::error('current password does not match !', 'Error');
            return redirect()->back();
        }
    }
}
