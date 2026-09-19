@extends('layouts.main')
@section('title', 'Student Master')
@section('page', 'students')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <style>
        .tabs-container {
            display: flex;
            gap: .5rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: .5rem;
        }

        .tab-btn {
            background: transparent;
            border: none;
            padding: .6rem 1.2rem;
            font-weight: 600;
            font-size: 13.5px;
            color: var(--text-muted);
            cursor: pointer;
            border-radius: var(--radius-sm);
            transition: all .2s;
        }

        .tab-btn.active {
            background-color: var(--primary);
            color: var(--text-white);
        }

        .student-tab-content {
            animation: fadeIn var(--transition-speed) var(--transition-cubic) forwards;
        }

        .table-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--primary);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            flex-shrink: 0;
        }

        .student-check-list {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            padding: .5rem;
            display: flex;
            flex-direction: column;
            gap: .3rem;
            background: var(--bg-card);
        }

        .student-check-list label {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .25rem .4rem;
            cursor: pointer;
            font-size: 13px;
            font-weight: normal;
            border-radius: 4px;
        }

        .student-check-list label:hover {
            background: var(--bg-hover, #f8f9fa);
        }

        /* Custom Searchable Dropdown */
        .search-select-wrap {
            position: relative;
        }

        .search-select-wrap input.search-select-input {
            width: 100%;
            cursor: pointer;
            background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.65rem center;
            background-size: 0.85rem;
            padding-right: 2rem;
        }

        .search-select-wrap input.search-select-input:focus {
            cursor: text;
        }

        .search-select-dropdown {
            display: none;
            position: absolute;
            z-index: 9999;
            left: 0;
            right: 0;
            top: calc(100% + 2px);
            background: var(--bg-card, #fff);
            border: 1px solid #cbd5e1;
            border-radius: var(--radius-sm);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            max-height: 220px;
            overflow-y: auto;
        }

        .search-select-dropdown.open {
            display: block;
        }

        .search-select-option {
            padding: 0.45rem 0.75rem;
            font-size: 12.5px;
            cursor: pointer;
            color: var(--text-main);
        }

        .search-select-option:hover,
        .search-select-option.highlighted {
            background: var(--primary);
            color: #fff;
        }

        .search-select-option.no-results {
            color: var(--text-muted);
            font-style: italic;
        }
    </style>
@endpush

@section('content')


    {{-- TABS --}}
    <div class="tabs-container">
        <button class="tab-btn active" onclick="showStudentsTab('individualTab', this)">👥 Individual Students</button>
        <button class="tab-btn" onclick="showStudentsTab('groupsTab', this)">📂 Group Master</button>
    </div>

    {{-- ─────────────── TAB 1: INDIVIDUAL STUDENTS ─────────────── --}}
    <div id="individualTab" class="student-tab-content">
        <div class="card mb-3">
            <div class="card-body d-flex flex-wrap align-center justify-between gap-3">
                <div class="d-flex gap-2 flex-wrap" style="flex:1; max-width:500px;">
                    <input type="text" id="searchBar" class="form-control" placeholder="Quick filter by name..."
                        style="flex:1;" oninput="if(dtStudents) dtStudents.search(this.value).draw()">
                    <select id="instrumentFilter" class="form-control" style="width:160px;"
                        onchange="if(dtStudents) dtStudents.column(3).search(this.value).draw()">
                        <option value="">All Instruments</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->name }}">{{ $course->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-secondary" onclick="showModal('bulkUploadModal')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="mr-1">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="17 8 12 3 7 8" />
                            <line x1="12" y1="3" x2="12" y2="15" />
                        </svg>
                        Bulk Upload
                    </button>
                    <button class="btn btn-primary" onclick="openAddStudent()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="mr-1">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        Add Student
                    </button>
                </div>
            </div>
        </div>

        <div class="card p-3" style="overflow-x:auto;">
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
                        <th data-priority="1" style="width:80px;text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        <tr>
                            <td class="font-bold" style="color:var(--primary)">
                                STU{{ str_pad($student->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div class="d-flex align-center gap-2">
                                    <span class="table-avatar">{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                                    <div>
                                        <span class="font-semibold">{{ $student->name }}</span>
                                        @if ($student->groups->isNotEmpty())
                                            <br><span class="badge badge-warning"
                                                style="font-size:10px;margin-top:3px;display:inline-block;">👥
                                                {{ $student->groups->first()->name }}</span>
                                        @endif
                                        @if ($student->intro_video_path)
                                            <br><span class="badge"
                                                style="font-size:10px;margin-top:3px;display:inline-block;background:rgba(16,185,129,0.15);color:#10b981;border:1px solid rgba(16,185,129,0.3);">🎥
                                                Intro Video</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $student->email }}</td>
                            <td>
                                @if ($student->courses->isNotEmpty())
                                    {!! $student->courses->pluck('name')->map(fn($n) => '<span class="badge badge-primary">' . $n . '</span>')->join(' ') !!}
                                @else
                                    <span class="badge badge-secondary">N/A</span>
                                @endif
                            </td>
                            <td>{{ $student->teacher->name ?? '—' }}</td>
                            <td class="font-semibold text-center" style="color:var(--primary)">{{ $student->credits }}</td>
                            <td>
                                @php $st = strtolower($student->status ?? 'inactive'); @endphp
                                <span
                                    class="badge {{ $st === 'active' ? 'badge-success' : ($st === 'pending payment' ? 'badge-warning' : 'badge-danger') }}">
                                    {{ ucfirst($student->status ?? 'Inactive') }}
                                </span>
                            </td>
                            <td>
                                <div class="actions-dropdown-container">
                                    <button class="actions-kebab-btn"
                                        onclick="toggleActionsDropdown(event,this)">⋮</button>
                                    <div class="actions-dropdown-menu" style="min-width:175px;">
                                        <button class="actions-dropdown-item"
                                            onclick="openEditStudent({{ $student->id }})">✏️ Edit Profile</button>
                                        @if ($student->intro_video_path)
                                            <button class="actions-dropdown-item" style="color:#10b981;"
                                                onclick="openIntroVideo('{{ addslashes($student->name) }}', '{{ asset('storage/' . $student->intro_video_path) }}', '{{ $student->intro_video_uploaded_at ? \Carbon\Carbon::parse($student->intro_video_uploaded_at)->format('M d, Y') : '' }}')">
                                                🎥 View Intro Video
                                            </button>
                                        @endif
                                        <form method="POST"
                                            action="{{ route('admin.students.resend-credentials', $student) }}"
                                            onsubmit="return confirm('Generate a new password and resend email?')"
                                            style="margin:0;">
                                            @csrf
                                            <button type="submit" class="actions-dropdown-item"
                                                style="width:100%;text-align:left;background:none;border:none;cursor:pointer;padding:.5rem .75rem;">📧
                                                Resend Email</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.students.destroy', $student) }}"
                                            onsubmit="return confirm('Delete this student?')" style="margin:0;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="actions-dropdown-item text-danger"
                                                style="width:100%;text-align:left;background:none;border:none;cursor:pointer;padding:.5rem .75rem;">🗑️
                                                Delete Student</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ─────────────── TAB 2: GROUP MASTER ─────────────── --}}
    <div id="groupsTab" class="student-tab-content" style="display:none;">
        <div class="card mb-3">
            <div class="card-body d-flex flex-wrap align-center justify-between gap-3">
                <div class="d-flex gap-2 flex-grow-1" style="max-width:320px;">
                    <input type="text" id="groupSearchBar" class="form-control" placeholder="Search groups..."
                        oninput="if(dtGroups) dtGroups.search(this.value).draw()">
                </div>
                <button class="btn btn-primary" onclick="openAddGroup()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="mr-1">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Create Group
                </button>
            </div>
        </div>

        <div class="card p-3" style="overflow-x:auto;">
            <h4 class="font-semibold mb-3 text-serif">Registered Groups Ledger</h4>
            <table class="table display responsive nowrap" id="groupsTable" style="width:100%">
                <thead>
                    <tr>
                        <th data-priority="1">Group ID</th>
                        <th data-priority="1">Group Name</th>
                        <th data-priority="2">Enrolled Members</th>
                        <th data-priority="3">Primary Teacher</th>
                        <th data-priority="3" class="text-center">Count</th>
                        <th data-priority="4">Status</th>
                        <th data-priority="1" style="width:80px;text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groups as $group)
                        <tr>
                            <td class="font-bold" style="color:var(--primary)">
                                GRP{{ str_pad($group->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td class="font-semibold">{{ $group->name }}</td>
                            <td style="font-size:12.5px;line-height:1.6;">
                                @if ($group->members->isNotEmpty())
                                    {{ $group->members->pluck('name')->join(', ') }}
                                @else
                                    <span class="text-muted">—No students enrolled—</span>
                                @endif
                            </td>
                            <td>{{ $group->teacher->name ?? '— Unassigned —' }}</td>
                            <td class="text-center font-bold">{{ $group->members_count }} /
                                {{ $maxGroupUsers }}</td>
                            <td>
                                <span
                                    class="badge {{ strtolower($group->status ?? 'active') === 'active' ? 'badge-success' : 'badge-danger' }}">
                                    {{ ucfirst($group->status ?? 'Active') }}
                                </span>
                            </td>
                            <td>
                                <div class="actions-dropdown-container">
                                    <button class="actions-kebab-btn"
                                        onclick="toggleActionsDropdown(event,this)">⋮</button>
                                    <div class="actions-dropdown-menu" style="min-width:130px;right:0;">
                                        <button class="actions-dropdown-item"
                                            onclick="openEditGroup({{ $group->id }}, '{{ addslashes($group->name) }}', '{{ $group->status ?? 'active' }}', [{{ $group->members->pluck('id')->join(',') }}], {{ $group->teacher_id ?? 'null' }})">✏️
                                            Edit Group</button>
                                        <form method="POST" action="{{ route('admin.groups.destroy', $group) }}"
                                            onsubmit="return confirm('Delete this group?')" style="margin:0;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="actions-dropdown-item text-danger"
                                                style="width:100%;text-align:left;background:none;border:none;cursor:pointer;padding:.5rem .75rem;">🗑️
                                                Delete Group</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ═══════════════════ MODALS ═══════════════════ --}}

    {{-- ADD / EDIT STUDENT MODAL --}}
    <div id="studentModal" class="modal-backdrop">
        <div class="modal" style="max-width:680px;">
            <div class="modal-header">
                <h3 id="studentModalTitle" class="font-semibold text-serif">Add New Student</h3>
                <button class="modal-close" onclick="hideModal('studentModal')">×</button>
            </div>
            <form id="studentForm" method="POST" action="{{ route('admin.students.store') }}">
                @csrf
                <input type="hidden" name="_method" id="sfMethod" value="POST">
                <div class="modal-body">
                    <div class="grid grid-2 gap-3">
                        <div class="form-group">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" id="sfName" class="form-control" required
                                placeholder="e.g. Ananya Iyer">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" id="sfEmail" class="form-control" required
                                placeholder="e.g. ananya@gmail.com">
                        </div>
                    </div>
                    <div class="grid grid-2 gap-3">
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" id="sfPhone" class="form-control"
                                placeholder="+91 98765 43210">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Age</label>
                            <input type="number" name="age" id="sfAge" class="form-control"
                                placeholder="e.g. 25" min="1">
                        </div>
                    </div>
                    <div class="grid grid-2 gap-3">
                        <div class="form-group">
                            <label class="form-label">Country</label>
                            <input type="hidden" name="country" id="sfCountry">
                            <div class="search-select-wrap" id="sfCountryWrap">
                                <input type="text" class="form-control search-select-input" id="sfCountrySearch"
                                    placeholder="— Select Country —" autocomplete="off" readonly
                                    onfocus="openSearchSelect('sfCountryWrap')">
                                <div class="search-select-dropdown" id="sfCountryDropdown">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Timezone</label>
                            <input type="hidden" name="timezone" id="sfTimezone" value="Asia/Kolkata">
                            <div class="search-select-wrap" id="sfTimezoneWrap">
                                <input type="text" class="form-control search-select-input" id="sfTimezoneSearch"
                                    placeholder="Asia/Kolkata" autocomplete="off" readonly
                                    onfocus="openSearchSelect('sfTimezoneWrap')">
                                <div class="search-select-dropdown" id="sfTimezoneDropdown">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-2 gap-3">
                        <div class="form-group">
                            <label class="form-label">Enrolled Level</label>
                            <select name="enrolled_level" id="sfLevel" class="form-control">
                                <option value="Foundation Level">Foundation Level</option>
                                <option value="Intermediate Level">Intermediate Level</option>
                                <option value="Advanced Level">Advanced Level</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Assigned Instructor</label>
                            <select name="teacher_id" id="sfTeacher" class="form-control">
                                <option value="">— Select Teacher —</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Music Category (Course)</label>
                        <div class="student-check-list" id="sfCourseList"
                            style="max-height: 120px; display:grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.25rem;">
                            @foreach ($courses as $course)
                                <label
                                    style="display:flex; align-items:center; gap:0.25rem; font-size:12.5px; font-weight:normal; margin:0;">
                                    <input type="checkbox" name="courses[]" value="{{ $course->id }}"
                                        class="sf-course-checkbox"
                                        style="width:15px;height:15px;accent-color:var(--primary);">
                                    <span>{{ $course->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid grid-2 gap-3">
                        <div class="form-group">
                            <label class="form-label">Referral Source</label>
                            <input type="text" name="referral_source" id="sfReferral" class="form-control"
                                placeholder="e.g. Google / Friend">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Joining Date</label>
                            <input type="date" name="joining_date" id="sfJoiningDate" class="form-control"
                                onchange="calculateStudentEndDate()">
                        </div>
                    </div>

                    <div class="grid grid-2 gap-3">
                        <div class="form-group">
                            <label class="form-label">Emergency Contact Name</label>
                            <input type="text" name="emergency_contact_name" id="sfEmgName" class="form-control"
                                placeholder="e.g. Rajesh Iyer">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Emergency Contact Phone</label>
                            <input type="text" name="emergency_contact_phone" id="sfEmgPhone" class="form-control"
                                placeholder="+91 99999 88888">
                        </div>
                    </div>
                    <div class="grid grid-2 gap-3">
                        <div class="form-group">
                            <label class="form-label">Enrollment Format *</label>
                            <select name="enrolled_format" id="sfFormat" class="form-control" required
                                onchange="toggleGroupSelect(this.value)">
                                <option value="Individual">Individual Student</option>
                                <option value="Group">Group Student</option>
                            </select>
                        </div>
                        <div class="form-group" id="groupSelectContainer" style="display:none;">
                            <label class="form-label">Assign to Group</label>
                            <select name="assigned_group" id="sfGroup" class="form-control">
                                <option value="">— Select Group —</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-2 gap-3">
                        <div class="form-group">
                            <label class="form-label">Credit Package</label>
                            <select name="credit_package_id" id="sfCreditPackage" class="form-control"
                                onchange="document.getElementById('sfCredits').value = this.options[this.selectedIndex].dataset.credits || 0; calculateStudentEndDate();">
                                <option value="" data-credits="0">— Custom / No Package —</option>
                                @if (isset($creditPackages))
                                    @foreach ($creditPackages as $package)
                                        <option value="{{ $package->id }}" data-credits="{{ $package->credits }}"
                                            data-format="{{ $package->enrollment_format ?? 'Individual' }}">
                                            {{ $package->name }} ({{ $package->credits }} Credits)</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Initial Credits</label>
                            <input type="number" name="credits" id="sfCredits" class="form-control" value="0"
                                min="0" onchange="calculateStudentEndDate()">
                        </div>
                    </div>

                    <div class="grid grid-2 gap-3">
                        <div class="form-group">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" id="sfEndDate" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Account Status *</label>
                            <select name="status" id="sfStatus" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="hideModal('studentModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Student</button>
                </div>
            </form>
        </div>
    </div>

    {{-- CREATE / EDIT GROUP MODAL --}}
    <div id="groupModal" class="modal-backdrop">
        <div class="modal" style="max-width:520px;">
            <div class="modal-header">
                <h3 id="groupModalTitle" class="font-semibold text-serif">Create New Group</h3>
                <button class="modal-close" onclick="hideModal('groupModal')">×</button>
            </div>
            <form id="groupForm" method="POST" action="{{ route('admin.groups.store') }}">
                @csrf
                <input type="hidden" name="_method" id="gfMethod" value="POST">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Group Name *</label>
                        <input type="text" name="name" id="gfName" class="form-control" required
                            placeholder="e.g. Vocal Harmony Quartet">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Primary Teacher</label>
                        <select name="teacher_id" id="gfTeacher" class="form-control">
                            <option value="">— Unassigned —</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Select Students (1 –
                            {{ $maxGroupUsers }} max)</label>
                        <div class="student-check-list" id="groupStudentsList">
                            @foreach ($students as $student)
                                <label>
                                    <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                        style="width:15px;height:15px;accent-color:var(--primary);">
                                    <span>{{ $student->name }}
                                        @if ($student->courses->isNotEmpty())
                                            <span
                                                style="font-size:11px;color:var(--text-muted)">({{ $student->courses->pluck('name')->join(', ') }})</span>
                                        @endif
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Group Status</label>
                        <select name="status" id="gfStatus" class="form-control">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="hideModal('groupModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btnSaveGroup">Create Group</button>
                </div>
            </form>
        </div>
    </div>

    {{-- BULK UPLOAD MODAL --}}
    <div id="bulkUploadModal" class="modal-backdrop">
        <div class="modal" style="max-width:620px;">
            <div class="modal-header">
                <h3 class="font-semibold text-serif">Bulk Upload Students via CSV</h3>
                <button class="modal-close" onclick="closeBulkUploadModal()">×</button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <p class="text-muted mb-2" style="font-size:13px;">Upload student roster using a CSV file. Columns:
                        <strong>name, email, phone, course, teacher, credits, status</strong>
                    </p>
                    <button class="btn btn-secondary btn-sm" type="button" onclick="downloadSampleCSV()">📥 Download
                        Template</button>
                </div>

                {{-- Drag & Drop Zone --}}
                <div class="drag-drop-zone mb-3" id="csvDragZone"
                    onclick="document.getElementById('csvFileInput').click()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="mb-2" style="color:var(--primary);">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="17 8 12 3 7 8" />
                        <line x1="12" y1="3" x2="12" y2="15" />
                    </svg>
                    <p style="font-size:13px;font-weight:600;">Drag and drop CSV file here, or click to browse</p>
                    <input type="file" id="csvFileInput" style="display:none;" accept=".csv"
                        onchange="handleCSVFileSelect(event)">
                </div>
                
                <div class="mb-3">
                    <label class="d-flex align-center gap-2" style="font-size:13px;cursor:pointer;">
                        <input type="checkbox" id="chkCreateUsers" value="1" checked>
                        Create user accounts and send login credentials via email
                    </label>
                </div>

                {{-- Preview Section (hidden until file selected) --}}
                <div id="bulkPreviewSection" style="display:none;">
                    <h4 class="font-semibold text-serif mb-2" style="font-size:13.5px;">Preview Data Rows</h4>
                    <div style="max-height:220px;overflow-y:auto;border:1px solid var(--border-color);border-radius:8px;">
                        <table class="table" id="bulkPreviewTable" style="font-size:12px;width:100%;">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Course</th>
                                    <th>Teacher</th>
                                    <th class="text-center">Credits</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="bulkPreviewBody"></tbody>
                        </table>
                    </div>
                </div>

                {{-- Result Section (shown after import) --}}
                <div id="bulkResultSection"
                    style="display:none;margin-top:1rem;padding:.75rem 1rem;border-radius:var(--radius-sm);font-size:13px;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeBulkUploadModal()">Cancel</button>
                <button type="button" class="btn btn-primary" id="btnConfirmImport" disabled
                    onclick="confirmCSVImport()">Import Records</button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    {{-- jQuery + DataTables CDN (only on this page) --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        // ── Custom Searchable Dropdown ────────────────────────────────
        const COUNTRIES = ['Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Antigua and Barbuda', 'Argentina',
            'Armenia', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus',
            'Belgium', 'Belize', 'Benin', 'Bhutan', 'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Brazil', 'Brunei',
            'Bulgaria', 'Burkina Faso', 'Burundi', 'Cabo Verde', 'Cambodia', 'Cameroon', 'Canada',
            'Central African Republic', 'Chad', 'Chile', 'China', 'Colombia', 'Comoros',
            'Congo (Republic of the Congo)', 'Costa Rica', "Côte d'Ivoire (Ivory Coast)", 'Croatia', 'Cuba', 'Cyprus',
            'Czech Republic (Czechia)', 'Democratic Republic of the Congo', 'Denmark', 'Djibouti', 'Dominica',
            'Dominican Republic', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia',
            'Eswatini', 'Ethiopia', 'Fiji', 'Finland', 'France', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana',
            'Greece', 'Grenada', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana', 'Haiti', 'Holy See (Vatican City)',
            'Honduras', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland', 'Israel', 'Italy',
            'Jamaica', 'Japan', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Kuwait', 'Kyrgyzstan', 'Laos', 'Latvia',
            'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Madagascar',
            'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Mauritania', 'Mauritius', 'Mexico',
            'Micronesia', 'Moldova', 'Monaco', 'Mongolia', 'Montenegro', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia',
            'Nauru', 'Nepal', 'Netherlands', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'North Korea',
            'North Macedonia', 'Norway', 'Oman', 'Pakistan', 'Palau', 'Palestine', 'Panama', 'Papua New Guinea',
            'Paraguay', 'Peru', 'Philippines', 'Poland', 'Portugal', 'Qatar', 'Romania', 'Russia', 'Rwanda',
            'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Vincent and the Grenadines', 'Samoa', 'San Marino',
            "São Tomé and Príncipe", 'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore',
            'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'South Korea', 'South Sudan', 'Spain',
            'Sri Lanka', 'Sudan', 'Suriname', 'Sweden', 'Switzerland', 'Syria', 'Tajikistan', 'Tanzania', 'Thailand',
            'Timor-Leste', 'Togo', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Türkiye', 'Turkmenistan', 'Tuvalu',
            'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'Uruguay', 'Uzbekistan',
            'Vanuatu', 'Venezuela', 'Vietnam', 'Yemen', 'Zambia', 'Zimbabwe'
        ];
        const TIMEZONES = @json(timezone_identifiers_list());

        function buildDropdown(wrapId, items) {
            const wrap = document.getElementById(wrapId);
            const dd = wrap.querySelector('.search-select-dropdown');
            dd.innerHTML = '';
            const all = ['', ...items]; // blank = clear
            items.forEach(item => {
                const div = document.createElement('div');
                div.className = 'search-select-option';
                div.textContent = item;
                div.dataset.value = item;
                div.addEventListener('mousedown', function(e) {
                    e.preventDefault();
                    selectSearchSelectOption(wrapId, item, item);
                });
                dd.appendChild(div);
            });
        }

        function openSearchSelect(wrapId) {
            const wrap = document.getElementById(wrapId);
            const inp = wrap.querySelector('.search-select-input');
            const dd = wrap.querySelector('.search-select-dropdown');
            inp.readOnly = false;
            inp.select();
            dd.classList.add('open');
            inp.oninput = function() {
                filterDropdown(wrapId, inp.value);
            };
        }

        function filterDropdown(wrapId, query) {
            const wrap = document.getElementById(wrapId);
            const dd = wrap.querySelector('.search-select-dropdown');
            const q = query.toLowerCase();
            let found = 0;
            dd.querySelectorAll('.search-select-option').forEach(opt => {
                const match = opt.dataset.value.toLowerCase().includes(q);
                opt.style.display = match ? '' : 'none';
                if (match) found++;
            });
            let noRes = dd.querySelector('.no-results');
            if (found === 0) {
                if (!noRes) {
                    noRes = document.createElement('div');
                    noRes.className = 'search-select-option no-results';
                    noRes.textContent = 'No results';
                    dd.appendChild(noRes);
                }
                noRes.style.display = '';
            } else if (noRes) noRes.style.display = 'none';
        }

        function selectSearchSelectOption(wrapId, label, value) {
            const wrap = document.getElementById(wrapId);
            const inp = wrap.querySelector('.search-select-input');
            const dd = wrap.querySelector('.search-select-dropdown');
            const hiddenId = wrapId === 'sfCountryWrap' ? 'sfCountry' : 'sfTimezone';
            inp.value = label;
            inp.readOnly = true;
            document.getElementById(hiddenId).value = value;
            dd.classList.remove('open');
        }

        function setSearchSelect(wrapId, value) {
            const wrap = document.getElementById(wrapId);
            const inp = wrap.querySelector('.search-select-input');
            const hiddenId = wrapId === 'sfCountryWrap' ? 'sfCountry' : 'sfTimezone';
            inp.value = value || '';
            inp.readOnly = true;
            document.getElementById(hiddenId).value = value || '';
        }

        function clearSearchSelect(wrapId, placeholder) {
            const wrap = document.getElementById(wrapId);
            const inp = wrap.querySelector('.search-select-input');
            const hiddenId = wrapId === 'sfCountryWrap' ? 'sfCountry' : 'sfTimezone';
            inp.value = '';
            inp.placeholder = placeholder;
            inp.readOnly = true;
            document.getElementById(hiddenId).value = '';
        }

        // Close dropdowns on outside click
        document.addEventListener('click', function(e) {
            document.querySelectorAll('.search-select-wrap').forEach(wrap => {
                if (!wrap.contains(e.target)) {
                    const dd = wrap.querySelector('.search-select-dropdown');
                    const inp = wrap.querySelector('.search-select-input');
                    // If user typed but didn't select, restore previous value
                    const hiddenId = wrap.id === 'sfCountryWrap' ? 'sfCountry' : 'sfTimezone';
                    const saved = document.getElementById(hiddenId).value;
                    inp.value = saved;
                    inp.readOnly = true;
                    dd.classList.remove('open');
                }
            });
        });

        // Initialise dropdown lists once DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            buildDropdown('sfCountryWrap', COUNTRIES);
            buildDropdown('sfTimezoneWrap', TIMEZONES);
            // Set default timezone display
            setSearchSelect('sfTimezoneWrap', 'Asia/Kolkata');
        });

        let dtStudents = null;
        let dtGroups = null;

        // allStudents JSON removed for performance. Data fetched via AJAX.

        document.addEventListener('DOMContentLoaded', function() {
            dtStudents = setupDataTable('studentsTable');
            // groups table is hidden by default – init lazily on tab switch
        });

        // ── Tab switcher ──────────────────────────────────────────────
        function showStudentsTab(tabId, btn) {
            document.querySelectorAll('.student-tab-content').forEach(t => t.style.display = 'none');
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById(tabId).style.display = 'block';
            if (btn) btn.classList.add('active');

            if (tabId === 'groupsTab' && !dtGroups) {
                dtGroups = setupDataTable('groupsTable');
            }
            if (dtStudents) dtStudents.columns.adjust().draw(false);
            if (dtGroups) dtGroups.columns.adjust().draw(false);
        }

        // ── Student modal ─────────────────────────────────────────────
        function calculateStudentEndDate() {
            const joiningDateStr = document.getElementById('sfJoiningDate').value;
            const packageSelect = document.getElementById('sfCreditPackage');

            if (!joiningDateStr || packageSelect.selectedIndex <= 0) return;

            const packageName = packageSelect.options[packageSelect.selectedIndex].text;
            const match = packageName.match(/\((\d+)\s+Months?\)/i);

            if (match && match[1]) {
                const monthsToAdd = parseInt(match[1]);
                const joiningDate = new Date(joiningDateStr);

                // Add months
                joiningDate.setMonth(joiningDate.getMonth() + monthsToAdd);

                // Format to YYYY-MM-DD
                const yyyy = joiningDate.getFullYear();
                const mm = String(joiningDate.getMonth() + 1).padStart(2, '0');
                const dd = String(joiningDate.getDate()).padStart(2, '0');

                document.getElementById('sfEndDate').value = `${yyyy}-${mm}-${dd}`;
            }
        }

        function toggleGroupSelect(val) {
            document.getElementById('groupSelectContainer').style.display = val === 'Group' ? 'flex' : 'none';

            // Filter credit packages
            const packageSelect = document.getElementById('sfCreditPackage');
            if (packageSelect) {
                let foundAny = false;
                for (let i = 0; i < packageSelect.options.length; i++) {
                    const opt = packageSelect.options[i];
                    if (opt.value === "") continue; // skip the 'Custom' option
                    const format = opt.getAttribute('data-format');
                    if (format === val || !format) {
                        opt.hidden = false;
                        opt.disabled = false;
                        foundAny = true;
                    } else {
                        opt.hidden = true;
                        opt.disabled = true;
                    }
                }

                // If currently selected option is hidden, reset selection
                if (packageSelect.selectedIndex > 0 && packageSelect.options[packageSelect.selectedIndex].hidden) {
                    packageSelect.value = '';
                    document.getElementById('sfCredits').value = 0;
                }
            }
        }

        function openAddStudent() {
            document.getElementById('studentForm').reset();
            document.getElementById('sfMethod').value = 'POST';
            document.getElementById('studentForm').action = '{{ route('admin.students.store') }}';
            document.getElementById('studentModalTitle').textContent = 'Add New Student';
            document.getElementById('groupSelectContainer').style.display = 'none';
            document.querySelectorAll('.sf-course-checkbox').forEach(cb => cb.checked = false);

            // Trigger format filter
            document.getElementById('sfFormat').value = 'Individual';
            toggleGroupSelect('Individual');

            // Reset searchable dropdowns
            clearSearchSelect('sfCountryWrap', '— Select Country —');
            setSearchSelect('sfTimezoneWrap', 'Asia/Kolkata');

            showModal('studentModal');
        }

        function openEditStudent(id) {
            // Show loading state (optional)
            document.getElementById('studentModalTitle').textContent = 'Loading...';
            document.getElementById('studentForm').reset();
            showModal('studentModal');

            fetch(`{{ url('admin/students') }}/${id}/json`)
                .then(r => r.json())
                .then(s => {
                    document.getElementById('sfName').value = s.name;
                    document.getElementById('sfEmail').value = s.email;
                    document.getElementById('sfPhone').value = s.phone;
                    document.getElementById('sfAge').value = s.age;
                    setSearchSelect('sfCountryWrap', s.country);
                    setSearchSelect('sfTimezoneWrap', s.timezone || 'Asia/Kolkata');
                    document.getElementById('sfLevel').value = s.enrolled_level;
                    document.querySelectorAll('.sf-course-checkbox').forEach(cb => {
                        cb.checked = s.course_ids && s.course_ids.includes(parseInt(cb.value));
                    });
                    document.getElementById('sfTeacher').value = s.teacher_id;
                    document.getElementById('sfReferral').value = s.referral_source;
                    document.getElementById('sfJoiningDate').value = s.joining_date;
                    document.getElementById('sfEmgName').value = s.emergency_contact_name;
                    document.getElementById('sfEmgPhone').value = s.emergency_contact_phone;
                    document.getElementById('sfFormat').value = s.enrolled_format;
                    document.getElementById('sfCredits').value = s.credits;
                    document.getElementById('sfEndDate').value = s.end_date;
                    document.getElementById('sfCreditPackage').value = s.credit_package_id;
                    document.getElementById('sfStatus').value = s.status;
                    toggleGroupSelect(s.enrolled_format);
                    if (s.group_id) document.getElementById('sfGroup').value = s.group_id;

                    document.getElementById('sfMethod').value = 'PUT';
                    document.getElementById('studentForm').action = '{{ url('admin/students') }}/' + id;
                    document.getElementById('studentModalTitle').textContent = 'Edit Student Profile';
                })
                .catch(err => {
                    alert('Error loading student data.');
                    hideModal('studentModal');
                });
        }

        // ── Group modal ───────────────────────────────────────────────
        function openAddGroup() {
            document.getElementById('groupForm').reset();
            document.getElementById('gfTeacher').value = '';
            // uncheck all
            document.querySelectorAll('#groupStudentsList input[type=checkbox]').forEach(cb => cb.checked = false);
            document.getElementById('gfMethod').value = 'POST';
            document.getElementById('groupForm').action = '{{ route('admin.groups.store') }}';
            document.getElementById('groupModalTitle').textContent = 'Create New Group';
            document.getElementById('btnSaveGroup').textContent = 'Create Group';
            showModal('groupModal');
        }

        function openEditGroup(id, name, status, memberIds, teacherId) {
            document.getElementById('gfName').value = name;
            document.getElementById('gfStatus').value = status;
            document.getElementById('gfTeacher').value = teacherId || '';
            // tick the right checkboxes
            document.querySelectorAll('#groupStudentsList input[type=checkbox]').forEach(cb => {
                cb.checked = memberIds.includes(parseInt(cb.value));
            });
            document.getElementById('gfMethod').value = 'PUT';
            document.getElementById('groupForm').action = '{{ url('admin/student-groups') }}/' + id;
            document.getElementById('groupModalTitle').textContent = 'Edit Group';
            document.getElementById('btnSaveGroup').textContent = 'Save Changes';
            showModal('groupModal');
        }

        // ── Bulk Upload ───────────────────────────────────────────────
        let parsedCSVFile = null;

        // Drag-over highlight
        const dragZone = document.getElementById('csvDragZone');
        if (dragZone) {
            dragZone.addEventListener('dragover', e => {
                e.preventDefault();
                dragZone.classList.add('dragover');
            });
            dragZone.addEventListener('dragleave', () => dragZone.classList.remove('dragover'));
            dragZone.addEventListener('drop', e => {
                e.preventDefault();
                dragZone.classList.remove('dragover');
                const file = e.dataTransfer.files[0];
                if (file && file.name.endsWith('.csv')) processCSVFile(file);
            });
        }

        function handleCSVFileSelect(e) {
            const file = e.target.files[0];
            if (file) processCSVFile(file);
        }

        function processCSVFile(file) {
            parsedCSVFile = file;
            const reader = new FileReader();
            reader.onload = function(e) {
                const lines = e.target.result.split('\n').filter(l => l.trim());
                if (lines.length < 2) {
                    alert('CSV has no data rows.');
                    return;
                }

                const headers = lines[0].split(',').map(h => h.trim().toLowerCase());
                const tbody = document.getElementById('bulkPreviewBody');
                tbody.innerHTML = '';
                let count = 0;

                for (let i = 1; i < lines.length; i++) {
                    const cols = lines[i].split(',').map(c => c.trim());
                    if (cols.length < 2) continue;
                    const row = {};
                    headers.forEach((h, idx) => row[h] = cols[idx] || '');
                    if (!row.email) continue;

                    const tr = document.createElement('tr');
                    tr.innerHTML = `
          <td><strong>${row.name || '—'}</strong></td>
          <td>${row.email}</td>
          <td>${row.phone || '—'}</td>
          <td><span class="badge badge-primary">${row.course || '—'}</span></td>
          <td>${row.teacher || '—'}</td>
          <td class="text-center">${row.credits || '0'}</td>
          <td>${row.status || 'active'}</td>
        `;
                    tbody.appendChild(tr);
                    count++;
                }

                if (count > 0) {
                    document.getElementById('bulkPreviewSection').style.display = 'block';
                    document.getElementById('btnConfirmImport').disabled = false;
                    document.getElementById('bulkResultSection').style.display = 'none';
                    dragZone.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--success)"><polyline points="20 6 9 17 4 12"/></svg>
          <p style="font-size:13px;font-weight:600;color:var(--success)">✅ ${count} records parsed from: ${file.name}</p>
        `;
                } else {
                    alert('No valid rows found. Check your CSV format.');
                }
            };
            reader.readAsText(file);
        }

        function confirmCSVImport() {
            if (!parsedCSVFile) return;
            const btn = document.getElementById('btnConfirmImport');
            btn.disabled = true;
            btn.textContent = 'Importing…';

            const formData = new FormData();
            formData.append('csv_file', parsedCSVFile);
            formData.append('create_users', document.getElementById('chkCreateUsers').checked ? '1' : '0');
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route('admin.students.bulk-import') }}', {
                    method: 'POST',
                    body: formData
                })
                .then(r => r.json())
                .then(data => {
                    const resultEl = document.getElementById('bulkResultSection');
                    resultEl.style.display = 'block';
                    const hasErrors = data.errors && data.errors.length > 0;
                    resultEl.style.background = hasErrors ? '#fef2f2' : '#ecfdf5';
                    resultEl.style.color = hasErrors ? '#dc2626' : '#059669';
                    
                    // Prevent XSS by building DOM nodes instead of raw innerHTML
                    resultEl.innerHTML = `<strong>✅ Imported: ${data.imported}</strong> &nbsp; <strong>⚠️ Skipped: ${data.skipped}</strong>`;
                    
                    if (hasErrors) {
                        const ul = document.createElement('ul');
                        ul.style.marginTop = '.5rem';
                        ul.style.paddingLeft = '1.2rem';
                        ul.style.fontSize = '12px';
                        data.errors.forEach(e => {
                            const li = document.createElement('li');
                            li.textContent = e;
                            ul.appendChild(li);
                        });
                        resultEl.appendChild(ul);
                    }
                    
                    btn.textContent = 'Done';
                    // Reload page after short delay so table refreshes
                    setTimeout(() => window.location.reload(), 1800);
                })
                .catch(() => {
                    alert('Import failed. Please try again.');
                    btn.disabled = false;
                    btn.textContent = 'Import Records';
                });
        }

        function closeBulkUploadModal() {
            parsedCSVFile = null;
            document.getElementById('csvFileInput').value = '';
            document.getElementById('bulkPreviewSection').style.display = 'none';
            document.getElementById('bulkResultSection').style.display = 'none';
            document.getElementById('btnConfirmImport').disabled = true;
            document.getElementById('btnConfirmImport').textContent = 'Import Records';
            document.getElementById('csvDragZone').innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mb-2" style="color:var(--primary);"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
      <p style="font-size:13px;font-weight:600;">Drag and drop CSV file here, or click to browse</p>
      <input type="file" id="csvFileInput" style="display:none;" accept=".csv" onchange="handleCSVFileSelect(event)">
    `;
            hideModal('bulkUploadModal');
        }

        function downloadSampleCSV() {
            const csv =
                'name,email,phone,course,teacher,credits,status\nAnanya Iyer,ananya@gmail.com,+91 98765 43210,Vocal (Carnatic),Meera Sharma,12,active\nAnirudh Kumar,anirudh@gmail.com,+91 88888 77777,Sitar,,8,active';
            const a = document.createElement('a');
            a.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
            a.download = 'student_bulk_upload_template.csv';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }



        // ── Intro Video Viewer ────────────────────────────────────────
        function openIntroVideo(studentName, videoUrl, uploadedAt) {
            document.getElementById('ivViewerTitle').textContent = studentName + "'s Intro Video";
            document.getElementById('ivViewerDate').textContent = uploadedAt ? 'Uploaded on ' + uploadedAt : '';
            const vid = document.getElementById('ivViewerVideo');
            vid.src = videoUrl;
            vid.load();
            document.getElementById('introVideoViewerModal').style.display = 'flex';
        }

        function closeIntroVideoViewer() {
            const vid = document.getElementById('ivViewerVideo');
            vid.pause();
            vid.src = '';
            document.getElementById('introVideoViewerModal').style.display = 'none';
        }
    </script>
@endpush

{{-- ═══════════════ INTRO VIDEO VIEWER MODAL (Admin) ═══════════════ --}}
@push('styles')
    <style>
        #introVideoViewerModal {
            position: fixed;
            inset: 0;
            background: rgba(5, 5, 15, 0.88);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            animation: ivAdminFadeIn 0.3s ease;
        }

        @keyframes ivAdminFadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .iv-viewer-box {
            background: linear-gradient(145deg, #1a1f2e, #111827);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 18px;
            width: 94%;
            max-width: 760px;
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.7);
            overflow: hidden;
            animation: ivAdminSlide 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes ivAdminSlide {
            from {
                transform: translateY(40px) scale(0.97);
                opacity: 0;
            }

            to {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
        }

        .iv-viewer-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        }

        .iv-viewer-header-left h3 {
            font-size: 1rem;
            font-weight: 700;
            color: #fff;
            margin: 0;
        }

        .iv-viewer-header-left span {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.4);
        }

        .iv-viewer-close {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            border: none;
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.2rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .iv-viewer-close:hover {
            background: rgba(239, 68, 68, 0.25);
            color: #ef4444;
        }

        .iv-viewer-video-wrap {
            background: #000;
            position: relative;
        }

        .iv-viewer-video-wrap video {
            display: block;
            width: 100%;
            max-height: 440px;
            object-fit: contain;
            background: #000;
        }

        .iv-viewer-footer {
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
        }

        .iv-viewer-badge {
            font-size: 0.75rem;
            color: #10b981;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            padding: 0.25rem 0.7rem;
            border-radius: 999px;
            font-weight: 600;
        }

        .iv-viewer-close-btn {
            padding: 0.5rem 1.25rem;
            background: rgba(255, 255, 255, 0.07);
            color: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .iv-viewer-close-btn:hover {
            background: rgba(255, 255, 255, 0.13);
            color: #fff;
        }
    </style>
@endpush

<div id="introVideoViewerModal" style="display: none;" onclick="if(event.target===this)closeIntroVideoViewer()">
    <div class="iv-viewer-box">
        <div class="iv-viewer-header">
            <div class="iv-viewer-header-left">
                <h3 id="ivViewerTitle">Student Intro Video</h3>
                <span id="ivViewerDate"></span>
            </div>
            <button class="iv-viewer-close" onclick="closeIntroVideoViewer()" title="Close">✕</button>
        </div>
        <div class="iv-viewer-video-wrap">
            <video id="ivViewerVideo" controls preload="metadata">
                Your browser does not support the video tag.
            </video>
        </div>
        <div class="iv-viewer-footer">
            <span class="iv-viewer-badge">🎥 Student Intro &amp; Instrument Showcase</span>
            <button class="iv-viewer-close-btn" onclick="closeIntroVideoViewer()">Close</button>
        </div>
    </div>
</div>
