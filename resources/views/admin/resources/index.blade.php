@extends('layouts.main')
@section('title', 'Resources Master - Harita Music Academy')
@section('page', 'resource-folders')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<style>
  .btn-upload { background: var(--primary); color: white; padding: 0.5rem 1rem; border-radius: var(--radius-sm); border: none; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 500; font-size: 0.9rem; }
  .btn-upload:hover { background: var(--primary-dark); color: white; }
  .badge { display: inline-block; padding: 0.25rem 0.5rem; border-radius: var(--radius-sm); font-size: 0.75rem; font-weight: 600; }
  .badge-success { background: rgba(46, 204, 113, 0.1); color: #27ae60; }
  .badge-danger { background: rgba(231, 76, 60, 0.1); color: #c0392b; }
  .badge-info { background: rgba(52, 152, 219, 0.1); color: #2980b9; }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header d-flex align-center justify-between">
        <h4 class="font-semibold" style="font-family: var(--font-serif); font-size: 1.25rem;">Resources Folders</h4>
        <button class="btn-upload" onclick="openUploadModal()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
            Create Folder
        </button>
    </div>
    
    <div class="card-body p-3">
        <table class="table display responsive nowrap" id="folderTable" style="width:100%">
            <thead>
                <tr>
                    <th>Folder Name</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th style="width: 150px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($folders as $item)
                <tr>
                    <td><strong>{{ $item->name }}</strong></td>
                    <td>
                        @if($item->type === 'curriculum')
                            <span class="badge badge-info">Curriculum</span>
                        @elseif($item->type === 'student')
                            <span class="badge" style="background: rgba(52, 152, 219, 0.1); color: #2980b9;">Student Specific</span>
                        @elseif($item->type === 'teacher')
                            <span class="badge" style="background: rgba(155, 89, 182, 0.1); color: #8e44ad;">Teacher Specific</span>
                        @else
                            <span class="badge badge-success">General (Both)</span>
                        @endif
                    </td>
                    <td title="{{ $item->description }}">{{ Str::limit($item->description, 40) }}</td>
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
                            <a href="{{ route('admin.resource-folders.files.index', $item) }}" class="btn btn-sm d-flex align-center gap-1" style="background: rgba(46, 204, 113, 0.1); color: #27ae60; border:none; text-decoration:none; padding: 0.25rem 0.5rem; font-weight: 600; font-size: 0.8rem;" title="Manage Files">
                                📁 Upload Files
                            </a>
                            <button class="btn btn-sm" style="background: rgba(52, 152, 219, 0.1); color: #2980b9; border:none; padding: 0.25rem 0.5rem; font-weight: 600; font-size: 0.8rem;" 
                                onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->name) }}', '{{ $item->type }}', '{{ addslashes($item->description) }}', {{ $item->sort_order }}, {{ $item->is_active ? 'true' : 'false' }})" title="Edit Folder">
                                ✏️ Edit
                            </button>
                            <form action="{{ route('admin.resource-folders.destroy', $item) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this folder and ALL files inside it?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background: rgba(231, 76, 60, 0.1); color: #e74c3c; border:none; padding: 0.25rem 0.5rem; font-weight: 600; font-size: 0.8rem;" title="Delete">
                                    🗑️ Delete
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
<div class="modal-overlay" id="folderModal" style="display: none; position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.5); z-index: 999; align-items:center; justify-content:center;">
    <div class="modal-content" style="background: white; border-radius: var(--radius-md); padding: 1.5rem; width: 100%; max-width: 500px; box-shadow: var(--shadow-lg);">
        <div class="d-flex justify-between align-center mb-3">
            <h3 id="modalTitle" style="font-family: var(--font-serif); margin:0;">Create Folder</h3>
            <button type="button" onclick="closeModal()" style="background:none; border:none; cursor:pointer; font-size:1.2rem;">&times;</button>
        </div>
        
        <form id="folderForm" action="{{ route('admin.resource-folders.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            
            <div class="mb-3">
                <label class="form-label d-block mb-1">Folder Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control w-100" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label d-block mb-1">Type <span class="text-danger">*</span></label>
                <select name="type" id="type" class="form-control w-100" required>
                    <option value="general">General Resource (Both)</option>
                    <option value="student">Student Specific</option>
                    <option value="teacher">Teacher Specific</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label d-block mb-1">Description</label>
                <textarea name="description" id="description" class="form-control w-100" rows="3"></textarea>
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
                <button type="submit" class="btn btn-primary" id="submitBtn">Save</button>
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
        $('#folderTable').DataTable({
            responsive: true,
            language: { search: "", searchPlaceholder: "Search folders..." }
        });
    });

    const modal = document.getElementById('folderModal');
    const form = document.getElementById('folderForm');
    const storeUrl = "{{ route('admin.resource-folders.store') }}";

    function openUploadModal() {
        document.getElementById('modalTitle').innerText = 'Create Folder';
        document.getElementById('formMethod').value = 'POST';
        form.action = storeUrl;
        form.reset();
        document.getElementById('submitBtn').innerText = 'Save';
        modal.style.display = 'flex';
    }

    function openEditModal(id, name, type, description, order, isActive) {
        document.getElementById('modalTitle').innerText = 'Edit Folder';
        document.getElementById('formMethod').value = 'PUT';
        form.action = `/admin/resource-folders/${id}`;
        
        document.getElementById('name').value = name;
        document.getElementById('type').value = type;
        document.getElementById('description').value = description;
        document.getElementById('sort_order').value = order;
        document.getElementById('is_active').checked = isActive;
        
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
