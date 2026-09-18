@extends('layouts.main')
@section('title', 'Manage Files: ' . $resourceFolder->name)
@section('page', 'resource-folders')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<style>
  .btn-upload { background: var(--primary); color: white; padding: 0.5rem 1rem; border-radius: var(--radius-sm); border: none; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 500; font-size: 0.9rem; }
  .btn-upload:hover { background: var(--primary-dark); color: white; }
  .btn-back { display: inline-flex; align-items: center; gap: 0.3rem; color: #64748b; text-decoration: none; font-weight: 500; margin-bottom: 1rem; transition: color 0.2s; }
  .btn-back:hover { color: var(--primary); }
</style>
@endpush

@section('content')
<a href="{{ route('admin.resource-folders.index') }}" class="btn-back">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
    Back to Folders
</a>

<div class="card">
    <div class="card-header d-flex align-center justify-between">
        <div>
            <h4 class="font-semibold" style="font-family: var(--font-serif); font-size: 1.25rem;">
                Folder: {{ $resourceFolder->name }}
            </h4>
            <span style="font-size: 0.85rem; color: #64748b;">Type: {{ ucfirst($resourceFolder->type) }}</span>
        </div>
        <button class="btn-upload" onclick="openUploadModal()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
            Upload File
        </button>
    </div>
    
    <div class="card-body p-3">
        <table class="table display responsive nowrap" id="fileTable" style="width:100%">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>File</th>
                    <th>Size</th>
                    <th>Order</th>
                    <th style="width: 100px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($files as $item)
                <tr>
                    <td>{{ $item->title }}</td>
                    <td>
                        <a href="{{ Storage::disk('public')->url($item->file_path) }}" target="_blank" class="text-primary hover-underline">{{ $item->file_name }}</a>
                    </td>
                    <td><span class="text-muted" style="font-size: 0.85rem;">{{ $item->fileSizeForHumans }}</span></td>
                    <td>{{ $item->sort_order }}</td>
                    <td>
                        <div class="d-flex align-center justify-center gap-2">
                            <form action="{{ route('admin.resource-folders.files.destroy', [$resourceFolder, $item]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this file?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background: rgba(231, 76, 60, 0.1); color: #e74c3c; border:none;" title="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal-overlay" id="fileModal" style="display: none; position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.5); z-index: 999; align-items:center; justify-content:center;">
    <div class="modal-content" style="background: white; border-radius: var(--radius-md); padding: 1.5rem; width: 100%; max-width: 500px; box-shadow: var(--shadow-lg);">
        <div class="d-flex justify-between align-center mb-3">
            <h3 id="modalTitle" style="font-family: var(--font-serif); margin:0;">Upload File to Folder</h3>
            <button type="button" onclick="closeModal()" style="background:none; border:none; cursor:pointer; font-size:1.2rem;">&times;</button>
        </div>
        
        <form id="fileForm" action="{{ route('admin.resource-folders.files.store', $resourceFolder) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label class="form-label d-block mb-1">File Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" class="form-control w-100" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label d-block mb-1">File <span class="text-danger">*</span></label>
                <input type="file" name="file" id="file" class="form-control w-100" required>
                <small class="text-muted d-block mt-1">PDF, DOC, PPT, Images, ZIP up to 50MB.</small>
            </div>
            
            <div class="mb-3">
                <label class="form-label d-block mb-1">Sort Order</label>
                <input type="number" name="sort_order" id="sort_order" class="form-control w-100" value="0">
            </div>
            
            <div class="d-flex justify-end gap-2 mt-4">
                <button type="button" class="btn" style="background: #f1f5f9; color: #475569;" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" id="submitBtn">Upload</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
    $(document).ready(function () {
        $('#fileTable').DataTable({
            responsive: true,
            language: { search: "", searchPlaceholder: "Search files..." }
        });
    });

    const modal = document.getElementById('fileModal');
    const form = document.getElementById('fileForm');

    function openUploadModal() {
        form.reset();
        modal.style.display = 'flex';
    }

    function closeModal() {
        modal.style.display = 'none';
    }

    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });
</script>
@endpush
