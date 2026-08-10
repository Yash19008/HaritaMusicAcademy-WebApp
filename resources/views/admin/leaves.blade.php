@extends('layouts.main')
@section('page', 'leaves')

@section('content')
<div class="grid grid-3 gap-4">
        <!-- LEAVES REGISTER LIST (All Roles) -->
        <div class="card grid-2-col-span-2" style="grid-column: span 3;" id="leaveRegisterSection">
          <div class="card-header">
            <h4 class="font-semibold">Leave Log Registry</h4>
          </div>
          <div class="card-body p-3">
            <table class="table display responsive nowrap" id="leavesTable" style="width:100%">
              <thead>
                <tr>
                  <th>Teacher</th>
                  <th>Dates</th>
                  <th>Reason</th>
                  <th>Cover Teacher</th>
                  <th>Status</th>
                  <th data-role-limit="admin">Approval Action</th>
                </tr>
              </thead>
              <tbody id="leavesTableBody">
                <!-- Populated via JS -->
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Developed by Sitesoch footer -->
      <footer class="footer">
        <p>© 2026 Harita Music Academy. All rights reserved. | Developed by <a href="https://sitesoch.com" target="_blank">Sitesoch</a></p>
      </footer>
@endsection
