@extends('layouts.app') 

@section('title', 'Assignments')

@section('content')
<div class="container py-4">
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- 
        ---------------------------------------------------------
        1. TEACHER INTERFACE (LIST CREATED ASSIGNMENTS)
        Corresponds to Figure 5.3.3 functionality (viewing submissions summary)
        ---------------------------------------------------------
    --}}
      @if(Auth::user()->isTeacher()) 
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 style="color: #333;">Assignment Management (Teacher)</h1>
            {{-- Button to go to the Create Assignment Form (Figure 5.3.1) --}}
            <a href="{{ route('assignments.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Create New Assignment
            </a>
        </div>

        <table class="table table-hover table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Title & Class</th>
                    <th>Due Date</th>
                    <th>Submissions</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                {{-- Loop through assignments created by this teacher --}}
                @forelse($assignments as $assignment)
                <tr>
                    <td>{{ $assignment->title }}</td>
                    <td>{{ \Carbon\Carbon::parse($assignment->due_date)->format('d M Y, h:i A') }}</td>
                    <td>
                        {{-- submissions_count comes from the Controller's withCount() --}}
                        <span class="badge bg-info">{{ $assignment->submissions_count }} Submissions</span>
                    </td>
                    <td>
                        @if($assignment->due_date < now())
                            <span class="badge bg-secondary">Closed</span>
                        @else
                            <span class="badge bg-success">Open</span>
                        @endif
                    </td>
                    <td>
                        {{-- Link to view all submissions for grading (Figure 5.3.3) --}}
                        <a href="{{ route('assignments.submissions', $assignment->id) }}" class="btn btn-sm btn-outline-dark me-2">
                            Review Submissions
                        </a>
                        {{-- Link to edit assignment (Figure 5.3.2) --}}
                        <a href="{{ route('assignments.edit', $assignment->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center p-4">You have not created any assignments yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

    {{-- 
        ---------------------------------------------------------
        2. STUDENT INTERFACE (LIST AVAILABLE ASSIGNMENTS)
        Corresponds to Figure 5.3.6: My Submission Interface
        ---------------------------------------------------------
    --}}
    @else
        <h1 style="color: #333;">My Assignments (Student)</h1>
        
        <div class="row row-cols-1 row-cols-md-2 g-4 mt-3">
            {{-- Loop through all available assignments with student status --}}
            @forelse($assignments as $assignment)
            <div class="col">
                <div class="card h-100 shadow-sm border-{{ 
                    $assignment->submission_status == 'Checked' ? 'success' : 
                    ($assignment->submission_status == 'Submitted' ? 'warning' : 'danger') 
                }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            {{-- Assignment Title --}}
                            <h5 class="card-title text-primary">{{ $assignment->title }}</h5>
                            
                            {{-- Submission Status Badge --}}
                            <span class="badge bg-{{ 
                                $assignment->submission_status == 'Checked' ? 'success' : 
                                ($assignment->submission_status == 'Submitted' ? 'warning text-dark' : 'danger') 
                            }} p-2">
                                {{ $assignment->submission_status }}
                            </span>
                        </div>
                        
                        <p class="card-text text-muted small">
                            Due: **{{ \Carbon\Carbon::parse($assignment->due_date)->format('d M Y, h:i A') }}**
                        </p>
                        
                        <hr class="my-3">
                        
                        {{-- Action Buttons --}}
                        @if($assignment->submission_status == 'Checked' || $assignment->submission_status == 'Submitted')
                            {{-- View Submission Interface (Figure 5.3.9) --}}
                            <a href="{{ route('assignments.show_submission', $assignment->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> View Submission
                            </a>
                        @else
                            {{-- Add Submission Interface (Figure 5.3.7) --}}
                            <a href="{{ route('assignments.show_submission_form', $assignment->id) }}" class="btn btn-sm btn-success">
                                <i class="fas fa-upload"></i> Submit Assignment
                            </a>
                        @endif
                        
                        {{-- Toggle Description --}}
                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#description-{{ $assignment->id }}" aria-expanded="false">
                            <i class="fas fa-info-circle"></i> Details
                        </button>

                        <div class="collapse mt-3" id="description-{{ $assignment->id }}">
                            <p class="small bg-light p-3 rounded">{{ $assignment->description }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info text-center">There are no assignments available at this time.</div>
            </div>
            @endforelse
        </div>
    @endif

</div>
@endsection
