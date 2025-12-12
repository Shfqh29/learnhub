<?php

namespace App\Http\Controllers;

use App\Models\ManageCourse;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ManageContentController extends Controller
{
    // MODULE 3 ENTRY PAGE: LIST COURSES
 public function module3()
{
    $courses = ManageCourse::all();
    return view('Module3.Teacher.courses', compact('courses'));
}


    // LIST CONTENT BY COURSE
   public function index($course)
{
    $course = ManageCourse::findOrFail($course);
    $contents = Content::where('course_id', $course->id)->get();

    return view('Module3.Teacher.index', compact('course', 'contents'));
}

    // SHOW CREATE FORM
  public function create($course)
{
    $course = ManageCourse::findOrFail($course);
    return view('Module3.Teacher.create', compact('course'));
}

    // STORE CONTENT
    public function store(Request $request, $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file'  => 'required|file|max:10240',
        ]);

        $path = $request->file('file')->store('contents', 'public');

        Content::create([
            'course_id' => $course,
            'title' => $request->title,
            'file_path' => $path,
            'file_type' => $request->file('file')->getClientOriginalExtension(),
        ]);

        return redirect()->route('content.index', $course)
            ->with('success', 'Content added successfully');
    }

    // EDIT CONTENT
    public function edit($id)
    {
        $content = Content::findOrFail($id);
        return view('Module3.Teacher.edit', compact('content'));
    }

    // UPDATE CONTENT
    public function update(Request $request, $id)
    {
        $content = Content::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'file'  => 'nullable|file|max:10240',
        ]);

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($content->file_path);
            $content->file_path = $request->file('file')->store('contents', 'public');
            $content->file_type = $request->file('file')->getClientOriginalExtension();
        }

        $content->title = $request->title;
        $content->save();

        return redirect()->route('content.index', $content->course_id)
            ->with('success', 'Content updated successfully');
    }

    // DELETE CONTENT
    public function destroy($id)
    {
        $content = Content::findOrFail($id);
        Storage::disk('public')->delete($content->file_path);
        $content->delete();

        return back()->with('success', 'Content deleted successfully');
    }
}
