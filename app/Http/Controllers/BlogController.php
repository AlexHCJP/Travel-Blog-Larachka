<?php

namespace App\Http\Controllers;

use App\blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'body' => 'required',
            'city_id' => 'exists:cities,id',
        ]);

        $blog = auth()->user()->blog()->create($data);
        return $blog;
    }

    public function show(blog $blog)
    {
        $data = [];
        $data['blog'] = $blog;
        $data['user'] = $blog->user()->get(['id','name']);
        $data['comments'] = $blog->comments()->with(['user' => function($query){
            $query->select('id', 'name');
        }])->orderByDesc('created_at')->get(['created_at', 'text', 'user_id']);
        return $data;
    }

    public function update(Request $request, blog $blog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(blog $blog)
    {
        //
    }
}
