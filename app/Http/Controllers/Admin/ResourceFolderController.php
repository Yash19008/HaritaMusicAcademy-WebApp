<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResourceFolder;
use Illuminate\Http\Request;

class ResourceFolderController extends Controller
{
    public function index()
    {
        $folders = ResourceFolder::orderBy('type')->orderBy('sort_order')->get();
        return view('admin.resources.index', compact('folders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:curriculum,general,student,teacher',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        ResourceFolder::create([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Folder created successfully.');
    }

    public function update(Request $request, ResourceFolder $resourceFolder)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:curriculum,general,student,teacher',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        $resourceFolder->update([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Folder updated successfully.');
    }

    public function destroy(ResourceFolder $resourceFolder)
    {
        $resourceFolder->delete();
        return back()->with('success', 'Folder deleted successfully.');
    }
}
