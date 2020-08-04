<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'post_id', 'user_id', 'rating'
    ];

    public function post()
    {
        return $this->belongsTo('App\Post');
    }
}
