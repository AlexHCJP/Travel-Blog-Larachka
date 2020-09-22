<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'text' => 'required',
            'blog_id' => 'exists:blogs,id'
        ]);
        $comment = auth()->user()->comment()->create($data);
        $user = $comment->user()->get(['id', 'name']);
        $output = $comment;
        $output->user = $user;
        return $output;
    }

    public function update(Request $request, Comment $comment)
    {
        //
    }

    public function destroy(Comment $comment)
    {
        //
    }
}
