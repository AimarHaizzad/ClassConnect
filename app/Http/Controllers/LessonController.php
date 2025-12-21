<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lesson\StoreLessonRequest;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
      public function lessonForm()
    {
        return view('lessons.lessonForm');
    }
    public function index()
    {
        return view('lessons.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLessonRequest $request)
    {
        $request->validated();

        if($request->hasFile('file_path')){
            $filePath = $request->file('file_path')->store('lessons', 'public');
            // Here you can save the file path to the database if needed
        }
        Lesson::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath ?? null,
            'subject_id' => $request->subject_id,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
