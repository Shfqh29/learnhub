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
    'coordinator' => $request->coordinator,
    'difficulty' => $request->difficulty ?? 1,
    'image_url' => $imagePath,
    'status' => 'pending', // ✅ DEFAULT
]);

    return redirect()->route('module2.index')
                     ->with('success', 'Successfully Add New Course !');
}

        public function show($id)
        {
            $course = ManageCourse::findOrFail($id);
            return view('module2.view', compact('course'));
        }

    public function edit($id)
    {
        $course = ManageCourse::findOrFail($id);
        return view('module2.edit', compact('course'));
    }

   public function update(Request $request, $id)
{
    $request->validate([
        'title' => 'required',
        'description' => 'required',
        'coordinator' => 'required',
        'image' => 'nullable|image|max:2048',
    ]);

    $course = ManageCourse::findOrFail($id);

    // Update image jika ada
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('courses', 'public');
        $course->image_url = $imagePath;
    }

    // Update other fields
    $course->title = $request->title;
    $course->description = $request->description;
    $course->coordinator = $request->coordinator;
    $course->difficulty = $request->difficulty ?? 1;

    // ✅ Reset status ke pending supaya admin approve semula
    $course->status = 'pending';

    $course->save();

    return redirect()->route('module2.index')
                     ->with('success', 'Course updated successfully and is pending admin approval.');
}


    public function destroy($id)
    {
        $course = ManageCourse::findOrFail($id);
        $course->delete();
        return redirect()->route('module2.index')->with('success', 'Course deleted successfully.');
    }

public function indexAdmin()
{
    $courses = ManageCourse::all(); // ❗ bukan pending sahaja
    $user = (object) ['type' => 'admin']; // sementara dummy admin
    return view('module2.indexAdmin', compact('courses'));
}

public function reject($id)
{
    $course = ManageCourse::findOrFail($id);
    $course->status = 'rejected';
    $course->save();

    return redirect()->route('module2.admin')
                     ->with('success', 'Course has been rejected');
}

public function approve($id)
{
    $course = ManageCourse::findOrFail($id);
    $course->status = 'approved';
    $course->save();

    return redirect()->route('module2.admin')
                     ->with('success', 'Successfully approved the course');
}


public function indexStudent()
{
    $courses = ManageCourse::where('status', 'approved')->get();
    $user = (object) ['type' => 'student']; // dummy student
    return view('module2.indexStudent', compact('courses', 'user'));
}



}
