<h2 class="mb-4">My Profile</h2>

<div class="profile-grid">
  <div class="card">
    <h3>Profile Information</h3>
    <div class="profile-info">
      <div class="info-item">
        <label>NPM</label>
        <p><?= htmlspecialchars($user['npm']) ?></p>
      </div>
      <div class="info-item">
        <label>Name</label>
        <p><?= htmlspecialchars($user['name']) ?></p>
      </div>
      <div class="info-item">
        <label>Email</label>
        <p><?= htmlspecialchars($user['email']) ?></p>
      </div>
      <div class="info-item">
        <label>Member Since</label>
        <p><?= date('d M Y', strtotime($user['created_at'])) ?></p>
      </div>
    </div>
  </div>

  <div class="card">
    <h3>Edit Profile</h3>
    <form method="POST" action="<?= BASEURL ?>/profile/update">
      <?= CSRF::field() ?>
      <div class="form-group">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
      </div>
      <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
      </div>
      <div class="form-group">
        <label class="form-label">Current Password</label>
        <input type="password" name="current_password" class="form-control" placeholder="Enter current password to save changes" required>
      </div>
      <div class="form-group">
        <label class="form-label">New Password <span class="text-muted">(optional)</span></label>
        <input type="password" name="new_password" class="form-control" placeholder="Leave blank to keep current">
      </div>
      <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
  </div>
</div>