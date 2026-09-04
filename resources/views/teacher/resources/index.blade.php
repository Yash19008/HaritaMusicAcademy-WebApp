@extends('layouts.main')
@section('title', 'Teacher Resources')
@section('page', 'resources')

@push('styles')
<style>
    .resources-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    
    .resource-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .resource-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    .resource-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        margin-bottom: 1rem;
        font-size: 24px;
        background: #f8f9fa;
        color: #4a5568;
    }
    
    .resource-icon.pdf { background: #fee2e2; color: #ef4444; }
    .resource-icon.doc, .resource-icon.docx { background: #e0e7ff; color: #4f46e5; }
    .resource-icon.png, .resource-icon.jpg, .resource-icon.jpeg { background: #dcfce7; color: #10b981; }
    .resource-icon.xls, .resource-icon.xlsx { background: #dcfce7; color: #059669; }
    
    .resource-title {
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
        word-break: break-word;
        color: #1f2937;
    }
    
    .resource-meta {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 1.5rem;
        flex-grow: 1;
    }
    
    .resource-download {
        display: block;
        text-align: center;
        padding: 0.75rem;
        background: var(--primary, #000);
        color: #fff;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        transition: background 0.2s;
    }
    
    .resource-download:hover {
        background: var(--primary-dark, #333);
        color: #fff;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: #fff;
        border-radius: 12px;
        border: 1px dashed #e5e7eb;
    }
    
    .empty-state h3 {
        color: #4b5563;
        margin-top: 1rem;
    }
</style>
@endpush

@section('content')
<div class="d-flex align-center justify-between mb-4 flex-wrap gap-2">
    <h2>Teacher Resources</h2>
</div>

@if(count($resources) > 0)
    <div class="resources-grid">
        @foreach($resources as $resource)
            <div class="resource-card">
                @php
                    $ext = strtolower($resource['extension']);
                    $iconClass = 'resource-icon ' . $ext;
                    
                    if ($ext == 'pdf') $icon = '📄';
                    elseif (in_array($ext, ['doc', 'docx'])) $icon = '📝';
                    elseif (in_array($ext, ['png', 'jpg', 'jpeg'])) $icon = '🖼️';
                    elseif (in_array($ext, ['xls', 'xlsx', 'csv'])) $icon = '📊';
                    else $icon = '📁';
                @endphp
                
                <div class="{{ $iconClass }}">{{ $icon }}</div>
                
                <div class="resource-title">{{ $resource['name'] }}</div>
                
                <div class="resource-meta">
                    <div>Size: {{ $resource['size'] }} KB</div>
                    <div>Added: {{ date('M d, Y', strtotime($resource['last_modified'])) }}</div>
                </div>
                
                <a href="{{ route('teacher.resources.download', $resource['name']) }}" class="resource-download">
                    Download
                </a>
            </div>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <div style="font-size: 3rem;">📂</div>
        <h3>No resources available yet.</h3>
        <p class="text-muted">Check back later for newly uploaded study materials, guides, and documents.</p>
    </div>
@endif
@endsection
