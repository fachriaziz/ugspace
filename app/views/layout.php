<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UGSpace</title>
  <link rel="icon" href="<?= BASEURL ?>/assets/img/logo.svg" type="image/svg+xml">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASEURL ?>/assets/css/style.css">
</head>

<body class="<?= $name === 'home' ? 'landing-page' : '' ?>">
  <nav class="navbar">
    <a href="<?= BASEURL ?>" class="nav-brand">
      <img src="<?= BASEURL ?>/assets/img/logo.svg" alt="Logo" class="nav-logo">
      UGSpace
    </a>
    <div class="nav-menu">
      <?php if (Auth::check()): ?>
        <a href="<?= BASEURL ?>/dashboard" class="nav-link">My Bookings</a>
        <a href="<?= BASEURL ?>/rooms" class="nav-link">Rooms</a>
        <a href="<?= BASEURL ?>/profile" class="nav-link">Profile</a>
        <span class="nav-link nav-user">Hi, <?= explode(' ', Auth::user()['name'])[0] ?></span>
        <a href="<?= BASEURL ?>/logout" class="btn btn-outline btn-sm">Logout</a>
      <?php else: ?>
        <a href="<?= BASEURL ?>/#home" class="nav-link">Home</a>
        <a href="<?= BASEURL ?>/#features" class="nav-link">Fitur</a>
        <a href="<?= BASEURL ?>/#team" class="nav-link">Team</a>
        <a href="<?= BASEURL ?>/login" class="btn btn-primary btn-sm">Login</a>
      <?php endif; ?>
    </div>
  </nav>

  <?php if ($name === 'home'): ?>
    <?php Flash::show(); ?>
    <?php require_once __DIR__ . "/$name.php"; ?>
  <?php else: ?>
    <div class="container">
      <?php Flash::show(); ?>
      <?php require_once __DIR__ . "/$name.php"; ?>
    </div>
  <?php endif; ?>

  <script src="<?= BASEURL ?>/assets/js/app.js"></script>
</body>

</html>