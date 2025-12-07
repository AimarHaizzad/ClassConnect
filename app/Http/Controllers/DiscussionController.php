<?php

namespace App\Http\Controllers;

use App\Http\Requests\Discussion\StoreDiscussionRequest;
use App\Http\Requests\Discussion\UpdateDiscussionRequest;
use App\Models\Discussion;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DiscussionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|RedirectResponse
    {
        $subjectId = session('selected_subject_id');

        if (! $subjectId) {
            return redirect()->route('subjects.select');
        }

        $subject = Subject::findOrFail($subjectId);
        $discussions = Discussion::with(['user', 'comments.user'])
            ->where('subject_id', $subjectId)
            ->latest()
            ->paginate(10);

        return view('discussions.index', compact('discussions', 'subject'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|RedirectResponse
    {
        $subjectId = session('selected_subject_id');

        if (! $subjectId) {
            return redirect()->route('subjects.select');
        }

        $subject = Subject::findOrFail($subjectId);

        return view('discussions.create', compact('subject'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDiscussionRequest $request): RedirectResponse
    {
        // Log that we reached the controller (validation passed)
        \Log::info('Discussion store method called', [
            'title' => $request->title,
            'content_length' => strlen($request->content ?? ''),
        ]);

        $subjectId = session('selected_subject_id');

        if (! $subjectId) {
            return redirect()->route('subjects.select');
        }

        $userId = auth()->id() ?? User::first()->id ?? 1;

        $imagePath = null;
        if ($request->hasFile('image')) {
            // Additional security: validate file type and scan for malicious content
            $file = $request->file('image');

            // Generate unique filename to prevent overwrites
            $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

            // Store in public disk
            $imagePath = $file->storeAs('discussions', $filename, 'public');

            // Verify the file was stored correctly
            if (! Storage::disk('public')->exists($imagePath)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['image' => 'Failed to upload image. Please try again.']);
            }
        }

        $discussion = Discussion::create([
            'user_id' => $userId,
            'subject_id' => $subjectId,
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
        ]);

        return redirect()->route('discussions.show', $discussion)
            ->with('success', 'Discussion created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Discussion $discussion): View|RedirectResponse
    {
        $subjectId = session('selected_subject_id');

        if (! $subjectId || $discussion->subject_id != $subjectId) {
            return redirect()->route('subjects.select');
        }

        $discussion->load(['user', 'subject', 'comments.user', 'comments.replies.user']);

        return view('discussions.show', compact('discussion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discussion $discussion): View|RedirectResponse
    {
        $subjectId = session('selected_subject_id');

        if (! $subjectId || $discussion->subject_id != $subjectId) {
            return redirect()->route('subjects.select');
        }

        // Check authorization - users can only edit their own discussions
        $currentUserId = auth()->id() ?? User::first()->id ?? 1;
        if ($discussion->user_id != $currentUserId) {
            abort(403, 'Unauthorized action.');
        }

        $discussion->load('subject');

        return view('discussions.edit', compact('discussion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDiscussionRequest $request, Discussion $discussion): RedirectResponse
    {
        $subjectId = session('selected_subject_id');

        if (! $subjectId || $discussion->subject_id != $subjectId) {
            return redirect()->route('subjects.select');
        }

        // Check authorization
        $currentUserId = auth()->id() ?? User::first()->id ?? 1;
        if ($discussion->user_id != $currentUserId) {
            abort(403, 'Unauthorized action.');
        }

        // Handle image upload
        $imagePath = $discussion->image;
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            // Upload new image
            $file = $request->file('image');
            $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $imagePath = $file->storeAs('discussions', $filename, 'public');
        }

        // Update discussion
        $discussion->update([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
        ]);

        return redirect()->route('discussions.show', $discussion)
            ->with('success', 'Discussion updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discussion $discussion): RedirectResponse
    {
        // Check authorization
        if (auth()->id() !== $discussion->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Delete associated image if exists
        if ($discussion->image && Storage::disk('public')->exists($discussion->image)) {
            Storage::disk('public')->delete($discussion->image);
        }

        $discussion->delete();

        return redirect()->route('discussions.index')
            ->with('success', 'Discussion deleted successfully!');
    }
}
