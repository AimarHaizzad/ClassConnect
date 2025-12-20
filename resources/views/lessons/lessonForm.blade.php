@extends('layouts.app')

@section('title', 'Lessons')

@section('content')

<div class="container">
    <div class="card p-4 ">

        <!-- Title -->
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" class="form-control" >
        </div>

        <!-- Description -->
        <div class="mb-2">
            <label class="form-label">Description</label>
            <textarea class="form-control" ></textarea>
            <div class="text-end small mt-1">143 words</div>
        </div>

        <!-- Upload -->
        <div class="mb-3">
            <label class="form-label">Upload material</label>

            <!-- File input -->
            <input type="file" class="form-control">

            <!-- File chip -->
            <div class="mt-2">

            </div>
        </div>

        <!-- Buttons -->
        <div class="mt-4 row justify-content-end gap-2">
            <button class="btn btn-secondary col-md-2">cancel</button>
            <button class="btn btn-success col-md-2">save</button>
        </div>

    </div>

    </div>
@endsection

