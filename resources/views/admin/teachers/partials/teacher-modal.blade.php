@push('modals')
  <!-- Teacher Modal -->
  <div id="teacherModal" class="modal-backdrop">
    <div class="modal">
      <div class="modal-header">
        <h3 id="modalTitle" class="font-semibold text-serif">Add New Teacher</h3>
        <button class="modal-close" onclick="hideModal('teacherModal')">x</button>
      </div>
      <form id="teacherForm" method="POST" action="">
        @csrf
        <div id="formMethod"></div>
        <div class="modal-body">

          <div class="grid grid-2 gap-3">
            <div class="form-group">
              <label class="form-label" for="Code">Teacher Code</label>
              <input type="text" id="Code" name="Code" class="form-control" required placeholder="e.g. HMATC000001" readonly>
            </div>
            <div class="form-group">
              <label class="form-label" for="tchName">Full Name</label>
              <input type="text" id="tchName" name="name" class="form-control" required placeholder="e.g. Meera Sharma">
            </div>
          </div>

          <div class="grid grid-2 gap-3">
            <div class="form-group">
              <label class="form-label" for="tchEmail">Email Address</label>
              <input type="email" id="tchEmail" name="email" class="form-control" required placeholder="e.g. meera@haritamusic.com">
            </div>
            <div class="form-group">
              <label class="form-label" for="tchPhone">Phone Number</label>
              <input type="text" id="tchPhone" name="phone" class="form-control" required placeholder="e.g. +91 87654 32109">
            </div>
          </div>

          <div class="grid grid-2 gap-3">
            <div class="form-group">
              <label class="form-label" for="tchExperience">Experience</label>
              <input type="text" id="tchExperience" name="experience" class="form-control" placeholder="e.g. 8 Years">
            </div>
            <div class="form-group">
              <label class="form-label" for="tchSpecialization">Specialization</label>
              <input type="text" id="tchSpecialization" name="specialization" class="form-control" placeholder="e.g. Hindustani Classical, Piano">
            </div>
          </div>

          <div class="grid grid-2 gap-3">
            <div class="form-group">
              <label class="form-label" for="tchJoiningDate">Joining Date</label>
              <input type="date" id="tchJoiningDate" name="joining_date" class="form-control">
            </div>
            <div class="form-group">
              <label class="form-label" for="tchRating">Rating</label>
              <input type="number" id="tchRating" name="rating" class="form-control" min="0" max="5" step="0.1" placeholder="e.g. 4.8">
            </div>
          </div>

          <div class="grid grid-2 gap-3">
            <div class="form-group">
              <label class="form-label" for="tchEmergencyName">Emergency Contact Name</label>
              <input type="text" id="tchEmergencyName" name="emergency_contact_name" class="form-control" placeholder="e.g. Rajesh Sharma">
            </div>
            <div class="form-group">
              <label class="form-label" for="tchEmergencyPhone">Emergency Contact Number</label>
              <input type="text" id="tchEmergencyPhone" name="emergency_contact_phone" class="form-control" placeholder="e.g. +91 98765 43210">
            </div>
          </div>

          <div class="grid grid-2 gap-3">
            <div class="form-group">
              <label class="form-label" for="Categories">Specialization Category</label>
              <select id="Categories" name="course_id" class="form-control">
                <option value="">Select Category</option>
                @foreach(\App\Models\Course::all() as $course)
                  <option value="{{ $course->id }}">{{ $course->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Week Off Days</label>
              <div style="height: 90px; overflow-y: auto; padding: 0.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 0.25rem; background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: 4px;">
                @foreach(['MON','TUE','WED','THU','FRI','SAT','SUN'] as $day)
                  <label style="display:flex; align-items:center; gap:0.25rem; font-size:12.5px; font-weight:normal; margin:0;">
                    <input type="checkbox" name="week_off[]" value="{{ $day }}" style="width:13px; height:13px;"> {{ $day }}
                  </label>
                @endforeach
              </div>
            </div>
          </div>

          <div class="grid grid-2 gap-3">
            <div class="form-group">
              <label class="form-label" for="Level">Level</label>
              <select id="Level" name="level" class="form-control">
                <option value="Foundation Level">Foundation Level</option>
                <option value="Intermediate Level">Intermediate Level</option>
                <option value="Advanced Level">Advanced Level</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label" for="tchStatus">Status</label>
              <select id="tchStatus" name="status" class="form-control" required>
                <option value="active">Active</option>
                <option value="on_leave">On Leave</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
          </div>

          <div class="grid grid-2 gap-3">
            <div class="form-group">
              <label class="form-label" for="ClassFee">Per Class Fee</label>
              <input type="text" id="ClassFee" name="per_class_rate" class="form-control" placeholder="e.g. Rs 200">
            </div>
            <div class="form-group">
              <label class="form-label" for="Certifications">Certifications (comma separated)</label>
              <input type="text" id="Certifications" name="certifications" class="form-control" placeholder="e.g. ABRSM Grade 8, Trinity">
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="tchYoutube">YouTube Video Link</label>
            <input type="text" id="tchYoutube" name="youtube_url" class="form-control" placeholder="e.g. https://www.youtube.com/watch?v=...">
          </div>

          <div class="form-group">
            <label class="form-label" for="tchBio">Short Biography</label>
            <textarea id="tchBio" name="bio" class="form-control" style="height: 60px;" placeholder="Teaching experience..."></textarea>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="hideModal('teacherModal')">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Teacher</button>
        </div>
      </form>
    </div>
  </div>
@endpush
