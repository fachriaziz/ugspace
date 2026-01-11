<div class="page-header">
  <h2>My Bookings</h2>
  <a href="<?= BASEURL ?>/rooms" class="btn btn-primary btn-sm">+ New Booking</a>
</div>

<div class="card">
  <?php if (!empty($bookings)): ?>
    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>Booking ID</th>
            <th>Room</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($bookings as $b): ?>
            <tr>
              <td><strong class="booking-code"><?= htmlspecialchars($b['booking_code']) ?></strong></td>
              <td><?= htmlspecialchars($b['room_code']) ?></td>
              <td><?= date('d M Y', strtotime($b['date'])) ?></td>
              <td><?= sprintf('%02d:00 - %02d:00', $b['start_hour'], $b['end_hour']) ?></td>
              <td><span class="badge badge-<?= $b['status'] ?>"><?= ucfirst($b['status']) ?></span></td>
              <td class="action-buttons">
                <a href="<?= BASEURL ?>/booking/<?= $b['booking_code'] ?>" class="btn btn-outline btn-sm">Detail</a>
                <?php if ($b['status'] === 'confirmed'): ?>
                  <form action="<?= BASEURL ?>/cancel/<?= $b['booking_code'] ?>" method="POST" class="inline">
                    <?= CSRF::field() ?>
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Cancel this booking?')">Cancel</button>
                  </form>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?= $pagination->render(BASEURL . '/dashboard') ?>
  <?php else: ?>
    <div class="empty-state">
      <i class="ri-calendar-line"></i>
      <p>You haven't made any bookings yet.</p>
      <a href="<?= BASEURL ?>/rooms" class="btn btn-primary">Book a Room</a>
    </div>
  <?php endif; ?>
</div>