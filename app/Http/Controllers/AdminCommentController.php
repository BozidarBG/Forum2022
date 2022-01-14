<?php

namespace App\Http\Controllers;

use App\Models\Comment;

class AdminCommentController extends Controller
{

    public function index()
    {
        $comments=Comment::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.comments.index', compact('comments'));
    }



    public function destroy(Comment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Comment deleted!');

    }
}
