@extends('layouts.app')

@section('title', 'View Assignment')

@section('content')
@php
    $userType = auth()->check() ? (auth()->user()->user_type ?? '') : '';

    $id    = $assignment->getAttribute('id');
    $title = $assignment->getAttribute('title') ?? ('Assignment #'.$id);
    $desc  = $assignment->getAttribute('description') ?? null;
    $due   = $assignment->getAttribute('due_at') ?? null;
    $max   = $assignment->getAttribute('max_marks') ?? null;
    $attachPath = $assignment->getAttribute('attachment_path') ?? null;
@endphp

<style>
    .wrap { max-width: 900px; }
    .card { background:#fff; border-radius:16px; padding:18px; box-shadow:0 2px 8px rgba(0,0,0,.08); }
    .title { font-size:22px; font-weight:900; color:#2d2d2d; margin-bottom:6px; }
    .muted { color:#777; font-size:13px; }
    .meta { display:flex; gap:10px; flex-wrap:wrap; margin-top:10px; }
    .badge{
        display:inline-block; padding:4px 10px; border-radius:999px;
        background:#f2efdf; border:1px solid #e3ddc9;
        font-size:12px; font-weight:800; color:#6a5226;
    }
    .section { margin-top:16px; }
    .section h4 { margin:0 0 8px 0; font-size:14px; color:#333; }
    .box { border:1px solid #eee; border-radius:12px; padding:12px; background:#fff; }
    .actions { display:flex; gap:10px; flex-wrap:wrap; margin-top:14px; }
    .btn{
        padding:10px 14px; border-radius:10px; border:none; cursor:pointer; font-weight:800;
        text-decoration:none; display:inline-flex; align-items:center; gap:8px; font-size:14px;
    }
    .btn-primary { background:#795E2E; color:#fff; }
    .btn-light { background:#f2f2f2; color:#222; }
    .btn-danger { background:#dc3545; color:#fff; }
</style>

<div class="wrap">
    <div class="card">
        <div class="title">{{ $title }}</div>
        <div class="muted">Assignment details and available actions.</div>

        <div class="meta">
            @if(isset($assignment->subject) && $assignment->subject)
                <span class="badge">{{ $assignment->subject->name }} ({{ $assignment->subject->code }})</span>
            @else
                <span class="badge">Subject: N/A</span>
            @endif

            @if(!empty($due))
                <span class="badge">Due: {{ \Illuminate\Support\Carbon::parse($due)->format('d M Y, h:i A') }}</span>
            @else
                <span class="badge">Due: Not set</span>
            @endif

            @if(!is_null($max))
                <span class="badge">Max Marks: {{ $max }}</span>
            @endif
        </div>

        <div class="section">
            <h4>Description</h4>
            <div class="box">
                <div class="muted" style="white-space: pre-wrap;">{{ $desc ?: 'No description provided.' }}</div>
            </div>
        </div>

        <div class="section">
    <h4>Attachment</h4>
    <div class="box">
        @if(!empty($attachPath) && Route::has('assignments.download'))
            <div class="muted" style="margin-bottom:10px;">Attachment is available.</div>
            <a class="btn btn-light" href="{{ route('assignments.download', $id) }}">Download PDF</a>
        @else
            <div class="muted">No attachment uploaded.</div>
        @endif
    </div>
</div>


        <div class="actions">
            <a class="btn btn-light" href="{{ route('assignments.index') }}">Back</a>

            {{-- Student actions --}}
            @if($userType === 'student' && Route::has('submissions.create'))
                <a class="btn btn-primary" href="{{ route('submissions.create', $id) }}">Submit / Resubmit</a>
            @endif

            {{-- Lecturer actions --}}
            @if($userType === 'lecturer')
                @if(Route::has('assignments.edit'))
                    <a class="btn btn-light" href="{{ route('assignments.edit', $id) }}">Edit</a>
                @endif

                @if(Route::has('assignments.submissions'))
                    <a class="btn btn-primary" href="{{ route('assignments.submissions', $id) }}">Submissions</a>
                @endif

                @if(Route::has('assignments.destroy'))
                    <form method="POST" action="{{ route('assignments.destroy', $id) }}" style="margin:0;"
                          onsubmit="return confirm('Delete this assignment?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit">Delete</button>
                    </form>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
