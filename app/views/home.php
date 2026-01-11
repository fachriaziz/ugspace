<main class="landing">
  <!-- Hero Section -->
  <section class="hero" id="home">
    <div class="container">
      <div class="hero-content">
        <h1><span class="text-primary">UGSpace</span><br>Tanpa Batas.</h1>
        <p class="hero-description">
          Platform terintegrasi untuk mempermudah civitas akademika dalam melakukan reservasi ruangan. Transparan, cepat, dan terstruktur.
        </p>
        <div class="hero-actions">
          <a href="<?= BASEURL ?>/register" class="btn btn-primary btn-lg">Mulai Sekarang</a>
          <a href="#features" class="btn btn-secondary btn-lg">Pelajari Fitur</a>
        </div>
      </div>
      <div class="hero-visual">
        <img src="<?= BASEURL ?>/assets/img/book.svg" alt="Booking Illustration" class="hero-img">
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section class="features" id="features">
    <div class="container">
      <div class="section-header text-center">
        <h2>Kenapa UGSpace?</h2>
        <p>Solusi modern untuk manajemen fasilitas kampus.</p>
      </div>
      
      <div class="features-grid">
        <div class="feature-card">
          <div class="icon-wrapper">
            <i class="ri-flashlight-line"></i>
          </div>
          <h3>Akses Instan</h3>
          <p>Cek ketersediaan dan booking ruangan dalam hitungan detik tanpa birokrasi yang rumit.</p>
        </div>

        <div class="feature-card">
          <div class="icon-wrapper">
            <i class="ri-calendar-check-line"></i>
          </div>
          <h3>Real Time Schedule</h3>
          <p>Jadwal ruangan selalu terupdate secara otomatis untuk menghindari bentrok penggunaan.</p>
        </div>

        <div class="feature-card">
          <div class="icon-wrapper">
            <i class="ri-shield-keyhole-line"></i>
          </div>
          <h3>Validasi Aman</h3>
          <p>Setiap reservasi dilengkapi kode unik digital sebagai bukti peminjaman yang sah.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Team Section -->
  <section class="team" id="team">
    <div class="container">
      <div class="section-header text-center">
        <h2>Tim Pengembang</h2>
        <p>Orang-orang dibalik UGSpace.</p>
      </div>
      <div class="team-grid">
        <div class="team-card">
          <div class="avatar">FA</div>
          <h3>Fachri Aziz</h3>
          <p>Lead Developer</p>
        </div>
        <div class="team-card">
          <div class="avatar">YP</div>
          <h3>Yudha Pratama</h3>
          <p>UI/UX Designer</p>
        </div>
        <div class="team-card">
          <div class="avatar">H</div>
          <h3>Hanif</h3>
          <p>Backend Engineer</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="landing-footer">
    <div class="container text-center">
      <p>&copy; <?= date('Y') ?> UGSpace. All rights reserved.</p>
    </div>
  </footer>
</main>