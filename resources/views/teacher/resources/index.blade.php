@extends('layouts.main')
@section('title', 'Teacher Resources & Syllabus')
@section('page', 'resources')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<!--<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">-->
<style>
    .nav-tabs {
        border-bottom: 2px solid rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
        display: flex;
        gap: 1rem;
    }
    .nav-tab {
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        color: #6b7280;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        transition: all 0.2s;
    }
    .nav-tab:hover {
        color: var(--primary);
    }
    .nav-tab.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
    }
    
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    
    /* Accordion styles */
    .folder-accordion {
        margin-bottom: 1rem;
        border: 1px solid rgba(0,0,0,0.08);
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
    }
    .folder-header {
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        background: #f8fafc;
        transition: background 0.2s;
    }
    .folder-header:hover {
        background: #f1f5f9;
    }
    .folder-title {
        font-weight: 600;
        font-size: 1.1rem;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .folder-icon {
        color: var(--primary);
    }
    .folder-meta {
        font-size: 0.85rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .folder-chevron {
        transition: transform 0.3s;
    }
    .folder-accordion.active .folder-chevron {
        transform: rotate(180deg);
    }
    .folder-body {
        display: none;
        padding: 1.5rem;
        border-top: 1px solid rgba(0,0,0,0.05);
    }
    .folder-accordion.active .folder-body {
        display: block;
    }
    
    /* Grid styles */
    .resource-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    .resource-card {
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.2s;
        background: #fff;
        display: flex;
        flex-direction: column;
    }
    .resource-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }
    .resource-thumb {
        background: #f8fafc;
        height: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        color: #ef4444; /* red for pdf by default */
    }
    .resource-thumb.doc, .resource-thumb.docx { color: #3b82f6; }
    .resource-thumb.xls, .resource-thumb.xlsx { color: #22c55e; }
    .resource-thumb.ppt, .resource-thumb.pptx { color: #f97316; }
    .resource-thumb.zip { color: #8b5cf6; }

    .resource-info {
        padding: 1.25rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    .resource-title {
        font-weight: 600;
        font-size: 1.05rem;
        margin-bottom: 0.5rem;
        color: #1e293b;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.4;
    }
    .resource-meta {
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 1rem;
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }
    .resource-actions {
        margin-top: auto;
    }
    .btn-download-full {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        padding: 0.6rem;
        background: rgba(var(--primary-rgb, 79, 70, 229), 0.1);
        color: var(--primary, #4f46e5);
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.2s;
    }
    .btn-download-full:hover {
        background: var(--primary, #4f46e5);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="d-flex align-center justify-between mb-4 flex-wrap gap-2">
    <h2>Teacher Resources & Syllabus</h2>
</div>

<div class="nav-tabs">
    <div class="nav-tab active" onclick="switchTab('resources', this)">General</div>
    <div class="nav-tab" onclick="switchTab('syllabus', this)">Syllabus</div>
</div>

<!-- General Resources Tab -->
<div id="resources" class="tab-content active">
    @if($generalFolders->count() > 0)
        @foreach($generalFolders as $folder)
            <div class="folder-accordion">
                <div class="folder-header" onclick="toggleFolder(this)">
                    <div class="folder-title">
                        <svg class="folder-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                        {{ $folder->name }}
                    </div>
                    <div class="folder-meta">
                        <span>{{ $folder->files->count() }} files</span>
                        <svg class="folder-chevron" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </div>
                </div>
                <div class="folder-body">
                    @if($folder->description)
                        <p class="text-muted mb-4" style="font-size: 0.9rem;">{{ $folder->description }}</p>
                    @endif
                    
                    @if($folder->files->count() > 0)
                        <div class="resource-grid">
                            @foreach($folder->files as $file)
                                @php
                                    $ext = strtolower(pathinfo($file->file_name, PATHINFO_EXTENSION));
                                @endphp
                                <div class="resource-card">
                                    <div class="resource-thumb {{ $ext }}">
                                        <div style="text-align: center;">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width: 48px; height: 48px; margin-bottom: 8px;">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                            </svg>
                                            <div style="font-weight: bold; font-size: 0.85rem; letter-spacing: 1px;">{{ strtoupper($ext) }}</div>
                                        </div>
                                    </div>
                                    <div class="resource-info">
                                        <div class="resource-title" title="{{ $file->title }}">{{ $file->title }}</div>
                                        <div class="resource-meta">
                                            <span class="badge" style="background: #f1f5f9; color: #475569; font-size: 0.7rem; padding: 0.2rem 0.5rem;">{{ strtoupper($ext) }}</span>
                                            <span>&bull;</span>
                                            <span>{{ $file->fileSizeForHumans }}</span>
                                        </div>
                                        <div class="resource-actions">
                                            <a href="{{ Storage::disk('public')->url($file->file_path) }}" target="_blank" class="btn-download-full">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                                Download Resource
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <p>No files uploaded to this folder yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <div class="text-center py-5 text-muted">
            <p>No general resources available.</p>
        </div>
    @endif
</div>

<!-- Syllabus Tab -->
<div id="syllabus" class="tab-content">
    <div class="card">
        <div class="card-body p-3">
            <table class="table display nowrap" id="teacherSyllabusTable" style="width:100%">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Size</th>
                        <th style="width: 100px; text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($courses as $course)
                        @foreach($course->syllabi as $syllabus)
                            @php
                                $ext = strtolower(pathinfo($syllabus->file_name, PATHINFO_EXTENSION));
                            @endphp
                            <tr>
                                <td><strong>{{ $course->name }}</strong></td>
                                <td>{{ $syllabus->title }}</td>
                                <td title="{{ $syllabus->description }}">{{ Str::limit($syllabus->description, 40) }}</td>
                                <td><span class="badge" style="background: #f1f5f9; color: #475569;">{{ strtoupper($ext) }}</span></td>
                                <td>{{ $syllabus->fileSizeForHumans }}</td>
                                <td class="text-center">
                                    <a href="{{ route('student.syllabus.download', $syllabus) }}" target="_blank" class="btn-download-full" style="padding: 0.4rem 0.6rem; display: inline-flex; width: auto;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                        Download
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
    function switchTab(tabId, element) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.nav-tab').forEach(el => el.classList.remove('active'));
        
        document.getElementById(tabId).classList.add('active');
        element.classList.add('active');
    }

    function toggleFolder(headerElement) {
        const accordion = headerElement.closest('.folder-accordion');
        accordion.classList.toggle('active');
    }

    $(document).ready(function () {
        $('#teacherSyllabusTable').DataTable({
            responsive: true,
            language: { search: "", searchPlaceholder: "Search syllabus..." }
        });
    });
</script>
@endpush
