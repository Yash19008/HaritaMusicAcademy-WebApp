<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Curriculum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CurriculumController extends Controller
{
    public function index()
    {
        $curricula = Curriculum::orderBy('sort_order')->get();
        return view('admin.curriculum.index', compact('curricula'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,png,jpg,jpeg|max:10240',
            'sort_order' => 'nullable|integer',
        ]);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $fileSize = $file->getSize();
        
        $path = $file->store('curricula', 'local');

        Curriculum::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $path,
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return back()->with('success', 'Curriculum uploaded successfully.');
    }

    public function update(Request $request, Curriculum $curriculum)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,png,jpg,jpeg|max:10240',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->only(['title', 'description', 'sort_order']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('file')) {
            if ($curriculum->file_path && Storage::disk('local')->exists($curriculum->file_path)) {
                Storage::disk('local')->delete($curriculum->file_path);
            }
            
            $file = $request->file('file');
            $data['file_path'] = $file->store('curricula', 'local');
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
        }

        $curriculum->update($data);

        return back()->with('success', 'Curriculum updated successfully.');
    }

    public function destroy(Curriculum $curriculum)
    {
        $curriculum->delete();
        return back()->with('success', 'Curriculum deleted successfully.');
    }

    public function download(Curriculum $curriculum)
    {
        if (!Storage::disk('local')->exists($curriculum->file_path)) {
            abort(404, 'File not found');
        }
        return Storage::disk('local')->download($curriculum->file_path, $curriculum->file_name);
    }
}
