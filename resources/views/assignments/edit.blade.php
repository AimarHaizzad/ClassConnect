@extends('layouts.app')

@section('title', 'Edit Assignment')

@section('content')
@php
    $id = $assignment->getAttribute('id');

    $dueValue = '';
    if (!empty($assignment->due_at)) {
        $dueValue = \Illuminate\Support\Carbon::parse($assignment->due_at)->format('Y-m-d\TH:i');
    }

    $attachPath = $assignment->getAttribute('attachment_path') ?? null;
@endphp

<style>
    .wrap { max-width: 900px; }
    .card { background:#fff; border-radius:16px; padding:18px; box-shadow:0 2px 8px rgba(0,0,0,.08); }
    .title { font-size:22px; font-weight:900; color:#2d2d2d; margin-bottom:6px; }
    .muted { color:#777; font-size:13px; margin-bottom:16px; }

    .grid { display:grid; grid-template-columns: 1fr 1fr; gap:12px; }
    @media (max-width: 720px){ .grid { grid-template-columns: 1fr; } }

    label { font-weight:800; font-size:13px; color:#333; display:block; margin-bottom:6px; }
    input, select, textarea {
        width:100%; padding:10px 12px; border-radius:10px; border:1px solid #ddd; background:#fff; outline:none;
    }
    textarea { min-height:120px; resize:vertical; }

    .row { margin-bottom:12px; }
    .actions { display:flex; gap:10px; flex-wrap:wrap; margin-top:8px; }

    .btn{
        padding:10px 14px; border-radius:10px; border:none; cursor:pointer; font-weight:900;
        text-decoration:none; display:inline-flex; align-items:center; gap:8px; font-size:14px;
    }
    .btn-primary { background:#795E2E; color:#fff; }
    .btn-light { background:#f2f2f2; color:#222; }
    .btn-danger { background:#dc3545; color:#fff; }

    .error { color:#b00020; font-size:13px; margin-top:6px; }
    .hint { color:#666; font-size:12px; margin-top:6px; }
    .box { border:1px solid #eee; border-radius:12px; padding:12px; background:#fff; }
</style>

<div class="wrap">
    <div class="card">
        <div class="title">Edit Assignment</div>
        <div class="muted">Update assignment details. Uploading a new PDF will replace the current file.</div>

        {{-- ✅ UPDATE FORM (ONLY UPDATE) --}}
        <form method="POST" action="{{ route('assignments.update', $id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid">
                <div class="row">
                    <label>Subject (optional)</label>
                    <select name="subject_id">
                        <option value="">-- Select Subject --</option>
                        @foreach(($subjects ?? []) as $s)
                            <option value="{{ $s->id }}"
                                @selected(old('subject_id', $assignment->subject_id ?? '') == $s->id)>
                                {{ $s->name ?? ('Subject #'.$s->id) }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <label>Due Date (optional)</label>
                    <input type="datetime-local" name="due_at" value="{{ old('due_at', $dueValue) }}">
                    @error('due_at') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <label>Title *</label>
                <input type="text" name="title" value="{{ old('title', $assignment->title ?? '') }}">
                @error('title') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <label>Description (optional)</label>
                <textarea name="description">{{ old('description', $assignment->description ?? '') }}</textarea>
                @error('description') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="grid">
                <div class="row">
                    <label>Max Marks (optional)</label>
                    <input type="number" name="max_marks" min="0" max="1000"
                           value="{{ old('max_marks', $assignment->max_marks ?? '') }}">
                    @error('max_marks') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <label>Attachment (PDF only, optional)</label>
                    <input type="file" name="attachment" accept="application/pdf">
                    @error('attachment') <div class="error">{{ $message }}</div> @enderror
                    <div class="hint">Max 10MB. PDF only.</div>
                </div>
            </div>

            <div class="row">
                <label>Current Attachment</label>
                <div class="box">
                    @if(!empty($attachPath) && Route::has('assignments.download'))
                        <div class="muted" style="margin-bottom:10px;">A file is currently attached.</div>
                        <a class="btn btn-light" href="{{ route('assignments.download', $id) }}">Download Current PDF</a>
                    @else
                        <div class="muted">No attachment uploaded.</div>
                    @endif
                </div>
            </div>

            <div class="actions">
                <button class="btn btn-primary" type="submit">Save Changes</button>
                <a class="btn btn-light" href="{{ route('assignments.index') }}">Back</a>
            </div>
        </form>

        {{-- ✅ DELETE FORM (SEPARATE, NOT NESTED) --}}
        @if(Route::has('assignments.destroy'))
            <div style="margin-top:14px;">
                <form method="POST" action="{{ route('assignments.destroy', $id) }}"
                      onsubmit="return confirm('Delete this assignment?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">Delete Assignment</button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
