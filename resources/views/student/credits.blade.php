@extends('layouts.main')
@section('page', 'credits')

@section('content')
    <!-- Student View specific warning about remaining credits -->
    <div id="studentCreditsWarning"
        class="card mb-4 p-3 @if ($balance < 5) badge-warning @else badge-success @endif"
        data-role-limit="student" style="border-color: var(--warning);">
        <div class="d-flex align-center gap-3">
            <span style="font-size: 1.5rem;">
                @if ($balance < 5)
                    ⚠️
                @else
                    🎓
                @endif
            </span>
            <div>
                <h4 class="font-bold text-primary">Class Credits Balance</h4>
                <p style="font-size: 0.9rem;">You have <strong id="studentRemainCredits">{{ $balance }}</strong> class
                    credits remaining.
                </p>
            </div>
        </div>
    </div>

    <!-- Transaction Log -->
    <div class="card">
        <div class="card-header">
            <h4 class="font-semibold">Credit Transaction Log</h4>
        </div>
        <div class="card-body p-3">
            <table class="table display responsive nowrap" id="transactionsTable" style="width:100%">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Action</th>
                        <th>Quantity</th>
                        <th>Reason / Remarks</th>
                    </tr>
                </thead>
                <tbody id="transactionTableBody">
                    @foreach ($transactions as $log)
                        <tr>
                            <td>{{ $log->created_at->format('M d, Y h:i A') }}</td>
                            <td class="font-bold {{ $log->quantity > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $log->action }}
                            </td>
                            <td class="font-bold">
                                @if (strtolower($log->action) === 'deducted' || strtolower($log->action) === 'deduct')
                                    -{{ abs($log->quantity) }}
                                @else
                                    +{{ abs($log->quantity) }}
                                @endif
                            </td>
                            <td style="color:var(--text-muted);">{{ $log->reason }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Developed by Sitesoch footer -->
    <footer class="footer">
        <p>© 2026 Harita Music Academy. All rights reserved. | Developed by <a href="https://sitesoch.com"
                target="_blank">Sitesoch</a></p>
    </footer>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            $('#transactionsTable').DataTable({
                responsive: true,
                order: [
                    [0, 'desc']
                ],
                language: {
                    search: "Search logs:",
                    lengthMenu: "Show _MENU_ logs",
                    info: "Showing _START_ to _END_ of _TOTAL_ logs"
                }
            });
        });
    </script>
@endpush
