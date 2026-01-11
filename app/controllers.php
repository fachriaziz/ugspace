<?php

// Controller - Main controller yang menangani semua request
class Controller
{
  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  /**
   * Menampilkan halaman landing/home
   * Hanya bisa diakses oleh guest (belum login)
   */
  public function home()
  {
    Auth::guest();
    $this->view('home');
  }

  // Menampilkan form login dan memproses login
  public function login()
  {
    Auth::guest();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // Validasi CSRF token
      if (!CSRF::verify()) {
        return header('Location: ' . BASEURL . '/login');
      }

      // Validasi input tidak kosong
      if (empty($_POST['npm']) || empty($_POST['password'])) {
        Flash::set('Please fill in all fields.', 'warning');
        return header('Location: ' . BASEURL . '/login');
      }

      // Cari user berdasarkan NPM
      $user = (new UserModel($this->db))->findByNPM($_POST['npm']);

      // Verifikasi password
      if ($user && password_verify($_POST['password'], $user['password_hash'])) {
        // Cek status akun
        if (!$user['active']) {
          Flash::set('Account inactive.', 'danger');
          return header('Location: ' . BASEURL . '/login');
        }
        // Set session dan redirect ke dashboard
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        CSRF::refresh();  // Regenerasi CSRF token setelah login
        return header('Location: ' . BASEURL . '/dashboard');
      }

      Flash::set('Invalid credentials.', 'danger');
      return header('Location: ' . BASEURL . '/login');
    }

    $this->view('login');
  }

  // Menampilkan form register dan memproses registrasi
  public function register()
  {
    Auth::guest();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // Validasi CSRF token
      if (!CSRF::verify()) {
        return header('Location: ' . BASEURL . '/register');
      }

      // Validasi semua field terisi
      if (empty($_POST['npm']) || empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password'])) {
        Flash::set('Please fill in all fields.', 'warning');
        return header('Location: ' . BASEURL . '/register');
      }

      $userModel = new UserModel($this->db);

      // Cek duplikasi email atau NPM
      if ($userModel->findByEmail($_POST['email']) || $userModel->findByNPM($_POST['npm'])) {
        Flash::set('Email or NPM already exists.', 'warning');
        return header('Location: ' . BASEURL . '/register');
      }

      // Buat user baru dengan password ter-hash
      $userModel->create([
        'npm' => $_POST['npm'],
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'password' => password_hash($_POST['password'], PASSWORD_DEFAULT)
      ]);

      Flash::set('Account created! Please login.', 'success');
      return header('Location: ' . BASEURL . '/login');
    }

    $this->view('register');
  }

  // Logout user dengan menghancurkan session
  public function logout()
  {
    session_destroy();
    header('Location: ' . BASEURL);
  }

  /**
   * Menampilkan dashboard user dengan daftar booking
   * Menampilkan booking milik user yang sedang login dengan pagination
   */
  public function dashboard()
  {
    Auth::require();

    $bookingModel = new BookingModel($this->db);
    $page = (int) ($_GET['page'] ?? 1);
    $total = $bookingModel->countByUser($_SESSION['user_id']);
    $pagination = new Pagination($total, $page);

    // Ambil booking dengan pagination
    $bookings = $bookingModel->getByUser($_SESSION['user_id'], $pagination->perPage, $pagination->offset());

    $this->view('dashboard', [
      'bookings' => $bookings,
      'pagination' => $pagination
    ]);
  }

  // Menampilkan detail booking berdasarkan kode
  public function bookingDetail($code)
  {
    Auth::require();

    $booking = (new BookingModel($this->db))->findByCodeWithDetails($code);

    // Validasi kepemilikan booking
    if (!$booking || $booking['user_id'] != $_SESSION['user_id']) {
      Flash::set('Booking not found.', 'danger');
      return header('Location: ' . BASEURL . '/dashboard');
    }

    $this->view('booking-detail', ['booking' => $booking]);
  }

  // Menampilkan halaman profil user
  public function profile()
  {
    Auth::require();
    $user = (new UserModel($this->db))->findById($_SESSION['user_id']);
    $this->view('profile', ['user' => $user]);
  }

  // Memproses update profil user
  public function profileUpdate()
  {
    Auth::require();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (!CSRF::verify()) {
        return header('Location: ' . BASEURL . '/profile');
      }

      $userModel = new UserModel($this->db);
      $user = $userModel->findById($_SESSION['user_id']);

      // Verifikasi password saat ini
      if (!password_verify($_POST['current_password'], $user['password_hash'])) {
        Flash::set('Wrong password.', 'danger');
        return header('Location: ' . BASEURL . '/profile');
      }

      // Cek email tidak digunakan user lain
      $existing = $userModel->findByEmail($_POST['email']);
      if ($existing && $existing['id'] != $_SESSION['user_id']) {
        Flash::set('Email already used.', 'warning');
        return header('Location: ' . BASEURL . '/profile');
      }

      // Update nama dan email
      $userModel->update($_SESSION['user_id'], $_POST['name'], $_POST['email']);
      $_SESSION['user_name'] = $_POST['name'];

      // Update password jika diisi
      if (!empty($_POST['new_password'])) {
        $userModel->updatePassword($_SESSION['user_id'], password_hash($_POST['new_password'], PASSWORD_DEFAULT));
      }

      Flash::set('Profile updated!', 'success');
    }
    header('Location: ' . BASEURL . '/profile');
  }

  // Menampilkan daftar ruangan dengan filter dan pagination
  public function rooms()
  {
    Auth::require();

    $roomModel = new RoomModel($this->db);
    $page = (int) ($_GET['page'] ?? 1);
    $sizeFilter = $_GET['size'] ?? null;

    // Validasi filter ukuran
    if ($sizeFilter && !in_array($sizeFilter, ['small', 'medium', 'large', 'xlarge'])) {
      $sizeFilter = null;
    }

    $total = $roomModel->countAll($sizeFilter);
    $pagination = new Pagination($total, $page);

    $rooms = $roomModel->getAll($pagination->perPage, $pagination->offset(), $sizeFilter);

    $this->view('rooms', [
      'rooms' => $rooms,
      'pagination' => $pagination,
      'sizeFilter' => $sizeFilter
    ]);
  }

  // Menampilkan jadwal ruangan pada tanggal tertentu
  public function schedule($id)
  {
    Auth::require();

    $room = (new RoomModel($this->db))->findById($id);
    if (!$room) return header('Location: ' . BASEURL . '/rooms');

    $date = $_GET['date'] ?? date('Y-m-d');
    $today = date('Y-m-d');
    $currentHour = (int) date('H');

    // Tidak boleh memilih tanggal lampau
    if ($date < $today) {
      $date = $today;
    }

    $bookings = (new BookingModel($this->db))->getByRoomAndDate($id, $date);

    // Slot waktu (07:00 - 17:00)
    $slots = [];
    for ($i = 7; $i < 17; $i++) {
      // Jika hari ini, tandai jam yang sudah lewat
      if ($date === $today && $i <= $currentHour) {
        $slots[$i] = 'passed';
      } else {
        $slots[$i] = 'available';
      }
    }

    // Tandai slot yang sudah dibooking
    foreach ($bookings as $b) {
      for ($h = $b['start_hour']; $h < $b['end_hour']; $h++) {
        if (isset($slots[$h]) && $slots[$h] !== 'passed') {
          $slots[$h] = 'booked';
        }
      }
    }

    $this->view('schedule', [
      'room' => $room,
      'date' => $date,
      'slots' => $slots,
      'today' => $today
    ]);
  }


  // Memproses pembuatan booking baru
  public function book()
  {
    Auth::require();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (!CSRF::verify()) {
        Flash::set('Invalid request.', 'danger');
        return header('Location: ' . BASEURL . '/rooms');
      }

      // Parse input
      $roomId = (int) $_POST['room_id'];
      $date = $_POST['date'];
      $start = (int) $_POST['start_hour'];
      $duration = (int) $_POST['duration'];
      $end = $start + $duration;

      $today = date('Y-m-d');
      $currentHour = (int) date('H');

      // Validasi: tidak boleh booking di tanggal lampau
      if ($date < $today) {
        Flash::set('Cannot book for past dates.', 'danger');
        return header("Location: " . BASEURL . "/schedule/$roomId?date=$today");
      }

      // Validasi: jika hari ini, tidak boleh booking jam yang sudah lewat
      if ($date === $today && $start <= $currentHour) {
        Flash::set('Cannot book past time slots.', 'danger');
        return header("Location: " . BASEURL . "/schedule/$roomId?date=$date");
      }

      // Validasi: durasi maksimal 3 jam
      if ($duration > 3 || $duration < 1) {
        Flash::set('Duration must be 1-3 hours.', 'danger');
        return header("Location: " . BASEURL . "/schedule/$roomId?date=$date");
      }

      // Validasi: jam selesai tidak melebihi jam operasional (17:00)
      if ($end > 17) {
        Flash::set('Booking cannot exceed 17:00.', 'danger');
        return header("Location: " . BASEURL . "/schedule/$roomId?date=$date");
      }

      $bookingModel = new BookingModel($this->db);

      // Cek konflik ruangan (ruangan sudah dibooking)
      if ($bookingModel->hasRoomConflict($roomId, $date, $start, $end)) {
        Flash::set('Time slot not available for this room.', 'danger');
        return header("Location: " . BASEURL . "/schedule/$roomId?date=$date");
      }

      // Cek konflik user (user sudah punya booking di waktu yang sama)
      if ($bookingModel->hasUserConflict($_SESSION['user_id'], $date, $start, $end)) {
        Flash::set('You already have a booking at this time.', 'warning');
        return header("Location: " . BASEURL . "/schedule/$roomId?date=$date");
      }

      // Simpan booking
      $bookingModel->create([
        'user_id' => $_SESSION['user_id'],
        'room_id' => $roomId,
        'date' => $date,
        'start_hour' => $start,
        'end_hour' => $end,
        'purpose' => $_POST['purpose']
      ]);

      Flash::set('Booking confirmed!', 'success');
      return header('Location: ' . BASEURL . '/dashboard');
    }

    header('Location: ' . BASEURL . '/rooms');
  }

  // Membatalkan booking berdasarkan kode
  public function cancelBooking($code)
  {
    Auth::require();

    // Hanya terima POST request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      return header('Location: ' . BASEURL . '/dashboard');
    }

    if (!CSRF::verify()) {
      Flash::set('Invalid request.', 'danger');
      return header('Location: ' . BASEURL . '/dashboard');
    }

    $bookingModel = new BookingModel($this->db);
    $booking = $bookingModel->findByCode($code);

    // Validasi kepemilikan booking
    if (!$booking || $booking['user_id'] != $_SESSION['user_id']) {
      Flash::set('Unauthorized.', 'danger');
      return header('Location: ' . BASEURL . '/dashboard');
    }

    // Batalkan jika masih confirmed
    if ($booking['status'] === 'confirmed') {
      $bookingModel->cancel($code);
      Flash::set('Booking cancelled.', 'success');
    }

    header('Location: ' . BASEURL . '/dashboard');
  }

  // Merender view dengan data
  private function view($name, $data = [])
  {
    extract($data);
    require_once __DIR__ . '/views/layout.php';
  }
}
