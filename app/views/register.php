<div class="auth-container">
  <div class="card">
    <h2>Register for UGSpace</h2>
    <form action="<?= BASEURL ?>/register" method="POST">
      <?= CSRF::field() ?>
      <div class="form-group">
        <label class="form-label">NPM</label>
        <input type="text" name="npm" class="form-control" placeholder="Enter your NPM" required>
      </div>
      <div class="form-group">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
      </div>
      <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
      </div>
      <div class="form-group">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Create a password" required>
      </div>
      <button type="submit" class="btn btn-primary w-full">Register</button>
    </form>
    <p class="auth-footer">Already have an account? <a href="<?= BASEURL ?>/login">Login here</a></p>
  </div>
</div>