@extends('layouts.main')
@section('page', 'students')

@push('styles')
<style>
.tabs-container {
      display: flex;
      gap: 0.5rem;
      margin-bottom: 1.5rem;
      border-bottom: 1px solid var(--border-color);
      padding-bottom: 0.5rem;
    }

    .tab-btn {
      background: transparent;
      border: none;
      padding: 0.6rem 1.2rem;
      font-weight: 600;
      font-size: 13.5px;
      color: var(--text-muted);
      cursor: pointer;
      border-radius: var(--radius-sm);
      transition: all 0.2s;
    }

    .tab-btn.active {
      background-color: var(--primary);
      color: var(--text-white);
    }

    .student-tab-content {
      animation: fadeIn var(--transition-speed) var(--transition-cubic) forwards;
    }
</style>
@endpush

@section('content')
<!-- TABS CONTAINER -->
      <div class="tabs-container">
        <button class="tab-btn active" onclick="showStudentsTab('individualTab', this)">👥 Individual Students</button>
        <button class="tab-btn" onclick="showStudentsTab('groupsTab', this)">📂 Group Master</button>
      </div>

      <!-- TAB 1: INDIVIDUAL STUDENTS VIEW -->
      <div id="individualTab" class="student-tab-content">
        <!-- Filter Card -->
        <div class="card mb-3">
          <div class="card-body d-flex flex-wrap align-center justify-between gap-3">
            <div class="d-flex gap-2 flex-wrap" style="flex: 1; max-width: 500px;">
              <input type="text" id="searchBar" class="form-control" placeholder="Quick filter by name..." style="flex: 1;">
              <select id="instrumentFilter" class="form-control" style="width: 160px;">
                <option value="">All Instruments</option>
                <option value="Vocal">Vocals</option>
                <option value="Sitar">Sitar</option>
                <option value="Violin">Violin</option>
                <option value="Flute">Flute</option>
                <option value="Tabla">Tabla</option>
              </select>
            </div>
            <div class="d-flex gap-2">
              <button class="btn btn-secondary" onclick="showModal('bulkUploadModal')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                  <polyline points="17 8 12 3 7 8"></polyline>
                  <line x1="12" y1="3" x2="12" y2="15"></line>
                </svg>
                Bulk Upload
              </button>
              <button class="btn btn-primary" onclick="openAddModal()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                  <line x1="12" y1="5" x2="12" y2="19"></line>
                  <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Add Student
              </button>
            </div>
          </div>
        </div>

        <!-- Individual Students Table -->
        <div class="card p-3" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
          <table class="table display responsive nowrap" id="studentsTable" style="width:100%">
            <thead>
              <tr>
                <th data-priority="7">ID</th>
                <th data-priority="1">Student Name</th>
                <th data-priority="6">Email</th>
                <th data-priority="3">Instrument</th>
<!-- TABS CONTAINER -->
      <div class="tabs-container">
        <button class="tab-btn active" onclick="showStudentsTab('individualTab', this)">👥 Individual Students</button>
        <button class="tab-btn" onclick="showStudentsTab('groupsTab', this)">📂 Group Master</button>
      </div>

      <!-- TAB 1: INDIVIDUAL STUDENTS VIEW -->
      <div id="individualTab" class="student-tab-content">
        <!-- Filter Card -->
        <div class="card mb-3">
          <div class="card-body d-flex flex-wrap align-center justify-between gap-3">
            <div class="d-flex gap-2 flex-wrap" style="flex: 1; max-width: 500px;">
              <input type="text" id="searchBar" class="form-control" placeholder="Quick filter by name..." style="flex: 1;">
              <select id="instrumentFilter" class="form-control" style="width: 160px;">
                <option value="">All Instruments</option>
                <option value="Vocal">Vocals</option>
                <option value="Sitar">Sitar</option>
                <option value="Violin">Violin</option>
                <option value="Flute">Flute</option>
                <option value="Tabla">Tabla</option>
              </select>
            </div>
            <div class="d-flex gap-2">
              <button class="btn btn-secondary" onclick="showModal('bulkUploadModal')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                  <polyline points="17 8 12 3 7 8"></polyline>
                  <line x1="12" y1="3" x2="12" y2="15"></line>
                </svg>
                Bulk Upload
              </button>
              <button class="btn btn-primary" onclick="openAddModal()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                  <line x1="12" y1="5" x2="12" y2="19"></line>
                  <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Add Student
              </button>
            </div>
          </div>
        </div>

        <!-- Individual Students Table -->
        <div class="card p-3" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
          <table class="table display responsive nowrap" id="studentsTable" style="width:100%">
            <thead>
              <tr>
                <th data-priority="7">ID</th>
                <th data-priority="1">Student Name</th>
                <th data-priority="6">Email</th>
                <th data-priority="3">Instrument</th>
                <th data-priority="4">Teacher</th>
                <th data-priority="5" class="text-center">Credits</th>
                <th data-priority="2">Status</th>
                <th data-priority="1" style="width: 80px; text-align: center;">Actions</th>
              </tr>
            </thead>
              @forelse($students as $student)
                <tr>
                  <td>#{{ str_pad($student->id, 4, '0', STR_PAD_LEFT) }}</td>
                  <td>
                    <div class="d-flex align-center gap-2">
                      <div class="avatar">{{ substr($student->name, 0, 2) }}</div>
                      <div>
                        <div class="font-semibold" style="color:var(--text-main)">{{ $student->name }}</div>
                      </div>
                    </div>
                  </td>
                  <td>{{ $student->email }}</td>
                  <td>{{ $student->course->name ?? 'N/A' }}</td>
                  <td>{{ $student->teacher->name ?? 'Not Assigned' }}</td>
                  <td class="text-center font-semibold" style="color:var(--primary)">{{ $student->credits }}</td>
                  <td>
                    <span class="badge {{ $student->status === 'active' ? 'badge-success' : 'badge-danger' }}">{{ ucfirst($student->status) }}</span>
                  </td>
                  <td>
                    <div class="d-flex gap-1 justify-center">
                      @can('students.edit')
                      <button class="btn btn-secondary btn-icon" title="Edit Student">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                      </button>
                      @endcan
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center py-4">No students found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- TAB 2: GROUP MASTER VIEW -->
      <div id="groupsTab" class="student-tab-content">
        <!-- Group Master Actions Card -->
        <div class="card mb-3">
          <div class="card-body d-flex flex-wrap align-center justify-between gap-3">
            <div class="d-flex gap-2 flex-grow-1" style="max-width: 320px;">
              <input type="text" id="groupSearchBar" class="form-control" placeholder="Search groups...">
            </div>
            @can('students.create')
            <button class="btn btn-primary" onclick="openAddGroupModal()">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
              </svg>
              Create Group
            </button>
            @endcan
          </div>
        </div>

        <!-- Groups Table -->
        <div class="card p-3" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
          <h4 class="font-semibold mb-3 text-serif">Registered Groups Ledger</h4>
          <table class="table display responsive nowrap" id="groupsTable" style="width:100%">
            <thead>
              <tr>
                <th data-priority="1">Group ID</th>
                <th data-priority="1">Group Name</th>
                <th data-priority="2">Enrolled Members</th>
                <th data-priority="3" class="text-center">Student Count</th>
                <th data-priority="4">Status</th>
                <th data-priority="1" style="width: 80px; text-align: center;">Actions</th>
              </tr>
            </thead>
            <tbody id="groupsTableBody">
              <!-- Populated dynamically -->
            </tbody>
          </table>
        </div>
      </div>
@endsection
