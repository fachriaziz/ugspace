<div class="auth-container">
  <div class="card">
    <h2>Login to UGSpace</h2>
    <form action="<?= BASEURL ?>/login" method="POST">
      <?= CSRF::field() ?>
      <div class="form-group">
        <label class="form-label">NPM</label>
        <input type="text" name="npm" class="form-control" placeholder="Enter your NPM" required>
      </div>
      <div class="form-group">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
      </div>
      <button type="submit" class="btn btn-primary w-full">Login</button>
    </form>
    <p class="auth-footer">Don't have an account? <a href="<?= BASEURL ?>/register">Register here</a></p>
  </div>
</div>