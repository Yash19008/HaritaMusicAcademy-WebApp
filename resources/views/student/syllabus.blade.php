@extends('layouts.main')
@section('title', 'My Syllabus - Harita Music Academy')
@section('page', 'syllabus')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<style>
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
<div class="card mb-4">
    <div class="card-body d-flex flex-wrap align-center justify-between gap-3">
        <div>
            <h2 style="font-size: 1.1rem; font-weight: 800; color: var(--text-main); margin: 0;">Course Syllabus</h2>
            <p style="font-size: 0.78rem; color: var(--text-muted); margin: 0.2rem 0 0;">
                Download curriculum guides and study materials for your enrolled courses.
            </p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-3">
        <table class="table display responsive nowrap" id="studentSyllabusTable" style="width:100%">
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
                                <a href="{{ route('student.syllabus.download', $syllabus) }}" target="_blank" class="btn-download">
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
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
    $(document).ready(function () {
        $('#studentSyllabusTable').DataTable({
            responsive: true,
            language: { search: "", searchPlaceholder: "Search materials..." }
        });
    });
</script>
@endpush
