<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\StoreCommentRequest;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request): RedirectResponse
    {
        $userId = auth()->id() ?? User::first()->id ?? 1;

        Comment::create([
            'discussion_id' => $request->discussion_id,
            'user_id' => $userId,
            'content' => $request->content,
        ]);

        return redirect()->back()
            ->with('success', 'Comment added successfully!');
    }
}
