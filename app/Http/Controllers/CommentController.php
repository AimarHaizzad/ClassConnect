<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request): RedirectResponse
    {
        $userId = auth()->id() ?? User::first()->id ?? 1;

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $photoPath = $file->storeAs('comments', $filename, 'public');

            if (! Storage::disk('public')->exists($photoPath)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['photo' => 'Failed to upload photo. Please try again.']);
            }
        }

        Comment::create([
            'discussion_id' => $request->discussion_id,
            'user_id' => $userId,
            'content' => $request->content,
            'photo' => $photoPath,
        ]);

        return redirect()->back()
            ->with('success', 'Comment added successfully!');
    }

    public function edit(Comment $comment): View|RedirectResponse
    {
        $currentUserId = auth()->id() ?? User::first()->id ?? 1;

        if ($comment->user_id != $currentUserId) {
            abort(403, 'Unauthorized action.');
        }

        $comment->load(['discussion', 'user']);

        return view('comments.edit', compact('comment'));
    }

    public function update(UpdateCommentRequest $request, Comment $comment): RedirectResponse
    {
        $currentUserId = auth()->id() ?? User::first()->id ?? 1;

        if ($comment->user_id != $currentUserId) {
            abort(403, 'Unauthorized action.');
        }

        // Handle photo upload
        $photoPath = $comment->photo;
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }

            // Upload new photo
            $file = $request->file('photo');
            $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $photoPath = $file->storeAs('comments', $filename, 'public');
        }

        $comment->update([
            'content' => $request->content,
            'photo' => $photoPath,
        ]);

        return redirect()->route('discussions.show', $comment->discussion)
            ->with('success', 'Comment updated successfully!');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $currentUserId = auth()->id() ?? User::first()->id ?? 1;

        if ($comment->user_id != $currentUserId) {
            abort(403, 'Unauthorized action.');
        }

        $discussion = $comment->discussion;

        // Delete associated photo if exists
        if ($comment->photo && Storage::disk('public')->exists($comment->photo)) {
            Storage::disk('public')->delete($comment->photo);
        }

        $comment->delete();

        return redirect()->route('discussions.show', $discussion)
            ->with('success', 'Comment deleted successfully!');
    }
}
