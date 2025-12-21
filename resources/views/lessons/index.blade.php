@extends('layouts.app')

@section('title', 'Lessons')

@section('content')

<div class="container py-5">

    <!-- Top Bar -->
    <div class="d-flex align-items-center gap-3 mb-4">

        <select class="form-select w-auto">
            <option>All</option>
        </select>

        <div class="input-group rounded-pill flex-grow-1">
            <input type="text" class="form-control rounded-pill" placeholder="Search by title or author">
            <span class="input-group-text bg-transparent border-0">
           <button class="btn btn-light fw-semibold well-sm rounded-pill">
            <i class="bi bi-search"></i>
        </button>
            </span>
        </div>

        <button class="btn btn-light fw-semibold" onclick="window.location='{{ route('lessons.lessonForm') }}'">
            <i class="bi bi-plus"></i> Lesson
        </button>
    </div>

    <!-- Discussion Box -->
    <div class="bg-secondary-subtle p-3 rounded">

        <div class="fw-bold mb-2 bg-light px-3 py-2 rounded">
            Lissons
        </div>

        <!-- Lesson Item -->
        <div class="bg-warning bg-opacity-25 p-3 rounded d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1 fw-semibold">Lesson 1</h6>
                <small>Description</small>
            </div>

            <div class="d-md-flex gap-2">
                <a href="#" class="btn btn-light btn-sm">View</a>
                <a href="#" class="btn btn-light btn-sm">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <button class="btn btn-light btn-sm text-danger">
                    <i class="bi bi-trash"></i> Delete
                </button>
            </div>


        </div>

    </div>

</div>
@endsection

