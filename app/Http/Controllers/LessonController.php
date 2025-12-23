<?php

namespace App\Http\Controllers;
<<<<<<< HEAD
use App\Http\Requests\Lesson\StoreLessonRequest;
use App\Models\Lesson;
use App\Models\Subject;
=======

use App\Http\Requests\Lesson\StoreLessonRequest;
use App\Models\Lesson;
>>>>>>> origin/main
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // <-- Import Storage here
use App\Models\File;
use Exception;
use Illuminate\Support\Facades\DB;

use function Symfony\Component\String\s;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
      public function lessonCreate()
    {
        try{
        $subjects = Subject::all();
        return view('lessons.lessonCreate', compact('subjects'));
        }
        catch(Exception $e){
            return view('lessons.lessonCreate')->with('error', 'An error occurred while loading the lesson creation page.');
        }
    }
    public function index(Request $request)
    {
        try{
        $lessons = Lesson::query();
        $subjects = Subject::all();
        if($request->search){
            $lessons->where('title', 'like', '%'.$request->search.'%');
        }
        if($request->subject){
            $lessons->where('subject_id', $request->subject);
        }
        $lessons = $lessons->paginate(6);
        return view('lessons.index', compact('lessons', 'subjects'));
    }catch(Exception $e){
            return redirect()->back()->with('error', 'An error occurred while fetching lessons.');
        }
    }
    public function lessonView()
    {

        $subjects = Subject::all();
        return view('lessons.lessonCreate', compact('subjects'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function file($id){
        $file = File::findOrFail($id);
        return Storage::disk('public')->download($file->file_path, $file->file_name);
    }
    /**
     * Store a newly created resource in storage.
     */
<<<<<<< HEAD
    public function store(StoreLessonRequest  $request)
    {

        $request->validated();
    try{
        DB::transaction(function () use ($request) {
         $lesson = Lesson::create([
            'title' => $request->title,
            'description' => $request->description,
            'subject_id' => $request->subject_id,
        ]);

        if($request->hasFile('file_path')){

        foreach ($request->file('file_path') as $file) {
            $filePath = $file->store('lessons', 'public');
            // Here you can save the file path to the database if needed
           File::create([
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'lesson_id' => $lesson->id,
           ]);
        }
        }
    });

        return redirect()->back()->with('success', 'Lesson created successfully.');
    }
        catch(Exception $e){
            return  redirect()->back()->with('error', 'An error occurred while creating the lesson.');
        }
=======
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
>>>>>>> origin/main
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       $lesson = Lesson::findOrFail($id);
       return view('lessons.lessonView', compact('lesson'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $lesson = Lesson::findOrFail($id);
        $subjects = Subject::all();
        return view('lessons.lessonEdit', compact('lesson', 'subjects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreLessonRequest $request, string $id)
    {
        $request->validated();

        try {
            $lesson = Lesson::findOrFail($id);

            // Update lesson details
            $lesson->update([
                'title' => $request->title,
                'description' => $request->description,
                'subject_id' => $request->subject_id,
            ]);

            // Handle new file uploads if provided
            if($request->hasFile('file_path')){
                foreach ($request->file('file_path') as $file) {
                    $filePath = $file->store('lessons', 'public');

                    File::create([
                        'file_path' => $filePath,
                        'file_name' => $file->getClientOriginalName(),
                        'lesson_id' => $lesson->id,
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Lesson updated successfully.');
        }
        catch(Exception $e){
            return redirect()->back()->with('error', 'An error occurred while updating the lesson.');
        }
    }

    /**
     * Delete a specific file from a lesson.
     */
    public function deleteFile(string $id)
    {
        try {
            $file = File::findOrFail($id);

            // Delete the physical file from storage
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }

            // Delete the database record
            $file->delete();

            return redirect()->back()->with('success', 'File deleted successfully.');
        } catch(Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the file.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Lesson::destroy($id);
        return redirect()->back()->with('success', 'Lesson deleted successfully.');
    }
}
