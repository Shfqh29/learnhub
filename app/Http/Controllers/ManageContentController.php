<?php

namespace App\Http\Controllers;

use App\Models\ManageCourse;
use App\Models\Title;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ManageContentController extends Controller
{
    /* ======================================================
       HELPER – GET COURSE THAT TEACHER IS ALLOWED TO MANAGE
    ====================================================== */
    private function getTeacherCourseOrFail($courseId)
{
    $teacherName = auth()->user()->name;

    $allowedForms = match ($teacherName) {
        'Ahmad Saiful' => [1, 2, 3],
        'Nur Nadia' => [4, 5],
        default => [],
    };

    return ManageCourse::where('id', $courseId)
        ->whereIn('form', $allowedForms)
        ->firstOrFail();
}

    /* ===============================
       MODULE 3 – TEACHER COURSE LIST
    =============================== */
   public function module3()
{
    $teacherName = auth()->user()->name;

    $allowedForms = match ($teacherName) {
        'Ahmad Saiful' => [1, 2, 3],
        'Nur Nadia' => [4, 5],
        default => [],
    };

    $courses = ManageCourse::whereIn('form', $allowedForms)->get();

    return view('Module3.Teacher.courses', compact('courses'));
}

    /* ===============================
       LIST WEEKS + CONTENT (TEACHER)
    =============================== */
    public function index($course)
    {
        $course = $this->getTeacherCourseOrFail($course);

        $titles = Title::where('course_id', $course->id)
            ->with(['contents' => fn ($q) => $q->orderBy('id')])
            ->get();

        return view('Module3.Teacher.index', compact('course', 'titles'));
    }

    /* ===============================
       CREATE WEEK (TITLE)
    =============================== */
    public function createTitle($course)
    {
        $course = $this->getTeacherCourseOrFail($course);
        return view('Module3.Teacher.add-title', compact('course'));
    }

    /* ===============================
       STORE WEEK (TITLE)
    =============================== */
    public function storeTitle(Request $request, $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $course = $this->getTeacherCourseOrFail($course);

        Title::create([
            'course_id' => $course->id,
            'title'     => $request->title,
        ]);

        return redirect()
            ->route('content.index', $course->id)
            ->with('success', 'Week added successfully');
    }

    /* ===============================
       CREATE CONTENT FORM
    =============================== */
    public function create(Request $request, $course)
    {
        $course = $this->getTeacherCourseOrFail($course);

        $titles = Title::where('course_id', $course->id)->get();
        $selectedTitle = $request->query('title');

        return view('Module3.Teacher.create', compact(
            'course',
            'titles',
            'selectedTitle'
        ));
    }

    /* ===============================
       STORE CONTENT
    =============================== */
    public function store(Request $request, $course)
    {
        $course = $this->getTeacherCourseOrFail($course);

        $request->validate([
            'title_id'     => 'required|exists:titles,id',
            'item_name'    => 'required|string|max:255',
            'description'  => 'nullable|string',
            'content_type' => 'required|in:notes,pdf,video,image',
            'file'         => 'nullable|file|max:20480',
        ]);

        $title = Title::where('id', $request->title_id)
            ->where('course_id', $course->id)
            ->firstOrFail();

        $path = null;
        $fileType = null;

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('contents', 'public');
            $fileType = $this->detectType($request->file('file'));
        }

        Content::create([
            'course_id'    => $course->id,
            'title_id'     => $title->id,
            'item_name'    => $request->item_name,
            'description'  => $request->description,
            'content_type' => $request->content_type,
            'file_path'    => $path,
            'file_type'    => $fileType,
        ]);

        return redirect()
            ->route('content.index', $course->id)
            ->with('success', 'Content added successfully');
    }

    /* ===============================
       EDIT CONTENT
    =============================== */
    public function edit($id)
    {
        $content = Content::findOrFail($id);
        $course  = $this->getTeacherCourseOrFail($content->course_id);

        $titles = Title::where('course_id', $course->id)->get();

        return view('Module3.Teacher.edit', compact(
            'content',
            'course',
            'titles'
        ));
    }

    /* ===============================
       UPDATE CONTENT
    =============================== */
    public function update(Request $request, $id)
    {
        $content = Content::findOrFail($id);
        $course  = $this->getTeacherCourseOrFail($content->course_id);

        $request->validate([
            'title_id'     => 'required|exists:titles,id',
            'item_name'    => 'required|string|max:255',
            'description'  => 'nullable|string',
            'content_type' => 'required|in:notes,pdf,video,image',
            'file'         => 'nullable|file|max:20480',
        ]);

        if ($request->hasFile('file')) {
            if ($content->file_path) {
                Storage::disk('public')->delete($content->file_path);
            }

            $content->file_path = $request->file('file')->store('contents', 'public');
            $content->file_type = $this->detectType($request->file('file'));
        }

        $content->update([
            'title_id'     => $request->title_id,
            'item_name'    => $request->item_name,
            'description'  => $request->description,
            'content_type' => $request->content_type,
        ]);

        return redirect()
            ->route('content.index', $course->id)
            ->with('success', 'Content updated successfully');
    }

    /* ===============================
       DELETE CONTENT
    =============================== */
    public function destroy($id)
    {
        $content = Content::findOrFail($id);
        $this->getTeacherCourseOrFail($content->course_id);

        if ($content->file_path) {
            Storage::disk('public')->delete($content->file_path);
        }

        $content->delete();

        return back()->with('success', 'Content deleted');
    }

    /* ===============================
       FILE TYPE DETECTOR
    =============================== */
    private function detectType($file)
    {
        return match (strtolower($file->getClientOriginalExtension())) {
            'pdf' => 'pdf',
            'mp4', 'mov', 'mkv' => 'video',
            'jpg', 'jpeg', 'png', 'gif' => 'image',
            default => 'document',
        };
    }

    /* ======================================================
       STUDENT – COURSE LIST (UNCHANGED)
    ====================================================== */
    public function studentCourses()
    {
        $student = auth()->user();

        $courses = ManageCourse::where('status_course', 'APPROVED')
            ->where('form', $student->form)
            ->get();

        return view('Module3.Student.index', compact('courses'));
    }

    /* ======================================================
       STUDENT – VIEW CONTENT (UNCHANGED)
    ====================================================== */
    public function studentContents($course)
    {
        $student = auth()->user();

        $course = ManageCourse::where('id', $course)
            ->where('status_course', 'APPROVED')
            ->where('form', $student->form)
            ->firstOrFail();

        $titles = Title::with('contents')
            ->where('course_id', $course->id)
            ->orderBy('id')
            ->get();

        return view('Module3.Student.contents', compact('course', 'titles'));
    }
}
