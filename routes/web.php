<?php

use App\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/','HomeController@index')->name('home');

Route::get('post/{slug}','PostController@details')->name('post.details');
Route::get('posts','PostController@index')->name('post.index');
Route::get('category/{slug}','PostController@postByCategory')->name('category.posts');
Route::get('tag/{slug}', 'PostController@postByTag')->name('tag.posts');

Route::post('subscriber','SubscriberController@store')->name('subscriber.store');

Route::get('search','SearchController@search')->name('search');

Route::get('profile/{username}','AuthorController@profile')->name('author.profile');

Auth::routes();

Route::group(['middleware'=> ['auth']], function () {
    Route::post('favorite/{post}/add','FavoriteController@add')->name('post.favorite');
    Route::post('comment/{post}','CommentController@store')->name('comment.store');
});

//AdminRoute
Route::group(['as'=>'admin.','prefix' => 'admin','namespace'=>'Admin','middleware'=>['auth','admin']], function () {
    Route::get('/dashboard','DashboardController@index')->name('dashboard');
    Route::resource('tag', 'TagController');
    Route::resource('category', 'CategoryController');
    Route::resource('post', 'PostController');

    Route::get('pending/post', 'PostController@pending')->name('post.pending');
    Route::put('post/{id}/approve', 'PostController@approval')->name('post.approve');

    Route::get('settings','SettingsController@index')->name('settings');
    Route::put('profile-update','SettingsController@profileUpdate')->name('profile.update');
    Route::put('password-update', 'SettingsController@passwordUpdate')->name('password.update');

    Route::get('/favorite', 'FavoriteController@index')->name('favorite.index');

    Route::get('comments/','CommentController@index')->name('comments.index');
    Route::delete('comments/{id}','CommentController@destroy')->name('comments.destroy');

    Route::get('/subscriber','SubscriberController@index')->name('subscriber.index');
    Route::delete('/subscriber/{subscriber}', 'SubscriberController@destroy')->name('subscriber.destroy');

    Route::get('authors','AuthorController@index')->name('authors.index');
    Route::delete('authors/{id}','AuthorController@destroy')->name('authors.destroy');
});
//AuthorRoute
Route::group(['as' => 'author.', 'prefix' => 'author', 'namespace' => 'author', 'middleware' => ['auth', 'author']], function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    Route::get('settings', 'SettingsController@index')->name('settings');
    Route::put('profile-update', 'SettingsController@profileUpdate')->name('profile.update');
    Route::put('password-update', 'SettingsController@passwordUpdate')->name('password.update');

    Route::resource('post', 'PostController');

    Route::get('comments/', 'CommentController@index')->name('comments.index');
    Route::delete('comments/{id}', 'CommentController@destroy')->name('comments.destroy');

    Route::get('/favorite', 'FavoriteController@index')->name('favorite.index');
});
view()->composer('name', function ($view) {

});
View::composer('layouts.frontend.partial.footer',function($view){

    $categories = Category::all();
    $view->with('categories',$categories);
});

