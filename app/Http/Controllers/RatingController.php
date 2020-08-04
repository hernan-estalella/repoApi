<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Rating;
use App\Http\Resources\RatingResource;

class RatingController extends Controller
{
    public function store(Request $request, Post $post)
    {
      $rating = Rating::firstOrCreate(
        [
          'user_id' => $request->user()->id,
          'post_id' => $post->id,
        ],
        ['rating' => $request->rating]
      );

      return new RatingResource($rating);
    }
}
