@extends('layouts.main')
@section('title', 'Teacher Resources & Curriculum')
@section('page', 'resources')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
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
    
    .btn-download {
        background: rgba(var(--primary-rgb), 0.1);
        color: var(--primary);
        padding: 0.25rem 0.75rem;
        border-radius: var(--radius-sm);
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        transition: all 0.2s;
    }
    .btn-download:hover {
        background: var(--primary);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="d-flex align-center justify-between mb-4 flex-wrap gap-2">
    <h2>Teacher Resources & Curriculum</h2>
</div>

<div class="nav-tabs">
    <div class="nav-tab active" onclick="switchTab('resources', this)">General Resources</div>
    <div class="nav-tab" onclick="switchTab('curriculum', this)">Curriculum</div>
</div>

<div class="card">
    <div class="card-body p-3">
        <div id="resources" class="tab-content active">
            <table class="table display responsive nowrap w-100" id="resourcesTable">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Type</th>
                        <th>Size</th>
                        <th style="width: 100px; text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resources as $resource)
                        @php
                            $ext = strtolower(pathinfo($resource['name'], PATHINFO_EXTENSION));
                        @endphp
                        <tr>
                            <td>{{ $resource['name'] }}</td>
                            <td><span class="badge" style="background: #f1f5f9; color: #475569;">{{ strtoupper($ext) }}</span></td>
                            <td>{{ $resource['size'] }}</td>
                            <td class="text-center">
                                <a href="{{ $resource['url'] }}" class="btn-download">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                    Download
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div id="curriculum" class="tab-content">
            <table class="table display responsive nowrap w-100" id="curriculumTable">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Size</th>
                        <th style="width: 100px; text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($curricula as $item)
                        @php
                            $ext = strtolower(pathinfo($item->file_name, PATHINFO_EXTENSION));
                        @endphp
                        <tr>
                            <td><strong>{{ $item->title }}</strong></td>
                            <td title="{{ $item->description }}">{{ Str::limit($item->description, 40) }}</td>
                            <td><span class="badge" style="background: #f1f5f9; color: #475569;">{{ strtoupper($ext) }}</span></td>
                            <td>{{ $item->fileSizeForHumans }}</td>
                            <td class="text-center">
                                <a href="{{ route('teacher.curriculum.download', $item) }}" target="_blank" class="btn-download">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                    Download
                                </a>
                            </td>
                        </tr>
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
    $(document).ready(function () {
        $('#resourcesTable').DataTable({
            responsive: true,
            language: { search: "", searchPlaceholder: "Search resources..." }
        });
        $('#curriculumTable').DataTable({
            responsive: true,
            language: { search: "", searchPlaceholder: "Search curriculum..." }
        });
    });

    function switchTab(tabId, element) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.nav-tab').forEach(el => el.classList.remove('active'));
        
        document.getElementById(tabId).classList.add('active');
        element.classList.add('active');
        
        // Redraw table when tab becomes visible to fix responsive sizing issues
        if(tabId === 'resources') {
            $('#resourcesTable').DataTable().columns.adjust().responsive.recalc();
        } else if(tabId === 'curriculum') {
            $('#curriculumTable').DataTable().columns.adjust().responsive.recalc();
        }
    }
</script>
@endpush
