@extends('layouts.main')
@section('title', 'Feedback - Harita Music Academy')
@section('page', 'feedbacks')

@push('styles')
<style>
.star-rating-container {
      display: flex;
      gap: 0.5rem;
      font-size: 1.75rem;
      color: var(--text-light);
      margin: 0.5rem 0;
    }

    .star-item {
      cursor: pointer;
      transition: color 0.15s;
    }

    .star-item.selected,
    .star-item:hover {
      color: var(--primary);
    }

    .feedback-grid {
      display: grid;
      grid-template-columns: 1.2fr 1.8fr;
      gap: 1.5rem;
    }

    @media (max-width: 992px) {
      .feedback-grid {
        grid-template-columns: 1fr;
      }
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@endpush

@section('content')
<div class="feedback-grid">
        <!-- Feedback Form -->
        <div class="card">
          <div class="card-header">
            <h4 class="font-semibold" style="font-family: var(--font-serif); font-size: 1.25rem;">Submit New Feedback
            </h4>
          </div>
          <div class="card-body p-4">
            <form action="{{ route('student.feedback.store') }}" method="POST">
              @csrf
              <div class="form-group mb-3">
                <label class="form-label font-semibold">Feedback Category</label>
                <select name="category" id="fbCategory" class="form-control" required onchange="toggleCategoryTarget()">
                  <option value="Mentor">Mentor (Teacher)</option>
                  <option value="System">System (App & Performance)</option>
                  <option value="Academy">Academy (Overall Facility)</option>
                </select>
              </div>

              <div class="form-group mb-3" id="mentorSelectGroup">
                <label class="form-label font-semibold">Select Teacher</label>
                <select name="teacher_id" id="fbTarget" class="form-control">
                  @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group mb-3">
                <label class="form-label font-semibold">Star Rating</label>
                <div class="star-rating-container" id="starRating">
                  <span class="star-item" data-value="1" onclick="setRating(1)">★</span>
                  <span class="star-item" data-value="2" onclick="setRating(2)">★</span>
                  <span class="star-item" data-value="3" onclick="setRating(3)">★</span>
                  <span class="star-item" data-value="4" onclick="setRating(4)">★</span>
                  <span class="star-item" data-value="5" onclick="setRating(5)">★</span>
                </div>
                <input type="hidden" name="rating" id="fbRating" value="5" required>
              </div>

              <div class="form-group mb-3">
                <label class="form-label font-semibold">Your Review Message</label>
                <textarea name="message" id="fbMessage" class="form-control" rows="4" placeholder="Tell us about your experience..." required></textarea>
              </div>

              <button type="submit" class="btn btn-primary w-100">Submit Review</button>
            </form>
          </div>
        </div>

        <!-- Feedback Log History -->
        <div class="card">
          <div class="card-header">
            <h4 class="font-semibold" style="font-family: var(--font-serif); font-size: 1.25rem;">My Feedback History
            </h4>
          </div>
          <div class="card-body p-3">
            <table class="table display responsive nowrap" id="fbHistoryTable" style="width:100%">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Category</th>
                  <th>Target</th>
                  <th>Rating</th>
                  <th>Message</th>
                </tr>
              </thead>
              <tbody>
                @foreach($feedbacks as $fb)
                <tr>
                  <td>{{ $fb->created_at->format('Y-m-d') }}</td>
                  <td><span class="badge badge-info">{{ $fb->category }}</span></td>
                  <td class="font-semibold">
                      @if($fb->category === 'Mentor' && $fb->teacher)
                          {{ $fb->teacher->name }}
                      @else
                          {{ $fb->target_element ?? 'System/Academy' }}
                      @endif
                  </td>
                  <td class="font-bold text-primary">{{ str_repeat('★', $fb->rating) }}</td>
                  <td style="white-space: normal; min-width: 150px;">{{ $fb->message }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

      </div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
      setRating(5);
      toggleCategoryTarget();
      $('#fbHistoryTable').DataTable({ responsive: true });
    });

    function setRating(rating) {
      document.getElementById("fbRating").value = rating;
      const stars = document.querySelectorAll(".star-item");
      stars.forEach((s, idx) => {
        if (idx < rating) s.classList.add("selected");
        else s.classList.remove("selected");
      });
    }

    function toggleCategoryTarget() {
      const cat = document.getElementById("fbCategory").value;
      const selectGroup = document.getElementById("mentorSelectGroup");
      const teacherSelect = document.getElementById("fbTarget");
      if (cat === "Mentor") {
        selectGroup.style.display = "block";
        teacherSelect.setAttribute("required", "required");
      } else {
        selectGroup.style.display = "none";
        teacherSelect.removeAttribute("required");
      }
    }
</script>
@endpush
