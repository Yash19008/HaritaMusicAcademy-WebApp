<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResourceFolder;
use App\Models\ResourceFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResourceFileController extends Controller
{
    public function index(ResourceFolder $resourceFolder)
    {
        $files = $resourceFolder->files()->get();
        return view('admin.resources.files', compact('resourceFolder', 'files'));
    }

    public function store(Request $request, ResourceFolder $resourceFolder)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,png,jpg,jpeg,zip|max:51200',
            'sort_order' => 'nullable|integer',
        ]);

        $file = $request->file('file');
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $fileName = $originalName . '_' . time() . '.' . $extension;
        $fileSize = $file->getSize();
        
        $path = $file->store('resource_files', 'public');

        $resourceFolder->files()->create([
            'title' => $request->title,
            'file_name' => $fileName,
            'file_path' => $path,
            'file_size' => $fileSize,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return back()->with('success', 'File uploaded successfully.');
    }

    public function destroy(ResourceFolder $resourceFolder, ResourceFile $resourceFile)
    {
        if ($resourceFile->file_path && Storage::disk('public')->exists($resourceFile->file_path)) {
            Storage::disk('public')->delete($resourceFile->file_path);
        }
        $resourceFile->delete();
        return back()->with('success', 'File deleted successfully.');
    }
}
