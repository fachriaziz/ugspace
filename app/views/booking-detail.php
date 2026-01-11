<a href="<?= BASEURL ?>/dashboard" class="back-link">
  <i class="ri-arrow-left-line"></i> Back to My Bookings
</a>

<div class="booking-detail-container">
  <div class="booking-card <?= $booking['status'] ?>">
    <div class="booking-header">
      <div class="booking-id">
        <span class="label">Booking ID</span>
        <span class="value"><?= htmlspecialchars($booking['booking_code']) ?></span>
      </div>
      <span class="badge badge-<?= $booking['status'] ?> badge-lg"><?= strtoupper($booking['status']) ?></span>
    </div>

    <div class="booking-body">
      <div class="info-section">
        <h3><i class="ri-door-line"></i> Room Information</h3>
        <div class="info-grid">
          <div class="info-item">
            <label>Room Code</label>
            <p><?= htmlspecialchars($booking['room_code']) ?></p>
          </div>
          <div class="info-item">
            <label>Room Name</label>
            <p><?= htmlspecialchars($booking['room_name']) ?></p>
          </div>
          <div class="info-item">
            <label>Location</label>
            <p><?= htmlspecialchars($booking['room_location']) ?></p>
          </div>
          <div class="info-item">
            <label>Capacity</label>
            <p><?= $booking['room_capacity'] ?> people</p>
          </div>
        </div>
      </div>

      <div class="info-section">
        <h3><i class="ri-time-line"></i> Schedule</h3>
        <div class="info-grid">
          <div class="info-item">
            <label>Date</label>
            <p><?= date('d F Y', strtotime($booking['date'])) ?></p>
          </div>
          <div class="info-item">
            <label>Time</label>
            <p><?= sprintf('%02d:00 - %02d:00', $booking['start_hour'], $booking['end_hour']) ?></p>
          </div>
          <div class="info-item">
            <label>Duration</label>
            <p><?= $booking['end_hour'] - $booking['start_hour'] ?> Hour(s)</p>
          </div>
        </div>
      </div>

      <div class="info-section">
        <h3><i class="ri-user-line"></i> Booked By</h3>
        <div class="info-grid">
          <div class="info-item">
            <label>Name</label>
            <p><?= htmlspecialchars($booking['user_name']) ?></p>
          </div>
          <div class="info-item">
            <label>NPM</label>
            <p><?= htmlspecialchars($booking['user_npm']) ?></p>
          </div>
          <div class="info-item">
            <label>Email</label>
            <p><?= htmlspecialchars($booking['user_email']) ?></p>
          </div>
        </div>
      </div>

      <div class="info-section">
        <h3><i class="ri-file-text-line"></i> Purpose</h3>
        <p class="purpose-text"><?= htmlspecialchars($booking['purpose']) ?></p>
      </div>

      <div class="info-section">
        <h3><i class="ri-information-line"></i> Booking Info</h3>
        <div class="info-grid">
          <div class="info-item">
            <label>Booked At</label>
            <p><?= date('d M Y, H:i', strtotime($booking['created_at'])) ?></p>
          </div>
          <div class="info-item">
            <label>Status</label>
            <p><?= ucfirst($booking['status']) ?></p>
          </div>
        </div>
      </div>
    </div>

    <div class="booking-footer">
      <p class="note"><i class="ri-information-line"></i> Show this booking detail to the room administrator as proof of reservation.</p>

      <div class="booking-actions">
        <button onclick="window.print()" class="btn btn-outline">
          <i class="ri-printer-line"></i> Print
        </button>
        <?php if ($booking['status'] === 'confirmed'): ?>
          <form action="<?= BASEURL ?>/cancel/<?= $booking['booking_code'] ?>" method="POST" class="inline">
            <?= CSRF::field() ?>
            <button type="submit" class="btn btn-danger" onclick="return confirm('Cancel this booking?')">
              <i class="ri-close-line"></i> Cancel Booking
            </button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>