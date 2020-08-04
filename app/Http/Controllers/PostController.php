<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PostResource::collection(Post::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validateInput($request);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $post = Post::create([
            'user_id' => $request->user()->id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'content' => $request->content
          ]);
    
          return new PostResource($post);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostValidator $request, Post $post)
    {
        if ($request->user()->id !== $post->user_id) {
            return response()->json(['error' => 'No puede editar un post que no es de su propiedad'], 403);
        }

        $validator = $this->validateInput($request);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $post->update($request->only('title', 'content', 'category_id'));


        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json(null, 204);
    }

    private function validateInput(Request $request)
    {
        $rules = [
            'title' => 'required|unique:posts,title',
            'content' => 'required',
            'category_id' => 'required',
        ];

        $messages = [
            'title.required' => 'Debe ingresar el título del post',
            'title.unique' => 'El título ya existe',
            'content.required' => 'El contenido del post no puede estar vacio',
            'category_id.required' => 'Seleccione la categoría del post'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        return $validator;
    }
}  