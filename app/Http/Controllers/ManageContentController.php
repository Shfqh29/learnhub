<?php

namespace App\Http\Controllers;

use App\Models\ManageCourse;
use App\Models\Title;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ManageContentController extends Controller
{
    /* ===============================
       MODULE 3 – COURSE LIST (BY FORM)
    =============================== */
    public function module3(Request $request)
    {
        $form = $request->query('form', 1); // default Form 1

        $courses = ManageCourse::where('form', $form)->get();

        return view('Module3.Teacher.courses', compact('courses', 'form'));
    }

    /* ===============================
       LIST WEEKS + CONTENT
    =============================== */
    public function index(Request $request, $course)
    {
        $course = ManageCourse::findOrFail($course);

        $titles = Title::where('course_id', $course->id)
            ->with(['contents' => function ($q) {
                $q->orderBy('id', 'asc');
            }])
            ->get();

        return view('Module3.Teacher.index', compact('course', 'titles'));
    }

    /* ===============================
       CREATE WEEK (TITLE) FORM
    =============================== */
    public function createTitle(Request $request, $course)
    {
        $course = ManageCourse::findOrFail($course);

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

        $course = ManageCourse::findOrFail($course);

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
        $course = ManageCourse::findOrFail($course);

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
        $request->validate([
            'title_id'     => 'required|exists:titles,id',
            'item_name'    => 'required|string|max:255',
            'description'  => 'nullable|string',
            'content_type' => 'required|in:notes,pdf,video,image',
            'file'         => 'nullable|file|max:20480',
        ]);

        $title = Title::where('id', $request->title_id)
            ->where('course_id', $course)
            ->firstOrFail();

        $path = null;
        $fileType = null;

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('contents', 'public');
            $fileType = $this->detectType($request->file('file'));
        }

        Content::create([
            'course_id'    => $course,
            'title_id'     => $title->id,
            'item_name'    => $request->item_name,
            'description'  => $request->description,
            'content_type' => $request->content_type,
            'file_path'    => $path,
            'file_type'    => $fileType,
        ]);

        return redirect()
            ->route('content.index', $course)
            ->with('success', 'Content added successfully');
    }

    /* ===============================
       EDIT CONTENT FORM
    =============================== */
    public function edit(Request $request, $id)
    {
        $content = Content::findOrFail($id);
        $course  = ManageCourse::findOrFail($content->course_id);

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
            ->route('content.index', $content->course_id)
            ->with('success', 'Content updated successfully');
    }

    /* ===============================
       DELETE CONTENT
    =============================== */
    public function destroy($id)
    {
        $content = Content::findOrFail($id);

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
}
