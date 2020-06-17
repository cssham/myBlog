<?php

namespace App;

use Facade\Ignition\QueryRecorder\Query;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function user()
    {
       return $this->belongsTo('App\User');
    }
    public function categories()
    {
        return $this->belongsToMany('App\Category', 'category_post')->withTimestamps();
    }
    public function tags(){
        return $this->belongsToMany('App\Tag', 'post_tag')->withTimestamps();
    }
    public function favoritePostUser(){
            return $this->belongsToMany('App\User')->withTimestamps();
    }
    public function comments(){
        return $this->hasMany('App\Comment');
    }
    public function scopeApproved($query)
    {
        return $query->where('is_approved', 1);
    }
    public function scopePublished($query)
    {
        return $query->where('status', 1);
    }
}
