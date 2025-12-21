<?php

namespace App\Http\Controllers;

use App\Models\ManageCourse;
use Illuminate\Http\Request;

class ManageCourseController extends Controller
{
   public function index()
{
    $courses = ManageCourse::all();
    $teacher = auth()->user()->name; // Dapatkan nama teacher login

    return view('module2.index', compact('courses', 'teacher'));
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
        'coordinator' => 'required|string', // nama teacher, boleh ikut input user
        'image' => 'nullable|image|max:2048',
    ]);

    $imagePath = $request->hasFile('image') ? $request->file('image')->store('courses', 'public') : null;

    ManageCourse::create([
    'title' => $request->title,
    'description' => $request->description,
    'teacher_id' => 1, // assign terus ID 1
    'coordinator' => $request->coordinator, // tambah line ni
    'difficulty' => $request->difficulty ?? 1,
    'image_url' => $imagePath,
    'status_course' => 'PENDING APPROVAL',
]);


    return redirect()->route('module2.index')
                     ->with('success', 'Successfully Add New Course!');
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
        'difficulty' => 'required|integer',
        'coordinator' => 'required|string',
    ]);

    $course = ManageCourse::findOrFail($id);

    $course->title = $request->title;
    $course->description = $request->description;
    $course->difficulty = $request->difficulty;
    $course->teacher_id = 1; // atau ikut coordinator
    $course->coordinator = $request->coordinator;

    // Reset status jika course dah APPROVED / REJECTED
    if(in_array($course->status_course, ['APPROVED', 'REJECTED'])) {
        $course->status_course = 'PENDING APPROVAL';
    }

    // Kalau ada image baru, save
    if ($request->hasFile('image')) {
        $course->image_url = $request->file('image')->store('courses', 'public');
    }

    $course->save();

    return redirect()->route('module2.index')
                     ->with('success', 'Course updated successfully and sent for approval!');
}


    public function destroy($id)
    {
        $course = ManageCourse::findOrFail($id);
        $course->delete();
        return redirect()->route('module2.index')->with('success', 'Course deleted successfully.');
    }

  public function indexAdmin()
{
$courses = ManageCourse::orderBy('id', 'desc')->get();
    return view('module2.indexAdmin', compact('courses'));
}

//Admin

public function approve($id)
{
    $course = ManageCourse::findOrFail($id);
    $course->status_course = 'APPROVED';
    $course->save();

    return back()->with('success', 'Course approved successfully!');
}

public function reject($id)
{
    $course = ManageCourse::findOrFail($id);
    $course->status_course = 'REJECTED';
    $course->save();

    return back()->with('success', 'Course rejected!');
}

//student
// Student: list approved courses only
public function indexStudent()
{
   $courses = ManageCourse::where('status_course', 'APPROVED')->get();
$studentForm = auth()->user()->form;
return view('module2.indexStudent', compact('courses', 'studentForm'));
}


// Student: view single approved course
public function showStudent($id)
{
    $course = ManageCourse::where('id', $id)
                ->where('status_course', 'APPROVED')
                ->firstOrFail();

    // 🔐 Get student form
    $studentForm = auth()->user()->form; // contoh: "Form 1"

    // 🔍 Extract course form from title
   $normalizedStudentForm = preg_replace('/\s+/', '', strtoupper($studentForm));
$normalizedCourseForm = preg_replace('/\s+/', '', strtoupper($courseForm));

$isAccessible = $studentForm && $courseForm &&
    $normalizedStudentForm === $normalizedCourseForm;

    // ❌ If course not for this student
    if (!$courseForm || strcasecmp($studentForm, $courseForm) !== 0) {
        abort(403, 'This course is not accessible for your form');
    }

    return view('module2.viewStudent', compact('course'));
}


}
