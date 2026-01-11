<a href="<?= BASEURL ?>/rooms" class="back-link">
  <i class="ri-arrow-left-line"></i> Back to Rooms
</a>

<div class="card">
  <div class="schedule-header">
    <div>
      <h2><?= htmlspecialchars($room['name']) ?></h2>
      <p class="text-muted room-info">
        <i class="ri-map-pin-line"></i> <?= htmlspecialchars($room['location']) ?>
        <span class="separator">•</span>
        <i class="ri-user-line"></i> <?= $room['capacity'] ?> people
      </p>
    </div>
    <form method="GET" class="date-form">
      <input type="date" name="date" value="<?= $date ?>" min="<?= $today ?>" class="form-control" onchange="this.form.submit()">
    </form>
  </div>

  <p class="text-muted mb-4">
    <i class="ri-information-line"></i> Click on an available slot to book
  </p>

  <div class="schedule-grid">
    <?php foreach ($slots as $hour => $status): ?>
      <div class="slot slot-<?= $status ?>"
        <?php if ($status === 'available'): ?>data-hour="<?= $hour ?>" <?php endif; ?>>
        <div class="slot-time"><?= sprintf('%02d:00', $hour) ?></div>
        <div class="slot-status"><?= $status === 'passed' ? 'Passed' : ucfirst($status) ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<div class="card booking-form hidden" id="bookingForm">
  <h3>Book This Room</h3>
  <form action="<?= BASEURL ?>/book" method="POST">
    <?= CSRF::field() ?>
    <input type="hidden" name="room_id" value="<?= $room['id'] ?>">
    <input type="hidden" name="date" value="<?= $date ?>">
    <input type="hidden" name="start_hour" id="startHour">

    <div class="form-group">
      <label class="form-label">Start Time</label>
      <div id="displayTime" class="display-time">-</div>
    </div>

    <div class="form-group">
      <label class="form-label">Duration</label>
      <select name="duration" class="form-control">
        <option value="1">1 Hour</option>
        <option value="2">2 Hours</option>
        <option value="3">3 Hours (Max)</option>
      </select>
    </div>

    <div class="form-group">
      <label class="form-label">Purpose</label>
      <textarea name="purpose" class="form-control" rows="3" placeholder="e.g., Rapat Organisasi, Belajar Kelompok, Presentasi..." required></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Confirm Booking</button>
  </form>
</div>