@extends('layouts.main')
@section('page', 'teachers')

@section('content')
<div class="card mb-3">
        <div class="card-body d-flex flex-wrap align-center justify-between gap-3">
          <div class="d-flex gap-2 flex-wrap" style="flex: 1; max-width: 400px;">
            <input type="text" id="searchBar" class="form-control" placeholder="Quick filter by name..." style="flex: 1;">
          </div>
          <button class="btn btn-primary" onclick="openAddModal()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Add Teacher
          </button>
        </div>
      </div>

      <!-- Teachers Table (Wrapped for DataTables) -->
      <div class="card p-3">
        <table class="table display responsive nowrap" id="teachersTable" style="width:100%">
          <thead>
            <tr>
              <th>ID</th>
              <th>Teacher Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Categories</th>
              <th>Week Off</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="teacherTableBody">
            <!-- Populated via JavaScript -->
          </tbody>
        </table>
      </div>

      <!-- Developed by Sitesoch footer -->
      <footer class="footer">
        <p>© 2026 Harita Music Academy. All rights reserved. | Developed by <a href="https://sitesoch.com" target="_blank">Sitesoch</a></p>
      </footer>
@endsection
