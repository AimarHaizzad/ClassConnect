<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    private function ensureStudent(): void
    {
        $u = auth()->user();
        if (!$u || ($u->user_type ?? '') !== 'student') {
            abort(403, 'Student only.');
        }
    }

    public function create(Assignment $assignment)
    {
        $this->ensureStudent();

        $existing = Submission::where('assignment_id', $assignment->id)
            ->where('student_id', auth()->id())
            ->first();

        return view('submissions.create', compact('assignment', 'existing'));
    }

    public function store(Request $request, Assignment $assignment)
    {
        $this->ensureStudent();

        $data = $request->validate(
            [
                'file' => ['required', 'file', 'mimes:pdf', 'max:10240'], // PDF only, 10MB
            ],
            [
                'file.mimes' => 'Only PDF files are allowed.',
            ]
        );

        // Compute late
        $isLate = false;
        if (!empty($assignment->due_at)) {
            $isLate = now()->gt($assignment->due_at);
        }

        // If already submitted, replace file (consistent status)
        $submission = Submission::where('assignment_id', $assignment->id)
            ->where('student_id', auth()->id())
            ->first();

        if ($submission && !empty($submission->file_path)) {
            Storage::disk('local')->delete($submission->file_path);
        }

        $path = $request->file('file')->store('submissions', 'local');

        if (!$submission) {
            $submission = new Submission();
            $submission->assignment_id = $assignment->id;
            $submission->student_id = auth()->id();
        }

        $submission->file_path = $path;
        $submission->submitted_at = now();
        $submission->is_late = $isLate;

        // If lecturer already graded earlier and student re-uploads, set back to submitted (simple rule)
        $submission->status = 'submitted';
        $submission->save();

        return redirect()->route('submissions.my')->with('success', 'Submission uploaded.');
    }

    public function my()
    {
        $this->ensureStudent();

        $submissions = Submission::with(['assignment.subject', 'grade'])
            ->where('student_id', auth()->id())
            ->latest('submitted_at')
            ->paginate(10);

        return view('submissions.my', compact('submissions'));
    }

    public function download(Submission $submission)
{
    $u = auth()->user();
    if (!$u) abort(403);

    // Student can download own submission only; lecturer can download all
    if (($u->user_type ?? '') !== 'lecturer' && (int)$submission->student_id !== (int)$u->id) {
        abort(403, 'Not allowed.');
    }

    $path = $submission->file_path ?? null;

    if (!$path || !Storage::disk('local')->exists($path)) {
        return back()->with('error', 'File not found. Please re-upload the submission.');
    }

    $filename = 'submission_'.$submission->id.'.pdf';

    return Storage::disk('local')->download($path, $filename);
}
}
