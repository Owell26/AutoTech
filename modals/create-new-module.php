<!-- Create Module Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark" id="exampleModalLabel">
                    <i class="bi bi-folder-plus me-2 text-primary"></i>Create New Module
                </h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="controller/ModuleController.php" method="post">
                <div class="modal-body px-4 pb-4">
                    <p class="text-muted small mb-4">Set up a new learning space for your students.</p>
                    
                    <div class="mb-3">
                        <label for="moduleDescription" class="form-label fw-semibold small text-uppercase text-muted">Module Name / Description</label>
                        <textarea class="form-control form-control-lg bg-light border-0 shadow-none fs-6" 
                                  id="moduleDescription" name="moduleDescription" 
                                  rows="3" placeholder="e.g., Advanced Automotive Systems" 
                                  style="border-radius: 10px;" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="section" class="form-label fw-semibold small text-uppercase text-muted">Section</label>
                            <select class="form-select bg-light border-0 shadow-none fs-6" 
                                    name="section" id="section" 
                                    style="border-radius: 10px;" required>
                                <option value="" selected disabled>Select Section</option>
                                <option value="Auto 1A">Auto 1A</option>
                                <option value="Auto 2A">Auto 2A</option>
                                <option value="Auto 3A">Auto 3A</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="year" class="form-label fw-semibold small text-uppercase text-muted">Academic Year</label>
                            <select class="form-select bg-light border-0 shadow-none fs-6" 
                                    name="year" id="year" 
                                    style="border-radius: 10px;" required>
                                <option value="" selected disabled>Select Year</option>
                                <option value="2026">2026</option>
                                <option value="2027">2027</option>
                                <option value="2028">2028</option>
                            </select>
                        </div>
                    </div>

                    <hr class="my-4 opacity-50">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-clock me-2 text-primary"></i>Initial Class Schedule</h6>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-uppercase text-muted">Meeting Day</label>
                        <select name="day_of_week" class="form-select bg-light border-0 shadow-none fs-6" style="border-radius: 10px;" required>
                            <option value="" selected disabled>Select Day</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Start Time</label>
                            <input type="time" name="start_time" class="form-control bg-light border-0 shadow-none fs-6" style="border-radius: 10px;" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-semibold small text-uppercase text-muted">End Time</label>
                            <input type="time" name="end_time" class="form-control bg-light border-0 shadow-none fs-6" style="border-radius: 10px;" required>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-semibold small text-uppercase text-muted">Meeting Type</label>
                        <select name="meeting_type" class="form-select bg-light border-0 shadow-none fs-6" style="border-radius: 10px;" required>
                            <option value="Face-to-Face">Face-to-Face</option>
                            <option value="Online">Online</option>
                            <option value="Hybrid">Hybrid</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-medium" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-medium shadow-sm" name="createModule">
                        Create Module
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    #exampleModal .form-control:focus, #exampleModal .form-select:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 0.25rem rgba(26, 115, 232, 0.1) !important;
        border: 1px solid #1a73e8 !important;
    }
</style>