<?php
require_once 'includes/config.example.php';
require_once 'includes/functions.php';

$page_title = 'Beranda';
?>

<?php include 'includes/header.php'; ?>

<main class="main-content">
    <div class="container">
        <!-- Welcome Section -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="display-5 mb-3 fade-in-up">Pilih Layanan</h2>
                <p class="lead text-muted fade-in-up">Silakan pilih layanan yang Anda butuhkan</p>
            </div>
        </div>

        <!-- Service Cards -->
        <div class="row g-4 mb-5">
            <!-- Guest Book Card -->
            <div class="col-lg-6">
                <div class="service-card h-100 fade-in-up">
                    <div class="card-body">
                        <div class="service-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <h3>Buku Tamu</h3>
                        <p>Daftarkan kunjungan Anda dan berikan kesan serta pesan untuk pelayanan kami. Sistem buku tamu digital yang mudah dan praktis.</p>
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>Estimasi: 2-3 menit
                            </small>
                        </div>
                        <a href="guest_book.php" class="btn btn-primary btn-lg">
                            <i class="fas fa-edit me-2"></i>Isi Buku Tamu
                        </a>
                    </div>
                </div>
            </div>

            <!-- Dispensation Card -->
            <div class="col-lg-6">
                <div class="service-card h-100 fade-in-up">
                    <div class="card-body">
                        <div class="service-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3>Dispensasi</h3>
                        <p>Ajukan permohonan dispensasi untuk keperluan resmi. Proses pengajuan yang cepat dengan sistem tracking status real-time.</p>
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>Estimasi: 5-7 menit
                            </small>
                        </div>
                        <a href="dispensation.php" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-paper-plane me-2"></i>Ajukan Dispensasi
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Information Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 bg-light">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <h4 class="mb-3">
                                    <i class="fas fa-info-circle text-primary me-2"></i>
                                    Informasi Penting
                                </h4>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Pastikan data yang diisi sudah benar dan lengkap
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Untuk dispensasi, mohon sertakan alasan yang jelas
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Status dispensasi dapat dipantau melalui admin
                                    </li>
                                    <li class="mb-0">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Semua data akan dijaga kerahasiaannya
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-4 text-center">
                                <i class="fas fa-shield-alt text-primary" style="font-size: 4rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="row mb-5">
            <div class="col-12 text-center mb-4">
                <h3>Statistik Layanan</h3>
                <p class="text-muted">Data penggunaan sistem hari ini</p>
            </div>
            
            <?php
            // Get today's statistics
            $today = date('Y-m-d');
            
            // Guest book count today
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM guest_book WHERE DATE(created_at) = ?");
            $stmt->execute([$today]);
            $guest_today = $stmt->fetchColumn();
            
            // Dispensation count today
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM dispensation WHERE DATE(created_at) = ?");
            $stmt->execute([$today]);
            $dispensation_today = $stmt->fetchColumn();
            
            // Total guest book
            $stmt = $pdo->query("SELECT COUNT(*) FROM guest_book");
            $total_guests = $stmt->fetchColumn();
            
            // Total dispensation
            $stmt = $pdo->query("SELECT COUNT(*) FROM dispensation");
            $total_dispensations = $stmt->fetchColumn();
            ?>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $guest_today; ?></div>
                    <div class="stats-label">Buku Tamu Hari Ini</div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $dispensation_today; ?></div>
                    <div class="stats-label">Dispensasi Hari Ini</div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $total_guests; ?></div>
                    <div class="stats-label">Total Buku Tamu</div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $total_dispensations; ?></div>
                    <div class="stats-label">Total Dispensasi</div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="row">
            <div class="col-12">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-phone me-2"></i>
                            Butuh Bantuan?
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center mb-3">
                                <i class="fas fa-phone-alt text-primary mb-2" style="font-size: 2rem;"></i>
                                <h6>Telepon</h6>
                                <p class="text-muted mb-0">(021) 123-4567</p>
                            </div>
                            <div class="col-md-4 text-center mb-3">
                                <i class="fas fa-envelope text-primary mb-2" style="font-size: 2rem;"></i>
                                <h6>Email</h6>
                                <p class="text-muted mb-0">info@pemerintahan.go.id</p>
                            </div>
                            <div class="col-md-4 text-center mb-3">
                                <i class="fas fa-clock text-primary mb-2" style="font-size: 2rem;"></i>
                                <h6>Jam Layanan</h6>
                                <p class="text-muted mb-0">08:00 - 16:00 WIB</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. Semua hak dilindungi.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p>Dikembangkan untuk pelayanan publik yang lebih baik</p>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="assets/js/script.js"></script>

</body>
</html>
