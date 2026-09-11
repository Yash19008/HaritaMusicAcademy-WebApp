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
                        <option value="Vocal">Vocals</option>
                        <option value="Sitar">Sitar</option>
                        <option value="Violin">Violin</option>
                        <option value="Flute">Flute</option>
                        <option value="Tabla">Tabla</option>
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
                                    <button class="actions-kebab-btn" onclick="toggleActionsDropdown(event,this)">⋮</button>
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
                                {{ \App\Models\Setting::get('max_group_users', 4) }}</td>
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
                            <select name="country" id="sfCountry" class="form-control">
                                <option value="">— Select Country —</option>
                                <option value="Afghanistan">Afghanistan</option>
                                <option value="Albania">Albania</option>
                                <option value="Algeria">Algeria</option>
                                <option value="Andorra">Andorra</option>
                                <option value="Angola">Angola</option>
                                <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                <option value="Argentina">Argentina</option>
                                <option value="Armenia">Armenia</option>
                                <option value="Australia">Australia</option>
                                <option value="Austria">Austria</option>
                                <option value="Azerbaijan">Azerbaijan</option>
                                <option value="Bahamas">Bahamas</option>
                                <option value="Bahrain">Bahrain</option>
                                <option value="Bangladesh">Bangladesh</option>
                                <option value="Barbados">Barbados</option>
                                <option value="Belarus">Belarus</option>
                                <option value="Belgium">Belgium</option>
                                <option value="Belize">Belize</option>
                                <option value="Benin">Benin</option>
                                <option value="Bhutan">Bhutan</option>
                                <option value="Bolivia">Bolivia</option>
                                <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                <option value="Botswana">Botswana</option>
                                <option value="Brazil">Brazil</option>
                                <option value="Brunei">Brunei</option>
                                <option value="Bulgaria">Bulgaria</option>
                                <option value="Burkina Faso">Burkina Faso</option>
                                <option value="Burundi">Burundi</option>
                                <option value="Cabo Verde">Cabo Verde</option>
                                <option value="Cambodia">Cambodia</option>
                                <option value="Cameroon">Cameroon</option>
                                <option value="Canada">Canada</option>
                                <option value="Central African Republic">Central African Republic</option>
                                <option value="Chad">Chad</option>
                                <option value="Chile">Chile</option>
                                <option value="China">China</option>
                                <option value="Colombia">Colombia</option>
                                <option value="Comoros">Comoros</option>
                                <option value="Congo (Republic of the Congo)">Congo (Republic of the Congo)</option>
                                <option value="Costa Rica">Costa Rica</option>
                                <option value="Côte d'Ivoire (Ivory Coast)">Côte d'Ivoire (Ivory Coast)</option>
                                <option value="Croatia">Croatia</option>
                                <option value="Cuba">Cuba</option>
                                <option value="Cyprus">Cyprus</option>
                                <option value="Czech Republic (Czechia)">Czech Republic (Czechia)</option>
                                <option value="Democratic Republic of the Congo">Democratic Republic of the Congo</option>
                                <option value="Denmark">Denmark</option>
                                <option value="Djibouti">Djibouti</option>
                                <option value="Dominica">Dominica</option>
                                <option value="Dominican Republic">Dominican Republic</option>
                                <option value="Ecuador">Ecuador</option>
                                <option value="Egypt">Egypt</option>
                                <option value="El Salvador">El Salvador</option>
                                <option value="Equatorial Guinea">Equatorial Guinea</option>
                                <option value="Eritrea">Eritrea</option>
                                <option value="Estonia">Estonia</option>
                                <option value="Eswatini">Eswatini</option>
                                <option value="Ethiopia">Ethiopia</option>
                                <option value="Fiji">Fiji</option>
                                <option value="Finland">Finland</option>
                                <option value="France">France</option>
                                <option value="Gabon">Gabon</option>
                                <option value="Gambia">Gambia</option>
                                <option value="Georgia">Georgia</option>
                                <option value="Germany">Germany</option>
                                <option value="Ghana">Ghana</option>
                                <option value="Greece">Greece</option>
                                <option value="Grenada">Grenada</option>
                                <option value="Guatemala">Guatemala</option>
                                <option value="Guinea">Guinea</option>
                                <option value="Guinea-Bissau">Guinea-Bissau</option>
                                <option value="Guyana">Guyana</option>
                                <option value="Haiti">Haiti</option>
                                <option value="Holy See (Vatican City)">Holy See (Vatican City)</option>
                                <option value="Honduras">Honduras</option>
                                <option value="Hungary">Hungary</option>
                                <option value="Iceland">Iceland</option>
                                <option value="India" selected>India</option>
                                <option value="Indonesia">Indonesia</option>
                                <option value="Iran">Iran</option>
                                <option value="Iraq">Iraq</option>
                                <option value="Ireland">Ireland</option>
                                <option value="Israel">Israel</option>
                                <option value="Italy">Italy</option>
                                <option value="Jamaica">Jamaica</option>
                                <option value="Japan">Japan</option>
                                <option value="Jordan">Jordan</option>
                                <option value="Kazakhstan">Kazakhstan</option>
                                <option value="Kenya">Kenya</option>
                                <option value="Kiribati">Kiribati</option>
                                <option value="Kuwait">Kuwait</option>
                                <option value="Kyrgyzstan">Kyrgyzstan</option>
                                <option value="Laos">Laos</option>
                                <option value="Latvia">Latvia</option>
                                <option value="Lebanon">Lebanon</option>
                                <option value="Lesotho">Lesotho</option>
                                <option value="Liberia">Liberia</option>
                                <option value="Libya">Libya</option>
                                <option value="Liechtenstein">Liechtenstein</option>
                                <option value="Lithuania">Lithuania</option>
                                <option value="Luxembourg">Luxembourg</option>
                                <option value="Madagascar">Madagascar</option>
                                <option value="Malawi">Malawi</option>
                                <option value="Malaysia">Malaysia</option>
                                <option value="Maldives">Maldives</option>
                                <option value="Mali">Mali</option>
                                <option value="Malta">Malta</option>
                                <option value="Marshall Islands">Marshall Islands</option>
                                <option value="Mauritania">Mauritania</option>
                                <option value="Mauritius">Mauritius</option>
                                <option value="Mexico">Mexico</option>
                                <option value="Micronesia">Micronesia</option>
                                <option value="Moldova">Moldova</option>
                                <option value="Monaco">Monaco</option>
                                <option value="Mongolia">Mongolia</option>
                                <option value="Montenegro">Montenegro</option>
                                <option value="Morocco">Morocco</option>
                                <option value="Mozambique">Mozambique</option>
                                <option value="Myanmar">Myanmar</option>
                                <option value="Namibia">Namibia</option>
                                <option value="Nauru">Nauru</option>
                                <option value="Nepal">Nepal</option>
                                <option value="Netherlands">Netherlands</option>
                                <option value="New Zealand">New Zealand</option>
                                <option value="Nicaragua">Nicaragua</option>
                                <option value="Niger">Niger</option>
                                <option value="Nigeria">Nigeria</option>
                                <option value="North Korea">North Korea</option>
                                <option value="North Macedonia">North Macedonia</option>
                                <option value="Norway">Norway</option>
                                <option value="Oman">Oman</option>
                                <option value="Pakistan">Pakistan</option>
                                <option value="Palau">Palau</option>
                                <option value="Palestine">Palestine</option>
                                <option value="Panama">Panama</option>
                                <option value="Papua New Guinea">Papua New Guinea</option>
                                <option value="Paraguay">Paraguay</option>
                                <option value="Peru">Peru</option>
                                <option value="Philippines">Philippines</option>
                                <option value="Poland">Poland</option>
                                <option value="Portugal">Portugal</option>
                                <option value="Qatar">Qatar</option>
                                <option value="Romania">Romania</option>
                                <option value="Russia">Russia</option>
                                <option value="Rwanda">Rwanda</option>
                                <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                <option value="Saint Lucia">Saint Lucia</option>
                                <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                                <option value="Samoa">Samoa</option>
                                <option value="San Marino">San Marino</option>
                                <option value="São Tomé and Príncipe">São Tomé and Príncipe</option>
                                <option value="Saudi Arabia">Saudi Arabia</option>
                                <option value="Senegal">Senegal</option>
                                <option value="Serbia">Serbia</option>
                                <option value="Seychelles">Seychelles</option>
                                <option value="Sierra Leone">Sierra Leone</option>
                                <option value="Singapore">Singapore</option>
                                <option value="Slovakia">Slovakia</option>
                                <option value="Slovenia">Slovenia</option>
                                <option value="Solomon Islands">Solomon Islands</option>
                                <option value="Somalia">Somalia</option>
                                <option value="South Africa">South Africa</option>
                                <option value="South Korea">South Korea</option>
                                <option value="South Sudan">South Sudan</option>
                                <option value="Spain">Spain</option>
                                <option value="Sri Lanka">Sri Lanka</option>
                                <option value="Sudan">Sudan</option>
                                <option value="Suriname">Suriname</option>
                                <option value="Sweden">Sweden</option>
                                <option value="Switzerland">Switzerland</option>
                                <option value="Syria">Syria</option>
                                <option value="Tajikistan">Tajikistan</option>
                                <option value="Tanzania">Tanzania</option>
                                <option value="Thailand">Thailand</option>
                                <option value="Timor-Leste">Timor-Leste</option>
                                <option value="Togo">Togo</option>
                                <option value="Tonga">Tonga</option>
                                <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                <option value="Tunisia">Tunisia</option>
                                <option value="Türkiye">Türkiye</option>
                                <option value="Turkmenistan">Turkmenistan</option>
                                <option value="Tuvalu">Tuvalu</option>
                                <option value="Uganda">Uganda</option>
                                <option value="Ukraine">Ukraine</option>
                                <option value="United Arab Emirates">United Arab Emirates</option>
                                <option value="United Kingdom">United Kingdom</option>
                                <option value="United States">United States</option>
                                <option value="Uruguay">Uruguay</option>
                                <option value="Uzbekistan">Uzbekistan</option>
                                <option value="Vanuatu">Vanuatu</option>
                                <option value="Venezuela">Venezuela</option>
                                <option value="Vietnam">Vietnam</option>
                                <option value="Yemen">Yemen</option>
                                <option value="Zambia">Zambia</option>
                                <option value="Zimbabwe">Zimbabwe</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Timezone</label>
                            <select name="timezone" id="sfTimezone" class="form-control">
                                @foreach (timezone_identifiers_list() as $tz)
                                    <option value="{{ $tz }}" {{ $tz === 'Asia/Kolkata' ? 'selected' : '' }}>
                                        {{ $tz }}</option>
                                @endforeach
                            </select>
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
                            <label class="form-label">Music Category (Course)</label>
                            <div class="student-check-list" id="sfCourseList" style="max-height: 120px;">
                                @foreach ($courses as $course)
                                    <label>
                                        <input type="checkbox" name="courses[]" value="{{ $course->id }}"
                                            class="sf-course-checkbox"
                                            style="width:15px;height:15px;accent-color:var(--primary);">
                                        <span>{{ $course->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-2 gap-3">
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
                        <div class="form-group">
                            <label class="form-label">Credit Package</label>
                            <select id="sfCreditPackage" class="form-control"
                                onchange="document.getElementById('sfCredits').value = this.options[this.selectedIndex].dataset.credits || 0; calculateStudentEndDate();">
                                <option value="" data-credits="0">— Custom / No Package —</option>
                                @if (isset($creditPackages))
                                    @foreach ($creditPackages as $package)
                                        <option value="{{ $package->id }}" data-credits="{{ $package->credits }}">
                                            {{ $package->name }} ({{ $package->credits }} Credits)</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-2 gap-3">
                        <div class="form-group">
                            <label class="form-label">Initial Credits</label>
                            <input type="number" name="credits" id="sfCredits" class="form-control" value="0"
                                min="0" onchange="calculateStudentEndDate()">
                        </div>
                        <div class="form-group">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" id="sfEndDate" class="form-control">
                        </div>
                    </div>
                    <div class="grid grid-2 gap-3">
                        <div class="form-group">
                            <label class="form-label">Account Status *</label>
                            <select name="status" id="sfStatus" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="form-group"></div>
                    </div>
                    <div class="grid grid-1 gap-3">
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
                            {{ \App\Models\Setting::get('max_group_users', 4) }} max)</label>
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
        let dtStudents = null;
        let dtGroups = null;

        @php
            $studentsJson = $students
                ->map(function ($s) {
                    return [
                        'id' => $s->id,
                        'name' => $s->name,
                        'email' => $s->email,
                        'country' => $s->country ?? '',
                        'timezone' => $s->user->timezone ?? 'Asia/Kolkata',
                        'phone' => $s->phone ?? '',
                        'enrolled_level' => $s->enrolled_level ?? 'Foundation Level',
                        'course_ids' => $s->courses->pluck('id')->toArray(),
                        'teacher_id' => $s->teacher_id ?? '',
                        'referral_source' => $s->referral_source ?? '',
                        'joining_date' => $s->joining_date ? \Carbon\Carbon::parse($s->joining_date)->format('Y-m-d') : '',
                        'emergency_contact_name' => $s->emergency_contact_name ?? '',
                        'emergency_contact_phone' => $s->emergency_contact_phone ?? '',
                        'enrolled_format' => $s->enrolled_format ?? 'Individual',
                        'credits' => $s->credits ?? 0,
                        'status' => $s->status ?? 'active',
                        'group_id' => optional($s->groups->first())->id ?? '',
                        'age' => $s->age ?? '',
                    ];
                })
                ->values();
        @endphp
        // All students as JSON for Edit modal pre-fill
        const allStudents = @json($studentsJson);

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
        function toggleGroupSelect(val) {
            document.getElementById('groupSelectContainer').style.display = val === 'Group' ? 'block' : 'none';
        }

        function openAddStudent() {
            document.getElementById('studentForm').reset();
            document.getElementById('sfMethod').value = 'POST';
            document.getElementById('studentForm').action = '{{ route('admin.students.store') }}';
            document.getElementById('studentModalTitle').textContent = 'Add New Student';
            document.getElementById('groupSelectContainer').style.display = 'none';
            document.querySelectorAll('.sf-course-checkbox').forEach(cb => cb.checked = false);
            showModal('studentModal');
        }

        function openEditStudent(id) {
            const s = allStudents.find(x => x.id === id);
            if (!s) return;

            document.getElementById('sfName').value = s.name;
            document.getElementById('sfEmail').value = s.email;
            document.getElementById('sfPhone').value = s.phone;
            document.getElementById('sfAge').value = s.age;
            document.getElementById('sfCountry').value = s.country;
            document.getElementById('sfTimezone').value = s.timezone;
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
            document.getElementById('sfStatus').value = s.status;
            toggleGroupSelect(s.enrolled_format);
            if (s.group_id) document.getElementById('sfGroup').value = s.group_id;

            document.getElementById('sfMethod').value = 'PUT';
            document.getElementById('studentForm').action = '{{ url('admin/students') }}/' + id;
            document.getElementById('studentModalTitle').textContent = 'Edit Student Profile';
            showModal('studentModal');
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
                    let html =
                        `<strong>✅ Imported: ${data.imported}</strong> &nbsp; <strong>⚠️ Skipped: ${data.skipped}</strong>`;
                    if (hasErrors) {
                        html += '<ul style="margin-top:.5rem;padding-left:1.2rem;font-size:12px;">';
                        data.errors.forEach(e => {
                            html += `<li>${e}</li>`;
                        });
                        html += '</ul>';
                    }
                    resultEl.innerHTML = html;
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

        function calculateStudentEndDate() {
            const joiningDateStr = document.getElementById('sfJoiningDate').value;
            const creditsStr = document.getElementById('sfCredits').value;

            if (joiningDateStr && creditsStr) {
                const joiningDate = new Date(joiningDateStr);
                const credits = parseInt(creditsStr, 10);

                if (!isNaN(credits)) {
                    // 1 credit = 1 day
                    const endDate = new Date(joiningDate);
                    endDate.setDate(endDate.getDate() + credits);

                    // Format to YYYY-MM-DD
                    const year = endDate.getFullYear();
                    const month = String(endDate.getMonth() + 1).padStart(2, '0');
                    const day = String(endDate.getDate()).padStart(2, '0');

                    document.getElementById('sfEndDate').value = `${year}-${month}-${day}`;
                }
            }
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

<div id="introVideoViewerModal" onclick="if(event.target===this)closeIntroVideoViewer()">
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
