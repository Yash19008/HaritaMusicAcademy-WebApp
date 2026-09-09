@extends('layouts.main')
@section('title', 'Demo Classes')
@section('page', 'demo-classes')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <style>
        /* ── DataTable theme overrides ── */
        table.dataTable.display tbody tr.odd>.sorting_1,
        table.dataTable.order-column.stripe tbody tr.odd>.sorting_1,
        table.dataTable.display tbody tr.even>.sorting_1,
        table.dataTable.order-column.stripe tbody tr.even>.sorting_1,
        table.dataTable.display tbody tr:hover>.sorting_1,
        table.dataTable.order-column.hover tbody tr:hover>.sorting_1 {
            background: transparent !important;
        }

        table.dataTable.display tbody tr>td {
            background: transparent !important;
        }

        table.dataTable thead th {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-muted);
            border-bottom: 2px solid var(--border-color) !important;
            padding: 0.85rem 1rem;
            white-space: nowrap;
        }

        table.dataTable tbody td {
            padding: 0.9rem 1rem;
            font-size: 0.85rem;
            color: var(--text-main);
            vertical-align: middle;
            border-bottom: 1px solid var(--border-light);
        }

        table.dataTable tbody tr:hover {
            background: var(--bg-main);
        }

        table.dataTable {
            border-collapse: collapse !important;
            width: 100% !important;
        }

        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            padding: 0.35rem 0.65rem;
            font-size: 0.82rem;
            font-family: var(--font-main);
            color: var(--text-main);
            background: var(--bg-card);
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            outline: none;
            border-color: var(--primary);
        }

        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label {
            font-size: 0.8rem;
            color: var(--text-muted);
            font-family: var(--font-main);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            font-size: 0.8rem;
            border-radius: var(--radius-sm) !important;
            padding: 0.3rem 0.7rem !important;
            font-family: var(--font-main);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--primary) !important;
            border-color: var(--primary) !important;
            color: #fff !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: var(--bg-main) !important;
            border-color: var(--border-color) !important;
            color: var(--primary) !important;
        }

        /* ── Status badges ── */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.28rem 0.7rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            white-space: nowrap;
        }

        .status-badge.scheduled {
            background: rgba(81, 4, 14, 0.08);
            color: #51040e;
            border: 1px solid rgba(81, 4, 14, 0.18);
        }

        .status-badge.completed {
            background: rgba(13, 148, 136, 0.09);
            color: #0d9488;
            border: 1px solid rgba(13, 148, 136, 0.2);
        }

        .status-badge.rescheduled {
            background: rgba(212, 175, 55, 0.12);
            color: #b38b22;
            border: 1px solid rgba(212, 175, 55, 0.25);
        }

        .status-badge.cancelled {
            background: rgba(239, 68, 68, 0.08);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.18);
        }

        /* ── Action buttons ── */
        .btn-join {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.38rem 0.9rem;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 0.78rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            font-family: var(--font-main);
        }

        .btn-join:hover {
            background: var(--primary-dark);
            color: #fff;
            transform: translateY(-1px);
        }

        /* ── Instrument cell ── */
        .instrument-cell {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .instrument-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(81, 4, 14, 0.08);
            border: 1px solid rgba(81, 4, 14, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.9rem;
        }

        /* ── Date cell ── */
        .date-cell {
            line-height: 1.3;
        }

        .date-cell .date-day {
            font-weight: 700;
            color: var(--text-main);
            font-size: 0.85rem;
        }

        .date-cell .date-time {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.1rem;
        }

        /* ── Empty state ── */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--text-muted);
        }

        .empty-state-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            background: rgba(81, 4, 14, 0.06);
            border: 1px solid rgba(81, 4, 14, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: var(--primary);
        }

        .empty-state-icon svg {
            width: 28px;
            height: 28px;
        }

        .empty-state h4 {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 0.35rem;
        }

        .empty-state p {
            font-size: 0.82rem;
        }

        /* ── Filter UI ── */
        .filter-container {
            background: rgba(81, 4, 14, 0.03);
            border: 1px solid rgba(81, 4, 14, 0.1);
            padding: 0.6rem 0.8rem;
            border-radius: var(--radius-md);
        }
        
        .filter-input-group {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        
        .filter-input-group .form-control {
            height: 34px;
            padding: 0.2rem 0.6rem;
            font-size: 0.82rem;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-color);
            background: #fff;
            width: 130px;
            color: var(--text-main);
        }
        
        .filter-input-group select.form-control {
            width: auto;
            min-width: 150px;
        }
        
        .filter-btn {
            height: 34px;
            padding: 0 1rem;
            font-size: 0.82rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            border-radius: var(--radius-sm);
            font-weight: 600;
        }
        
        .filter-divider {
            width: 1px;
            height: 24px;
            background: var(--border-color);
            margin: 0 0.4rem;
        }
    </style>
@endpush

@section('content')

    {{-- ── Page Header / Filters ── --}}
    <div class="card mb-4">
        <div class="card-body d-flex flex-wrap align-center justify-between gap-3">
            <div>
                <h2 style="font-size: 1.1rem; font-weight: 800; color: var(--text-main); margin: 0;">Demo Classes</h2>
                <p style="font-size: 0.78rem; color: var(--text-muted); margin: 0.2rem 0 0;">
                    Manage your upcoming and past demo classes.
                </p>
            </div>
            <form method="GET" class="filter-container d-flex gap-2 flex-wrap align-center">
                
                <div class="filter-input-group">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date', today()->format('Y-m-d')) }}" title="Start Date">
                </div>
                
                <span class="text-muted" style="font-size: 0.8rem; font-weight: 500;">&rarr;</span>
                
                <div class="filter-input-group">
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date', today()->format('Y-m-d')) }}" title="End Date">
                </div>
                
                <button type="submit" class="btn btn-primary filter-btn">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                    Filter
                </button>
                
                <div class="filter-divider"></div>
                
                <div class="filter-input-group">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2.5"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
                    <select id="statusFilter" class="form-control" onchange="filterTable()">
                        <option value="">All Statuses</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="completed">Completed</option>
                        <option value="converted">Converted</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="no-show">No-Show</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Demo Classes Table ── --}}
    <div class="card mb-4">
        <div class="card-body" style="padding: 1rem;">
            @if ($demos->count() > 0)
                <div style="overflow-x: auto;">
                    <table id="demosTable" class="display responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date & Time</th>
                                <th>Instrument</th>
                                <th>Student</th>
                                <th>Duration</th>
                                <th>Attendance</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $now = now(); @endphp
                            @foreach ($demos as $i => $demo)
                                @php
                                    $status = $demo->status;
                                    $badgeClass = match ($status) {
                                        'completed', 'converted' => 'completed',
                                        'cancelled', 'no-show' => 'cancelled',
                                        default => 'scheduled',
                                    };
                                    $statusLabel = ucfirst($status);
                                @endphp
                                <tr data-status="{{ $status }}">
                                    {{-- # --}}
                                    <td style="color: var(--text-muted); font-size:0.78rem; font-weight:600;">
                                        {{ $i + 1 }}
                                    </td>

                                    {{-- Date & Time --}}
                                    <td data-order="{{ $demo->scheduled_at->timestamp }}">
                                        <div class="date-cell">
                                            <div class="date-day">{{ $demo->scheduled_at->format('d M Y') }}</div>
                                            <div class="date-time">{{ $demo->scheduled_at->format('h:i A') }}</div>
                                        </div>
                                    </td>

                                    {{-- Instrument --}}
                                    <td>
                                        <div class="instrument-cell">
                                            <div class="instrument-icon">🎵</div>
                                            <span style="font-weight: 600; color: var(--text-main);">{{ $demo->instrument }}</span>
                                        </div>
                                    </td>

                                    {{-- Student --}}
                                    <td>
                                        <span style="font-weight: 600; color: var(--text-main);">
                                            {{ $demo->student_name ?? 'N/A' }} (Demo)
                                        </span>
                                    </td>

                                    {{-- Duration --}}
                                    <td>
                                        <span style="font-weight: 600;">{{ $demo->duration_minutes }}</span>
                                        <span style="color: var(--text-muted); font-size: 0.78rem;"> min</span>
                                    </td>

                                    {{-- Attendance --}}
                                    <td>
                                        @if ($demo->teacher_attended === true)
                                            <span class="status-badge completed">Present</span>
                                        @elseif ($demo->teacher_attended === false)
                                            <span class="status-badge cancelled">Absent</span>
                                        @else
                                            <span class="status-badge scheduled" style="background:#f1f5f9;color:#64748b;border-color:#cbd5e1;">—</span>
                                        @endif
                                    </td>

                                    {{-- Status --}}
                                    <td>
                                        <span class="status-badge {{ $badgeClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>

                                    {{-- Actions --}}
                                    <td>
                                        @if ($status === 'scheduled')
                                            <div class="d-flex gap-2">
                                                @php
                                                    $minutesUntilClass = $now->diffInMinutes($demo->scheduled_at, false);
                                                    $canJoin = $minutesUntilClass <= 15 && $now->isBefore($demo->scheduled_at->copy()->addMinutes($demo->duration_minutes ?? 40));
                                                @endphp
                                                @if($canJoin)
                                                    <a href="{{ $demo->teacher_join_url }}"
                                                        onclick="alert('The call is recorded for quality purposes.'); showDemoPopup(event, this.href)" class="btn-join">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <polygon points="23 7 16 12 23 17 23 7"></polygon>
                                                            <rect x="1" y="5" width="15" height="14" rx="2"
                                                                ry="2"></rect>
                                                        </svg>
                                                        Start Demo
                                                    </a>
                                                @else
                                                    <button class="btn-join" style="opacity: 0.5; cursor: not-allowed; background: #94a3b8;"
                                                        title="You can join 15 minutes before the class starts." disabled>
                                                        Start Demo
                                                    </button>
                                                @endif
                                            </div>
                                        @else
                                            <span style="color: var(--text-light); font-size: 0.78rem;">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                    </div>
                    <h4>No demo classes yet</h4>
                    <p>Your upcoming and past demo sessions will appear here.</p>
                </div>
            @endif
        </div>
    </div>

<div id="demoGuidelinePopup" class="demo-popup-overlay" style="display: none;">
    <div class="demo-popup-glass">
        <div class="demo-popup-top">
            <div class="demo-icon-ring">
                <span class="demo-popup-icon">🚀</span>
            </div>
            <h3 class="demo-popup-title">Demo Class Flow</h3>
            <p class="demo-popup-subtitle">Please follow this structured roadmap for a successful trial session.</p>
        </div>
        
        <div class="demo-flow-cards">
            <div class="flow-card">
                <div class="flow-time">5 mins</div>
                <div class="flow-desc"><strong>Introduction:</strong> Build rapport and understand the student's background.</div>
            </div>
            <div class="flow-card">
                <div class="flow-time highlight">10 mins</div>
                <div class="flow-desc"><strong>Voice Assessment:</strong> Evaluate their current vocal or instrumental skills.</div>
            </div>
            <div class="flow-card">
                <div class="flow-time highlight-main">20 mins</div>
                <div class="flow-desc"><strong>Teaching Session:</strong> Deliver a high-value, engaging mini-lesson.</div>
            </div>
            <div class="flow-card">
                <div class="flow-time">5 mins</div>
                <div class="flow-desc"><strong>Roadmap:</strong> Explain what they will achieve in the next few months to close.</div>
            </div>
        </div>

        <div class="demo-popup-action">
            <div class="redirect-spinner"></div>
            <p>Redirecting to Google Meet in <strong id="demoPopupTimer" class="timer-text">10</strong> seconds...</p>
        </div>
    </div>
</div>

<style>
.demo-popup-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(15, 23, 42, 0.85);
    backdrop-filter: blur(8px);
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeInPopup 0.3s ease-out;
}
.demo-popup-glass {
    background: white;
    border-radius: 20px;
    width: 90%;
    max-width: 500px;
    padding: 30px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    position: relative;
    overflow: hidden;
    animation: scaleUpPopup 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.demo-popup-glass::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; height: 6px;
    background: linear-gradient(90deg, #d4af37, #f5d76e, #d4af37);
    background-size: 200% 100%;
    animation: gradientMove 3s linear infinite;
}
.demo-popup-top {
    text-align: center;
    margin-bottom: 25px;
}
.demo-icon-ring {
    width: 70px;
    height: 70px;
    background: #fff8e1;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    box-shadow: 0 0 0 8px rgba(212, 175, 55, 0.1);
}
.demo-popup-icon {
    font-size: 2.2rem;
}
.demo-popup-title {
    font-size: 1.6rem;
    font-weight: 800;
    color: #1e293b;
    margin: 0 0 5px 0;
}
.demo-popup-subtitle {
    color: #64748b;
    font-size: 0.95rem;
    margin: 0;
}
.demo-flow-cards {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 30px;
}
.flow-card {
    display: flex;
    align-items: center;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 15px;
    transition: transform 0.2s;
}
.flow-card:hover {
    transform: translateX(5px);
    border-color: #cbd5e1;
}
.flow-time {
    background: #e2e8f0;
    color: #475569;
    font-weight: 700;
    font-size: 0.85rem;
    padding: 5px 10px;
    border-radius: 8px;
    min-width: 75px;
    text-align: center;
    margin-right: 15px;
}
.flow-time.highlight {
    background: #fef3c7;
    color: #d97706;
}
.flow-time.highlight-main {
    background: #dbeafe;
    color: #2563eb;
}
.flow-desc {
    color: #334155;
    font-size: 0.9rem;
    line-height: 1.4;
}
.flow-desc strong {
    color: #0f172a;
}
.demo-popup-action {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    background: #f1f5f9;
    padding: 15px;
    border-radius: 12px;
}
.demo-popup-action p {
    margin: 0;
    color: #475569;
    font-weight: 500;
}
.timer-text {
    color: #d4af37;
    font-size: 1.2rem;
    font-weight: 800;
}
.redirect-spinner {
    width: 20px;
    height: 20px;
    border: 3px solid #cbd5e1;
    border-top-color: #d4af37;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}
@keyframes fadeInPopup { from { opacity: 0; } to { opacity: 1; } }
@keyframes scaleUpPopup { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
@keyframes gradientMove { 0% { background-position: 100% 0; } 100% { background-position: -100% 0; } }
@keyframes spin { to { transform: rotate(360deg); } }
</style>

@endsection

@push('scripts')
    {{-- jQuery + DataTables CDN --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
        let dtDemos;

        document.addEventListener('DOMContentLoaded', function() {
            const tableConfig = {
                responsive: true,
                pageLength: 15,
                lengthMenu: [10, 15, 25, 50],
                order: [
                    [1, 'asc']
                ], // Sort by date ascending
                dom: 'lfrtip', // Show native search box along with custom filter
                columnDefs: [{
                        orderable: false,
                        targets: [2, 6]
                    }, // Instrument & Actions not sortable
                    {
                        width: '40px',
                        targets: 0
                    },
                ],
                language: {
                    emptyTable: 'No demo classes found.',
                    zeroRecords: 'No matching demo classes found.',
                    info: 'Showing _START_ to _END_ of _TOTAL_ classes',
                    infoEmpty: 'No demo classes available',
                    lengthMenu: 'Show _MENU_ per page',
                    paginate: {
                        previous: '‹',
                        next: '›'
                    },
                }
            };

            @if ($demos->count() > 0)
                dtDemos = $('#demosTable').DataTable(tableConfig);
            @endif
        });

        // Status filter
        function filterTable() {
            const val = document.getElementById('statusFilter').value.toLowerCase();
            const rows = document.querySelectorAll('#demosTable tbody tr');

            rows.forEach(row => {
                const status = row.getAttribute('data-status') || '';
                row.style.display = (!val || status === val) ? '' : 'none';
            });
        }
        
        let demoPopupInterval;
        function showDemoPopup(event, url) {
            event.preventDefault();
            
            const popup = document.getElementById('demoGuidelinePopup');
            const timerSpan = document.getElementById('demoPopupTimer');
            
            if(!popup) return;
            
            clearInterval(demoPopupInterval);
            popup.style.display = 'flex';
            
            let secondsLeft = 10;
            timerSpan.textContent = secondsLeft;
            
            demoPopupInterval = setInterval(() => {
                secondsLeft--;
                timerSpan.textContent = secondsLeft;
                
                if (secondsLeft <= 0) {
                    clearInterval(demoPopupInterval);
                    timerSpan.parentElement.innerHTML = "Redirecting now...";
                    
                    let newWin = window.open(url, '_blank');
                    if(!newWin || newWin.closed || typeof newWin.closed=='undefined') { 
                        window.location.href = url;
                    } else {
                        popup.style.display = 'none';
                    }
                }
            }, 1000);
        }
    </script>
@endpush
