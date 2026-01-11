<?php
class Database
{
  private $conn;  // Koneksi mysqli
  private $stmt;  // Prepared statement saat ini

  // Inisialisasi koneksi database
  public function __construct()
  {
    $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($this->conn->connect_error) {
      die("Database connection failed: " . $this->conn->connect_error);
    }
    $this->conn->set_charset("utf8mb4");
  }

  // Menyiapkan query SQL dengan prepared statement
  public function query($query)
  {
    $this->stmt = $this->conn->prepare($query);
    return $this;
  }

  // Mengikat parameter ke prepared statement
  public function bind($types, ...$params)
  {
    $bindParams = [$types];
    foreach ($params as $key => $value) {
      $bindParams[] = &$params[$key];
    }
    call_user_func_array([$this->stmt, 'bind_param'], $bindParams);
    return $this;
  }

  // Mengeksekusi prepared statement
  public function execute()
  {
    return $this->stmt->execute();
  }

  // Mengeksekusi query dan mengembalikan semua hasil sebagai array asosiatif
  public function resultSet()
  {
    $this->execute();
    return $this->stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  }

  // Mengeksekusi query dan mengembalikan satu baris hasil
  public function single()
  {
    $this->execute();
    return $this->stmt->get_result()->fetch_assoc();
  }

  // Mengeksekusi query COUNT dan mengembalikan jumlahnya
  public function count()
  {
    $this->execute();
    $result = $this->stmt->get_result()->fetch_assoc();
    return $result ? (int) reset($result) : 0;
  }
}

// Flash - Mengelola pesan flash/notifikasi yang ditampilkan sekali
class Flash
{
  // Menyimpan pesan flash ke session
  public static function set($message, $type = 'success')
  {
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
  }

  // Menampilkan pesan flash dan menghapusnya dari session
  public static function show()
  {
    if (isset($_SESSION['flash'])) {
      $f = $_SESSION['flash'];
      $message = htmlspecialchars($f['message']);
      $type = htmlspecialchars($f['type']);
      echo "<div class=\"alert alert-{$type}\">{$message}</div>";
      unset($_SESSION['flash']);
    }
  }
}

// Auth - Mengelola autentikasi dan otorisasi pengguna
class Auth
{
  // Mengecek apakah pengguna sudah login
  public static function check()
  {
    return isset($_SESSION['user_id']);
  }

  // Mendapatkan data pengguna yang sedang login
  public static function user()
  {
    return self::check() ? [
      'id' => $_SESSION['user_id'],
      'name' => $_SESSION['user_name']
    ] : null;
  }

  // Middleware: Memaksa pengguna untuk login
  public static function require()
  {
    if (!self::check()) {
      header('Location: ' . BASEURL . '/login');
      exit;
    }
  }

  // Middleware: Memaksa pengguna sebagai guest (belum login)
  public static function guest()
  {
    if (self::check()) {
      header('Location: ' . BASEURL . '/dashboard');
      exit;
    }
  }
}

// CSRF - Mengelola proteksi Cross-Site Request Forgery
class CSRF
{
  // Menghasilkan token CSRF baru jika belum ada
  public static function generate()
  {
    if (empty($_SESSION['csrf_token'])) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
  }

  // Menghasilkan hidden input field dengan token CSRF
  public static function field()
  {
    return '<input type="hidden" name="csrf_token" value="' . self::generate() . '">';
  }

  // Memvalidasi token CSRF dari request
  public static function verify($token = null)
  {
    $token = $token ?? ($_POST['csrf_token'] ?? '');
    if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
      Flash::set('Invalid request. Please try again.', 'danger');
      return false;
    }
    return true;
  }

  // Membuat token CSRF baru (untuk regenerasi setelah login)
  public static function refresh()
  {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
}

// Pagination - Mengelola paginasi data
class Pagination
{
  public $page;       // Halaman saat ini
  public $perPage;    // Jumlah item per halaman
  public $total;      // Total item
  public $totalPages; // Total halaman

  // Inisialisasi pagination
  public function __construct($total, $page = 1, $perPage = null)
  {
    $this->perPage = $perPage ?? PER_PAGE;
    $this->total = $total;
    $this->totalPages = max(1, ceil($total / $this->perPage));
    $this->page = max(1, min($page, $this->totalPages));
  }

  // Menghitung offset untuk query SQL LIMIT
  public function offset()
  {
    return ($this->page - 1) * $this->perPage;
  }

  // Menghasilkan HTML navigation pagination
  public function render($baseUrl)
  {
    if ($this->totalPages <= 1) return '';

    $html = '<div class="pagination">';

    // Previous
    if ($this->page > 1) {
      $html .= '<a href="' . $baseUrl . '?page=' . ($this->page - 1) . '" class="page-link">&laquo;</a>';
    }

    // Page numbers
    for ($i = 1; $i <= $this->totalPages; $i++) {
      if ($i == $this->page) {
        $html .= '<span class="page-link active">' . $i . '</span>';
      } else {
        $html .= '<a href="' . $baseUrl . '?page=' . $i . '" class="page-link">' . $i . '</a>';
      }
    }

    // Next
    if ($this->page < $this->totalPages) {
      $html .= '<a href="' . $baseUrl . '?page=' . ($this->page + 1) . '" class="page-link">&raquo;</a>';
    }

    $html .= '</div>';
    return $html;
  }
}
