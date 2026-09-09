@extends('layouts.main')
@section('title', 'Curriculum Master - Harita Music Academy')
@section('page', 'curriculum')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<style>
  .btn-upload { background: var(--primary); color: white; padding: 0.5rem 1rem; border-radius: var(--radius-sm); border: none; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 500; font-size: 0.9rem; }
  .btn-upload:hover { background: var(--primary-dark); color: white; }
  .badge { display: inline-block; padding: 0.25rem 0.5rem; border-radius: var(--radius-sm); font-size: 0.75rem; font-weight: 600; }
  .badge-success { background: rgba(46, 204, 113, 0.1); color: #27ae60; }
  .badge-danger { background: rgba(231, 76, 60, 0.1); color: #c0392b; }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header d-flex align-center justify-between">
        <h4 class="font-semibold" style="font-family: var(--font-serif); font-size: 1.25rem;">Curriculum Master</h4>
        <button class="btn-upload" onclick="openUploadModal()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
            Upload Curriculum
        </button>
    </div>
    
    <div class="card-body p-3">
        <table class="table display responsive nowrap" id="curriculumTable" style="width:100%">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>File</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th style="width: 120px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($curricula as $item)
                <tr>
                    <td>{{ $item->title }}</td>
                    <td title="{{ $item->description }}">{{ Str::limit($item->description, 40) }}</td>
                    <td>
                        <a href="{{ route('admin.curriculum.download', $item) }}" target="_blank" class="text-primary hover-underline">{{ $item->file_name }}</a>
                        <div class="text-muted" style="font-size: 0.8rem;">{{ $item->fileSizeForHumans }}</div>
                    </td>
                    <td>{{ $item->sort_order }}</td>
                    <td>
                        @if($item->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex align-center justify-center gap-2">
                            <button class="btn btn-sm" style="background: rgba(var(--primary-rgb), 0.1); color: var(--primary); border:none;" 
                                onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->title) }}', '{{ addslashes($item->description) }}', {{ $item->sort_order }}, {{ $item->is_active ? 'true' : 'false' }})" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                            </button>
                            <form action="{{ route('admin.curriculum.destroy', $item) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this curriculum?');" style="display:inline;">
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

<!-- Upload/Edit Modal -->
<div class="modal-overlay" id="curriculumModal" style="display: none; position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.5); z-index: 999; align-items:center; justify-content:center;">
    <div class="modal-content" style="background: white; border-radius: var(--radius-md); padding: 1.5rem; width: 100%; max-width: 500px; box-shadow: var(--shadow-lg);">
        <div class="d-flex justify-between align-center mb-3">
            <h3 id="modalTitle" style="font-family: var(--font-serif); margin:0;">Upload Curriculum</h3>
            <button type="button" onclick="closeModal()" style="background:none; border:none; cursor:pointer; font-size:1.2rem;">&times;</button>
        </div>
        
        <form id="curriculumForm" action="{{ route('admin.curriculum.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            
            <div class="mb-3">
                <label class="form-label d-block mb-1">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" class="form-control w-100" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label d-block mb-1">Description</label>
                <textarea name="description" id="description" class="form-control w-100" rows="3"></textarea>
            </div>
            
            <div class="mb-3">
                <label class="form-label d-block mb-1">File <span id="fileRequired" class="text-danger">*</span></label>
                <input type="file" name="file" id="file" class="form-control w-100" accept=".pdf,.doc,.docx,.ppt,.pptx,.png,.jpg,.jpeg">
                <small class="text-muted d-block mt-1">PDF, DOC, DOCX, PPT, Images up to 10MB.</small>
                <small id="fileHint" class="text-muted d-block" style="display:none;">Leave blank to keep existing file.</small>
            </div>
            
            <div class="d-flex gap-3 mb-3">
                <div style="flex:1;">
                    <label class="form-label d-block mb-1">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" class="form-control w-100" value="0">
                </div>
                <div style="flex:1; display:flex; align-items:flex-end; padding-bottom: 0.5rem;">
                    <label class="d-flex align-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" id="is_active" value="1" checked>
                        <span>Active</span>
                    </label>
                </div>
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
        $('#curriculumTable').DataTable({
            responsive: true,
            language: { search: "", searchPlaceholder: "Search curriculum..." }
        });
    });

    const modal = document.getElementById('curriculumModal');
    const form = document.getElementById('curriculumForm');
    const storeUrl = "{{ route('admin.curriculum.store') }}";

    function openUploadModal() {
        document.getElementById('modalTitle').innerText = 'Upload Curriculum';
        document.getElementById('formMethod').value = 'POST';
        form.action = storeUrl;
        form.reset();
        document.getElementById('file').required = true;
        document.getElementById('fileRequired').style.display = 'inline';
        document.getElementById('fileHint').style.display = 'none';
        document.getElementById('submitBtn').innerText = 'Upload';
        modal.style.display = 'flex';
    }

    function openEditModal(id, title, description, order, isActive) {
        document.getElementById('modalTitle').innerText = 'Edit Curriculum';
        document.getElementById('formMethod').value = 'PUT';
        form.action = `/admin/curriculum/${id}`;
        
        document.getElementById('title').value = title;
        document.getElementById('description').value = description;
        document.getElementById('sort_order').value = order;
        document.getElementById('is_active').checked = isActive;
        
        document.getElementById('file').required = false;
        document.getElementById('fileRequired').style.display = 'none';
        document.getElementById('fileHint').style.display = 'block';
        document.getElementById('submitBtn').innerText = 'Save Changes';
        
        modal.style.display = 'flex';
    }

    function closeModal() {
        modal.style.display = 'none';
    }

    // Close modal on outside click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });
</script>
@endpush
