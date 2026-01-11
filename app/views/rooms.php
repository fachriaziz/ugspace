<div class="page-header">
  <h2>Available Rooms</h2>
  <input type="text" id="searchInput" class="form-control search-input" placeholder="Search rooms...">
</div>

<div class="filter-bar">
  <span class="filter-label"><i class="ri-filter-3-line"></i> Room Size:</span>
  <div class="filter-chips">
    <a href="<?= BASEURL ?>/rooms" class="chip <?= empty($sizeFilter) ? 'active' : '' ?>">All</a>
    <a href="<?= BASEURL ?>/rooms?size=small" class="chip <?= ($sizeFilter ?? '') === 'small' ? 'active' : '' ?>">
      <i class="ri-user-line"></i> Small <span class="chip-hint">≤20</span>
    </a>
    <a href="<?= BASEURL ?>/rooms?size=medium" class="chip <?= ($sizeFilter ?? '') === 'medium' ? 'active' : '' ?>">
      <i class="ri-group-line"></i> Medium <span class="chip-hint">21-50</span>
    </a>
    <a href="<?= BASEURL ?>/rooms?size=large" class="chip <?= ($sizeFilter ?? '') === 'large' ? 'active' : '' ?>">
      <i class="ri-team-line"></i> Large <span class="chip-hint">51-100</span>
    </a>
    <a href="<?= BASEURL ?>/rooms?size=xlarge" class="chip <?= ($sizeFilter ?? '') === 'xlarge' ? 'active' : '' ?>">
      <i class="ri-building-line"></i> Extra Large <span class="chip-hint">100+</span>
    </a>
  </div>
</div>

<div class="card">
  <?php if (!empty($rooms)): ?>
    <div class="table-wrapper">
      <table id="roomsTable">
        <thead>
          <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Location</th>
            <th>Capacity</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rooms as $room): ?>
            <tr>
              <td><strong><?= htmlspecialchars($room['code']) ?></strong></td>
              <td><?= htmlspecialchars($room['name']) ?></td>
              <td><?= htmlspecialchars($room['location']) ?></td>
              <td>
                <?php
                $capacityClass = 'xlarge';
                if ($room['capacity'] <= 20) $capacityClass = 'small';
                elseif ($room['capacity'] <= 50) $capacityClass = 'medium';
                elseif ($room['capacity'] <= 100) $capacityClass = 'large';
                ?>
                <span class="capacity-tag <?= $capacityClass ?>">
                  <i class="ri-user-line"></i> <?= $room['capacity'] ?>
                </span>
              </td>
              <td>
                <a href="<?= BASEURL ?>/schedule/<?= $room['id'] ?>" class="btn btn-primary btn-sm">View Schedule</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php
    $paginationUrl = BASEURL . '/rooms';
    if (!empty($sizeFilter)) {
      $paginationUrl .= "?size=$sizeFilter";
    }
    echo $pagination->render($paginationUrl);
    ?>
  <?php else: ?>
    <div class="empty-state">
      <i class="ri-door-line"></i>
      <p>No rooms found with this filter.</p>
      <a href="<?= BASEURL ?>/rooms" class="btn btn-outline">Clear Filter</a>
    </div>
  <?php endif; ?>
</div>