<?php

// UserModel - Model untuk operasi CRUD tabel users
class UserModel
{
  private $db;

  public function __construct(Database $db)
  {
    $this->db = $db;
  }

  // Mencari user berdasarkan NPM
  public function findByNPM($npm)
  {
    $this->db->query('SELECT * FROM users WHERE npm = ?');
    $this->db->bind('s', $npm);
    return $this->db->single();
  }

  // Mencari user berdasarkan email
  public function findByEmail($email)
  {
    $this->db->query('SELECT * FROM users WHERE email = ?');
    $this->db->bind('s', $email);
    return $this->db->single();
  }

  // Mencari user berdasarkan ID
  public function findById($id)
  {
    $this->db->query('SELECT * FROM users WHERE id = ?');
    $this->db->bind('i', $id);
    return $this->db->single();
  }

  // Membuat user baru
  public function create($data)
  {
    $this->db->query("INSERT INTO users (npm, name, email, password_hash) VALUES (?, ?, ?, ?)");
    $this->db->bind('ssss', $data['npm'], $data['name'], $data['email'], $data['password']);
    return $this->db->execute();
  }

  // Mengupdate profil user (nama dan email)
  public function update($id, $name, $email)
  {
    $this->db->query("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $this->db->bind('ssi', $name, $email, $id);
    return $this->db->execute();
  }

  // Mengupdate password user
  public function updatePassword($id, $hash)
  {
    $this->db->query("UPDATE users SET password_hash = ? WHERE id = ?");
    $this->db->bind('si', $hash, $id);
    return $this->db->execute();
  }
}

// RoomModel - Model untuk operasi CRUD tabel rooms
class RoomModel
{
  private $db;

  public function __construct(Database $db)
  {
    $this->db = $db;
  }

  // Menghasilkan SQL filter berdasarkan ukuran ruangan
  private function getSizeFilterSql($sizeFilter)
  {
    return match ($sizeFilter) {
      'small' => ' AND capacity <= 20',
      'medium' => ' AND capacity > 20 AND capacity <= 50',
      'large' => ' AND capacity > 50 AND capacity <= 100',
      'xlarge' => ' AND capacity > 100',
      default => ''
    };
  }

  // Mendapatkan semua ruangan aktif dengan pagination dan filter
  public function getAll($limit = null, $offset = null, $sizeFilter = null)
  {
    $sql = 'SELECT * FROM rooms WHERE active = 1' . $this->getSizeFilterSql($sizeFilter) . ' ORDER BY code ASC';
    $types = '';
    $params = [];

    if ($limit !== null) {
      $sql .= ' LIMIT ? OFFSET ?';
      $types .= 'ii';
      $params[] = $limit;
      $params[] = $offset ?? 0;
    }

    $this->db->query($sql);
    if (!empty($params)) {
      $this->db->bind($types, ...$params);
    }
    return $this->db->resultSet();
  }

  // Menghitung total ruangan aktif dengan filter
  public function countAll($sizeFilter = null)
  {
    $sql = 'SELECT COUNT(*) FROM rooms WHERE active = 1' . $this->getSizeFilterSql($sizeFilter);
    $this->db->query($sql);
    return $this->db->count();
  }

  // Mencari ruangan berdasarkan ID
  public function findById($id)
  {
    $this->db->query('SELECT * FROM rooms WHERE id = ?');
    $this->db->bind('i', $id);
    return $this->db->single();
  }
}

// BookingModel - Model untuk operasi CRUD tabel bookings
class BookingModel
{
  private $db;

  public function __construct(Database $db)
  {
    $this->db = $db;
  }

  // Menghasilkan kode booking unik dengan format UGS_[timestamp]
  private function generateBookingCode()
  {
    return "UGS_" . time();
  }

  // Mendapatkan semua booking milik user dengan detail ruangan
  public function getByUser($userId, $limit = null, $offset = null)
  {
    $sql = 'SELECT b.*, r.code as room_code, r.name as room_name, r.location as room_location 
            FROM bookings b 
            JOIN rooms r ON b.room_id = r.id 
            WHERE b.user_id = ? 
            ORDER BY b.date DESC, b.start_hour ASC';

    if ($limit !== null) {
      $sql .= ' LIMIT ? OFFSET ?';
      $this->db->query($sql);
      $this->db->bind('iii', $userId, $limit, $offset ?? 0);
    } else {
      $this->db->query($sql);
      $this->db->bind('i', $userId);
    }

    return $this->db->resultSet();
  }

  // Menghitung total booking milik user
  public function countByUser($userId)
  {
    $this->db->query('SELECT COUNT(*) FROM bookings WHERE user_id = ?');
    $this->db->bind('i', $userId);
    return $this->db->count();
  }

  /**
   * Mendapatkan booking yang terkonfirmasi untuk ruangan pada tanggal tertentu
   * Digunakan untuk menampilkan jadwal ruangan
   */
  public function getByRoomAndDate($roomId, $date)
  {
    $this->db->query("SELECT * FROM bookings WHERE room_id = ? AND date = ? AND status = 'confirmed'");
    $this->db->bind('is', $roomId, $date);
    return $this->db->resultSet();
  }

  // Mencari booking berdasarkan kode booking
  public function findByCode($code)
  {
    $this->db->query('SELECT * FROM bookings WHERE booking_code = ?');
    $this->db->bind('s', $code);
    return $this->db->single();
  }

  // Mencari booking dengan detail lengkap (ruangan dan user)
  public function findByCodeWithDetails($code)
  {
    $this->db->query('SELECT b.*, r.code as room_code, r.name as room_name, r.location as room_location, r.capacity as room_capacity,
                      u.name as user_name, u.npm as user_npm, u.email as user_email
                      FROM bookings b 
                      JOIN rooms r ON b.room_id = r.id 
                      JOIN users u ON b.user_id = u.id
                      WHERE b.booking_code = ?');
    $this->db->bind('s', $code);
    return $this->db->single();
  }

  // Mengecek apakah ada konflik jadwal pada ruangan
  public function hasRoomConflict($roomId, $date, $start, $end)
  {
    $this->db->query("SELECT id FROM bookings WHERE room_id = ? AND date = ? AND status = 'confirmed' AND start_hour < ? AND end_hour > ?");
    $this->db->bind('isii', $roomId, $date, $end, $start);
    return $this->db->single() !== null;
  }

  // Mengecek apakah user sudah punya booking di waktu yang sama
  public function hasUserConflict($userId, $date, $start, $end)
  {
    $this->db->query("SELECT id FROM bookings WHERE user_id = ? AND date = ? AND status = 'confirmed' AND start_hour < ? AND end_hour > ?");
    $this->db->bind('isii', $userId, $date, $end, $start);
    return $this->db->single() !== null;
  }

  // Membuat booking baru dengan status confirmed
  public function create($data)
  {
    $bookingCode = $this->generateBookingCode();
    $this->db->query("INSERT INTO bookings (booking_code, user_id, room_id, date, start_hour, end_hour, purpose, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'confirmed')");
    $this->db->bind('siisiss', $bookingCode, $data['user_id'], $data['room_id'], $data['date'], $data['start_hour'], $data['end_hour'], $data['purpose']);
    return $this->db->execute() ? $bookingCode : false;
  }

  // Membatalkan booking (mengubah status menjadi cancelled)
  public function cancel($code)
  {
    $this->db->query("UPDATE bookings SET status = 'cancelled' WHERE booking_code = ?");
    $this->db->bind('s', $code);
    return $this->db->execute();
  }
}
