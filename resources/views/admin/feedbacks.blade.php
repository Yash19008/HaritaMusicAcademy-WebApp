@extends('layouts.main')
@section('page', 'feedbacks')

@section('content')
<div class="card">
        <div class="card-header d-flex align-center justify-between">
          <h4 class="font-semibold" style="font-family: var(--font-serif); font-size: 1.25rem;">All Submitted Feedbacks
            &amp; Issues</h4>
        </div>
        <div class="card-body p-3">
          <table class="table display responsive nowrap" id="adminFbTable" style="width:100%">
            <thead>
              <tr>
                <th>Date</th>
                <th>Student</th>
                <th>Category</th>
                <th>Target Element</th>
                <th>Rating</th>
                <th>Review Message</th>
                <th>Status</th>
                <th style="width: 100px; text-align: center;">Actions</th>
              </tr>
            </thead>
            <tbody id="adminFbBody">
              <!-- Loaded Dynamically -->
            </tbody>
          </table>
        </div>
      </div>
@endsection
