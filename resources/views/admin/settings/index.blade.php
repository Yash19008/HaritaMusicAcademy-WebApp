@extends('layouts.main')
@section('title', 'Settings')
@section('page', 'settings')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@endpush

@section('content')
    <div class="grid grid-2 gap-4 slide-up">
        <!-- ACADEMY PROFILE (Admin Only) -->
        <div class="card" data-role-limit="admin">
            <div class="card-header">
                <h4 class="font-semibold">Academy Public Info</h4>
            </div>
            <form id="academyConfigForm" method="POST" action="{{ route('admin.settings.save') }}" class="card-body">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="acName">Academy Name</label>
                    <input type="text" id="acName" name="academy_name" class="form-control"
                        value="{{ $settings['academy_name'] ?? 'Harita Music Academy' }}" required>
                </div>
                <div class="grid grid-2 gap-2">
                    <div class="form-group">
                        <label class="form-label" for="acEmail">Contact Email</label>
                        <input type="email" id="acEmail" name="contact_email" class="form-control"
                            value="{{ $settings['contact_email'] ?? 'info@haritamusic.com' }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="acPhone">Support Phone</label>
                        <input type="text" id="acPhone" name="support_phone" class="form-control"
                            value="{{ $settings['support_phone'] ?? '+91 80 4432 1099' }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="acAddress">Address</label>
                    <input type="text" id="acAddress" name="address" class="form-control"
                        value="{{ $settings['address'] ?? '12, Veena Avenue, Carnatic Nagar, Chennai - 600028' }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Academy Details</button>
            </form>
        </div>

        <!-- SCHEDULING RULES (Admin Only) -->
        <div class="card" data-role-limit="admin">
            <div class="card-header">
                <h4 class="font-semibold">Scheduling Policies</h4>
            </div>
            <form id="policiesForm" method="POST" action="{{ route('admin.settings.save') }}" class="card-body">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="polDuration">Default Class Duration</label>
                    <select id="polDuration" name="class_duration" class="form-control">
                        <option value="40" {{ ($settings['class_duration'] ?? '40') == '40' ? 'selected' : '' }}>40
                            Minutes</option>
                    </select>
                </div>
                <div class="grid grid-2 gap-3">
                    <div class="form-group mb-3">
                        <label class="form-label" for="polRescheduleLimitIndian">Indian Students Reschedule Lock
                            (Hours)</label>
                        <input type="number" id="polRescheduleLimitIndian" name="indian_reschedule_cutoff_hours"
                            class="form-control" value="{{ $settings['indian_reschedule_cutoff_hours'] ?? '10' }}"
                            min="1">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="polRescheduleLimitIntl">International Students Reschedule Lock
                            (Hours)</label>
                        <input type="number" id="polRescheduleLimitIntl" name="intl_reschedule_cutoff_hours"
                            class="form-control" value="{{ $settings['intl_reschedule_cutoff_hours'] ?? '12' }}"
                            min="1">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save System Policies</button>
            </form>
        </div>




        <!-- BUSINESS RULES (Admin Only) -->
        <div class="card" data-role-limit="admin">
            <div class="card-header">
                <h4 class="font-semibold">Business Rules</h4>
            </div>
            <form id="businessRulesForm" method="POST" action="{{ route('admin.settings.save') }}" class="card-body">
                @csrf
                <div class="grid grid-2 gap-3 mb-3">
                    <div class="form-group">
                        <label class="form-label" for="oppTeacher">Demo Class Opportunity Teacher</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <input type="number" id="oppTeacher" name="opportunity_teacher_pct" class="form-control"
                                value="{{ $settings['opportunity_teacher_pct'] ?? '20' }}" min="0" max="100"
                                style="padding-right: 2.5rem;" required>
                            <span
                                style="position: absolute; right: 1rem; font-weight: bold; color: var(--text-muted);">%</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="oppBonus">Emergency Class Opportunity Bonus</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <span
                                style="position: absolute; left: 1rem; font-weight: bold; color: var(--text-muted);">₹</span>
                            <input type="number" id="oppBonus" name="opportunity_bonus_rs" class="form-control"
                                value="{{ $settings['opportunity_bonus_rs'] ?? '100' }}" min="0"
                                style="padding-left: 2.5rem;" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="refBonusTeacher">Referral Bonus Teacher</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <span
                                style="position: absolute; left: 1rem; font-weight: bold; color: var(--text-muted);">₹</span>
                            <input type="number" id="refBonusTeacher" name="referral_bonus_teacher_rs"
                                class="form-control" value="{{ $settings['referral_bonus_teacher_rs'] ?? '500' }}"
                                min="0" style="padding-left: 2.5rem;" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="oppStudent">Referral Bonus Student</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <input type="number" id="oppStudent" name="referral_bonus_student_credits"
                                class="form-control" value="{{ $settings['referral_bonus_student_credits'] ?? '2' }}"
                                min="0" style="padding-right: 5.5rem;" required>
                            <span
                                style="position: absolute; right: 1rem; font-weight: bold; color: var(--text-muted);">Credits</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="maxGroupUsers">Max Group Users</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <input type="number" id="maxGroupUsers" name="max_group_users" class="form-control"
                                value="{{ $settings['max_group_users'] ?? '4' }}" min="1" required>
                        </div>
                    </div>
                </div>
                <div class="grid grid-2 gap-3 mb-3">
                    <div class="form-group">
                        <label class="form-label" for="demoPriceInr">Demo Price (India)</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <span style="position: absolute; left: 1rem; font-weight: bold; color: var(--text-muted);">₹</span>
                            <input type="number" id="demoPriceInr" name="demo_price_inr" class="form-control"
                                value="{{ $settings['demo_price_inr'] ?? '0' }}" min="0" style="padding-left: 2.5rem;" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="demoPriceIntl">Demo Price (International)</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <span style="position: absolute; left: 1rem; font-weight: bold; color: var(--text-muted);">₹</span>
                            <input type="number" id="demoPriceIntl" name="demo_price_intl" class="form-control"
                                value="{{ $settings['demo_price_intl'] ?? '0' }}" min="0" style="padding-left: 2.5rem;" required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save Business Rules</button>
            </form>
        </div>

        <!-- REMINDER CONFIGS (Admin Only) -->
        <div class="card" data-role-limit="admin">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h4 class="font-semibold">Class Reminders</h4>
                <button type="button" class="btn btn-sm btn-primary" onclick="openAddReminderModal()">
                    + Add Reminder
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table display responsive nowrap" id="reminderConfigsTable" style="width:100%">
                        <thead>
                            <tr>
                                <th data-priority="1">Label</th>
                                <th data-priority="2">Minutes Before</th>
                                <th data-priority="3">Targets</th>
                                <th data-priority="2">Status</th>
                                <th data-priority="1" class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="reminderConfigTableBody">
                            <!-- Populated by JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- CREDIT PACKAGES (Admin Only) -->
        <div class="card" data-role-limit="admin">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h4 class="font-semibold">Credit Packages</h4>
                <button type="button" class="btn btn-sm btn-primary" onclick="openAddCreditPackageModal()">
                    + Add Package
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table display responsive nowrap" id="creditPackagesTable" style="width:100%">
                        <thead>
                            <tr>
                                <th data-priority="1">Package Name</th>
                                <th data-priority="2">Credits</th>
                                <th data-priority="1" class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($creditPackages as $package)
                                <tr>
                                    <td class="font-semibold">{{ $package->name }}</td>
                                    <td>{{ $package->credits }}</td>
                                    <td class="text-right">
                                        <div
                                            style="display: flex; gap: 0.5rem; justify-content: flex-end; align-items: center;">
                                            <button type="button" class="btn btn-sm btn-secondary"
                                                onclick="openEditCreditPackageModal({{ $package->id }}, '{{ addslashes($package->name) }}', {{ $package->credits }})">Edit</button>
                                            <form action="{{ route('admin.credit-packages.destroy', $package->id) }}"
                                                method="POST" onsubmit="return confirm('Delete this package?');"
                                                style="margin: 0;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ADD CREDIT PACKAGE MODAL -->
    <div class="modal-backdrop" id="addCreditPackageModal">
        <div class="modal" style="max-width: 400px;">
            <div class="modal-header">
                <h4 class="font-semibold">Add Credit Package</h4>
                <button type="button" class="modal-close" onclick="closeAddCreditPackageModal()">&times;</button>
            </div>
            <form action="{{ route('admin.credit-packages.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label" for="packageName">Package Name</label>
                        <input type="text" id="packageName" name="name" class="form-control"
                            placeholder="e.g., Starter Pack" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="packageCredits">Credits</label>
                        <input type="number" id="packageCredits" name="credits" class="form-control"
                            placeholder="e.g., 10" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        onclick="closeAddCreditPackageModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Package</button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT CREDIT PACKAGE MODAL -->
    <div class="modal-backdrop" id="editCreditPackageModal">
        <div class="modal" style="max-width: 400px;">
            <div class="modal-header">
                <h4 class="font-semibold">Edit Credit Package</h4>
                <button type="button" class="modal-close" onclick="closeEditCreditPackageModal()">&times;</button>
            </div>
            <form id="editCreditPackageForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label" for="editPackageName">Package Name</label>
                        <input type="text" id="editPackageName" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="editPackageCredits">Credits</label>
                        <input type="number" id="editPackageCredits" name="credits" class="form-control"
                            min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        onclick="closeEditCreditPackageModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Package</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ADD / EDIT REMINDER MODAL -->
    <div class="modal-backdrop" id="reminderModal">
        <div class="modal" style="max-width: 500px;">
            <div class="modal-header">
                <h4 class="font-semibold" id="reminderModalTitle">Add Reminder</h4>
                <button type="button" class="modal-close" onclick="closeReminderModal()">&times;</button>
            </div>
            <form id="reminderForm" onsubmit="saveReminderConfig(event)">
                <input type="hidden" id="reminderId" value="">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label" for="remLabel">Label</label>
                        <input type="text" id="remLabel" class="form-control" placeholder="e.g. 10 Hours Before" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="remMinutes">Minutes Before Class</label>
                        <input type="number" id="remMinutes" class="form-control" placeholder="e.g. 600" min="1" required>
                    </div>
                    <div class="grid grid-2 gap-3 mb-3">
                        <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
                            <input type="checkbox" id="remNotifyStudent" checked> Notify Students
                        </label>
                        <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
                            <input type="checkbox" id="remNotifyTeacher" checked> Notify Teachers
                        </label>
                    </div>
                    <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
                        <input type="checkbox" id="remEnabled" checked> Enabled
                    </label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeReminderModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Reminder</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Developed by Sitesoch footer -->
    <footer class="footer mt-4">
        <p>© 2026 Harita Music Academy. All rights reserved. | Developed by <a href="https://sitesoch.com"
                target="_blank">Sitesoch</a></p>
    </footer>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof setupDataTable === 'function') {
                setupDataTable('creditPackagesTable');
            } else {
                $('#creditPackagesTable').DataTable({
                    responsive: true,
                    pageLength: 10,
                    language: {
                        search: "",
                        searchPlaceholder: "Search packages..."
                    }
                });
            }
            loadReminderConfigs();
        });

        let reminderTable;

        function loadReminderConfigs() {
            fetch('/admin/reminder-configs')
                .then(r => r.json())
                .then(data => {
                    if (reminderTable) {
                        reminderTable.destroy();
                    }
                    const tbody = document.getElementById('reminderConfigTableBody');
                    tbody.innerHTML = '';
                    const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, character => ({
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#039;'
                    }[character]));
                    if(data && data.length > 0) {
                        data.forEach(config => {
                            const targets = [];
                            if (config.notify_student) targets.push('Students');
                            if (config.notify_teacher) targets.push('Teachers');
                            
                            const statusBadge = config.enabled 
                                ? `<span class="badge badge-primary">Active</span>`
                                : `<span class="badge badge-secondary text-muted">Disabled</span>`;

                            tbody.innerHTML += `
                                <tr>
                                    <td class="font-semibold">${escapeHtml(config.label)}</td>
                                    <td>${config.minutes_before}</td>
                                    <td>${escapeHtml(targets.join(', '))}</td>
                                    <td>${statusBadge}</td>
                                    <td class="text-right">
                                        <button class="btn btn-sm btn-secondary" style="padding:0.25rem 0.5rem;" onclick='openEditReminderModal(${JSON.stringify(config).replace(/'/g, "&#39;")})'>Edit</button>
                                        <button class="btn btn-sm btn-danger" style="padding:0.25rem 0.5rem;" onclick="deleteReminder(${config.id})">Delete</button>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                    
                    if (typeof setupDataTable === 'function') {
                        reminderTable = setupDataTable('reminderConfigsTable');
                    } else {
                        reminderTable = $('#reminderConfigsTable').DataTable({
                            responsive: true,
                            pageLength: 10,
                            language: {
                                search: "",
                                searchPlaceholder: "Search reminders..."
                            }
                        });
                    }
                })
                .catch(err => console.error(err));
        }

        function openAddReminderModal() {
            document.getElementById('reminderId').value = '';
            document.getElementById('reminderForm').reset();
            document.getElementById('remNotifyStudent').checked = true;
            document.getElementById('remNotifyTeacher').checked = true;
            document.getElementById('remEnabled').checked = true;
            document.getElementById('reminderModalTitle').innerText = 'Add Reminder';
            document.getElementById('reminderModal').classList.add('show');
        }

        function openEditReminderModal(config) {
            document.getElementById('reminderId').value = config.id;
            document.getElementById('remLabel').value = config.label;
            document.getElementById('remMinutes').value = config.minutes_before;
            document.getElementById('remNotifyStudent').checked = config.notify_student;
            document.getElementById('remNotifyTeacher').checked = config.notify_teacher;
            document.getElementById('remEnabled').checked = config.enabled;
            document.getElementById('reminderModalTitle').innerText = 'Edit Reminder';
            document.getElementById('reminderModal').classList.add('show');
        }

        function closeReminderModal() {
            document.getElementById('reminderModal').classList.remove('show');
        }

        function saveReminderConfig(e) {
            e.preventDefault();
            const id = document.getElementById('reminderId').value;
            const payload = {
                label: document.getElementById('remLabel').value,
                minutes_before: document.getElementById('remMinutes').value,
                notify_student: document.getElementById('remNotifyStudent').checked ? 1 : 0,
                notify_teacher: document.getElementById('remNotifyTeacher').checked ? 1 : 0,
                enabled: document.getElementById('remEnabled').checked ? 1 : 0,
            };

            const url = id ? `/admin/reminder-configs/${id}` : '/admin/reminder-configs';
            const method = id ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            })
            .then(r => r.json())
            .then(res => {
                if(res.success) {
                    closeReminderModal();
                    loadReminderConfigs();
                } else {
                    alert('Error saving configuration');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Server error saving config');
            });
        }

        function deleteReminder(id) {
            if(!confirm('Are you sure you want to delete this reminder?')) return;
            fetch(`/admin/reminder-configs/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(r => r.json())
            .then(res => {
                if(res.success) {
                    loadReminderConfigs();
                }
            });
        }

        function openAddCreditPackageModal() {
            document.getElementById('addCreditPackageModal').classList.add('show');
        }

        function closeAddCreditPackageModal() {
            document.getElementById('addCreditPackageModal').classList.remove('show');
        }

        function openEditCreditPackageModal(id, name, credits) {
            document.getElementById('editPackageName').value = name;
            document.getElementById('editPackageCredits').value = credits;
            document.getElementById('editCreditPackageForm').action = `/admin/credit-packages/${id}`;
            document.getElementById('editCreditPackageModal').classList.add('show');
        }

        function closeEditCreditPackageModal() {
            document.getElementById('editCreditPackageModal').classList.remove('show');
        }
    </script>
@endpush
