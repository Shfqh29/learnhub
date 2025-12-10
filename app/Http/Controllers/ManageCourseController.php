<?php

namespace App\Http\Controllers;

use App\Models\ManageCourse;
use Illuminate\Http\Request;

class ManageCourseController extends Controller
{
   public function index()
{
    $courses = ManageCourse::all();
    return view('module2.index', compact('courses'));
}


    public function create()
{
    return view('module2.create');
}

 public function store(Request $request)
{
    $request->validate([
        'title' => 'required',
        'description' => 'required',
        'coordinator' => 'required',
        'image' => 'nullable|image|max:2048', // optional validation
    ]);

    // Handle image upload
    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('courses', 'public');
    }

    ManageCourse::create([
        'title' => $request->title,
        'description' => $request->description,
        'teacher_id' => $request->coordinator,
        'difficulty' => $request->difficulty ?? 1,
        'image_url' => $imagePath,
    ]);

    return redirect()->route('manage_courses.index')
                     ->with('success', 'Successfully Add New Course !');
}

        public function show($id)
        {
            $course = ManageCourse::findOrFail($id);
            return view('manage_courses.view', compact('course'));
        }

    public function edit($id)
    {
        $course = ManageCourse::findOrFail($id);
        return view('manage_courses.edit', compact('course'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'category' => 'required',
            'duration' => 'required|integer',
        ]);

        $course = ManageCourse::findOrFail($id);
        $course->update($request->all());
        return redirect()->route('manage_courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy($id)
    {
        $course = ManageCourse::findOrFail($id);
        $course->delete();
        return redirect()->route('manage_courses.index')->with('success', 'Course deleted successfully.');
    }
}
