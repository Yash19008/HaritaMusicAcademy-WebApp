<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Syllabus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SyllabusController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::orderBy('name')->get();
        $query = Syllabus::with('course');

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        $syllabi = $query->orderBy('course_id')->orderBy('sort_order')->get();

        return view('admin.syllabus.index', compact('syllabi', 'courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,png,jpg,jpeg|max:10240',
            'sort_order' => 'nullable|integer',
        ]);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $fileSize = $file->getSize();
        
        $path = $file->store('syllabi', 'local');

        Syllabus::create([
            'course_id' => $request->course_id,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $path,
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return back()->with('success', 'Syllabus uploaded successfully.');
    }

    public function update(Request $request, Syllabus $syllabus)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,png,jpg,jpeg|max:10240',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->only(['course_id', 'title', 'description', 'sort_order']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('file')) {
            if ($syllabus->file_path && Storage::disk('local')->exists($syllabus->file_path)) {
                Storage::disk('local')->delete($syllabus->file_path);
            }
            
            $file = $request->file('file');
            $data['file_path'] = $file->store('syllabi', 'local');
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
        }

        $syllabus->update($data);

        return back()->with('success', 'Syllabus updated successfully.');
    }

    public function destroy(Syllabus $syllabus)
    {
        $syllabus->delete();
        return back()->with('success', 'Syllabus deleted successfully.');
    }

    public function download(Syllabus $syllabus)
    {
        if (!Storage::disk('local')->exists($syllabus->file_path)) {
            abort(404, 'File not found');
        }
        return Storage::disk('local')->download($syllabus->file_path, $syllabus->file_name);
    }
}
