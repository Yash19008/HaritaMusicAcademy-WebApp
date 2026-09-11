@extends('layouts.main')
@section('title', 'Teacher Resources & Curriculum')
@section('page', 'resources')

@push('styles')
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
        margin-bottom: 0.75rem;
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }
    .resource-desc {
        font-size: 0.85rem;
        color: #475569;
        margin-bottom: 1rem;
        flex-grow: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
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
    <h2>Teacher Resources & Curriculum</h2>
</div>

<div class="nav-tabs">
    <div class="nav-tab active" onclick="switchTab('resources', this)">General Resources</div>
    <div class="nav-tab" onclick="switchTab('curriculum', this)">Curriculum</div>
</div>

<div id="resources" class="tab-content active">
    @if(count($resources) > 0)
        <div class="resource-grid">
            @foreach($resources as $resource)
                @php
                    $ext = strtolower(pathinfo($resource['name'], PATHINFO_EXTENSION));
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
                        <div class="resource-title" title="{{ $resource['name'] }}">{{ $resource['name'] }}</div>
                        <div class="resource-meta">
                            <span class="badge" style="background: #f1f5f9; color: #475569; font-size: 0.7rem; padding: 0.2rem 0.5rem;">{{ strtoupper($ext) }}</span>
                            <span>&bull;</span>
                            <span>{{ $resource['size'] }}</span>
                        </div>
                        <div class="resource-actions">
                            <a href="{{ $resource['url'] }}" class="btn-download-full">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                Download Resource
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5 text-muted">
            <p>No resources available.</p>
        </div>
    @endif
</div>

<div id="curriculum" class="tab-content">
    @if($curricula->count() > 0)
        <div class="resource-grid">
            @foreach($curricula as $item)
                @php
                    $ext = strtolower(pathinfo($item->file_name, PATHINFO_EXTENSION));
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
                        <div class="resource-title" title="{{ $item->title }}">{{ $item->title }}</div>
                        <div class="resource-meta">
                            <span class="badge" style="background: #f1f5f9; color: #475569; font-size: 0.7rem; padding: 0.2rem 0.5rem;">{{ strtoupper($ext) }}</span>
                            <span>&bull;</span>
                            <span>{{ $item->fileSizeForHumans }}</span>
                        </div>
                        @if($item->description)
                            <div class="resource-desc">{{ $item->description }}</div>
                        @endif
                        <div class="resource-actions">
                            <a href="{{ route('teacher.curriculum.download', $item) }}" target="_blank" class="btn-download-full">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                Download Document
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5 text-muted">
            <p>No curriculum documents available.</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function switchTab(tabId, element) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.nav-tab').forEach(el => el.classList.remove('active'));
        
        document.getElementById(tabId).classList.add('active');
        element.classList.add('active');
    }
</script>
@endpush
