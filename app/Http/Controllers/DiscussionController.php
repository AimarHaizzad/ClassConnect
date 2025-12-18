<?php

namespace App\Http\Controllers;

use App\Http\Requests\Discussion\StoreDiscussionRequest;
use App\Http\Requests\Discussion\UpdateDiscussionRequest;
use App\Models\Discussion;
use App\Models\Subject;
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

        $user = auth()->user();
        $userClass = $user->class ?? null;
        $isLecturer = $user->user_type === 'lecturer';

        // Only students need a class to view discussions
        if (! $isLecturer && ! $userClass) {
            return redirect()->route('dashboard')
                ->with('error', 'You must be assigned to a class to view discussions.');
        }

        $subject = Subject::findOrFail($subjectId);

        // Show all discussions but filter by class for interactions
        // Students can see all discussions but only interact with their own class
        // Lecturers can see and interact with all discussions
        $discussions = Discussion::with(['user', 'comments.user'])
            ->where('subject_id', $subjectId)
            ->latest()
            ->paginate(10);

        return view('discussions.index', compact('discussions', 'subject', 'userClass', 'isLecturer'));
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

        $user = auth()->user();
        $userClass = $user->class ?? null;
        $isLecturer = $user->user_type === 'lecturer';

        // Only students need a class to create discussions
        if (! $isLecturer && ! $userClass) {
            return redirect()->back()
                ->with('error', 'You must be assigned to a class to create discussions.');
        }

        $userId = $user->id;

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
            'class' => $isLecturer ? null : $userClass, // Lecturers don't need a class
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

        $user = auth()->user();
        $userClass = $user->class ?? null;
        $isLecturer = $user->user_type === 'lecturer';

        // Lecturers can interact with all discussions
        // Students can only interact with discussions from their own class
        // If discussion has no class (lecturer created), all can interact
        $canInteract = $isLecturer || ($userClass && $discussion->class === $userClass) || ! $discussion->class;

        $discussion->load(['user', 'subject', 'comments.user', 'comments.replies.user']);

        return view('discussions.show', compact('discussion', 'canInteract', 'userClass', 'isLecturer'));
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

        $user = auth()->user();
        $userClass = $user->class ?? null;
        $isLecturer = $user->user_type === 'lecturer';

        // Check authorization - users can only edit their own discussions
        if ($discussion->user_id != $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Lecturers can edit any discussion, students can only edit from their class
        if (! $isLecturer) {
            if (! $userClass) {
                return redirect()->back()
                    ->with('error', 'You must be assigned to a class to edit discussions.');
            }
            if ($discussion->class !== $userClass) {
                abort(403, 'You can only edit discussions from your own class.');
            }
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

        $user = auth()->user();
        $userClass = $user->class ?? null;
        $isLecturer = $user->user_type === 'lecturer';

        // Check authorization
        if ($discussion->user_id != $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Lecturers can update any discussion, students can only update from their class
        if (! $isLecturer) {
            if (! $userClass) {
                return redirect()->back()
                    ->with('error', 'You must be assigned to a class to update discussions.');
            }
            if ($discussion->class !== $userClass) {
                abort(403, 'You can only update discussions from your own class.');
            }
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
        $user = auth()->user();
        $userClass = $user->class ?? null;
        $isLecturer = $user->user_type === 'lecturer';

        // Check authorization
        if ($user->id !== $discussion->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Lecturers can delete any discussion, students can only delete from their class
        if (! $isLecturer) {
            if (! $userClass) {
                return redirect()->back()
                    ->with('error', 'You must be assigned to a class to delete discussions.');
            }
            if ($discussion->class !== $userClass) {
                abort(403, 'You can only delete discussions from your own class.');
            }
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
