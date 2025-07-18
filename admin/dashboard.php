<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if admin is logged in
require_admin();

$page_title = 'Dashboard Admin';

// Get statistics
$stats = [];

// Guest book statistics
$stmt = $pdo->query("SELECT COUNT(*) FROM guest_book");
$stats['total_guests'] = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM guest_book WHERE DATE(created_at) = CURDATE()");
$stats['guests_today'] = $stmt->fetchColumn();

// Dispensation statistics
$stmt = $pdo->query("SELECT COUNT(*) FROM dispensation");
$stats['total_dispensations'] = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM dispensation WHERE DATE(created_at) = CURDATE()");
$stats['dispensations_today'] = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM dispensation WHERE status = 'pending'");
$stats['pending_dispensations'] = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM dispensation WHERE status = 'approved'");
$stats['approved_dispensations'] = $stmt->fetchColumn();

// Handle status update
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $id = (int)$_POST['id'];
    $status = sanitize_input($_POST['status']);
    
    if (in_array($status, ['pending', 'approved', 'rejected'])) {
        try {
            $stmt = $pdo->prepare("UPDATE dispensation SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            $success_message = 'Status berhasil diperbarui';
        } catch (PDOException $e) {
            $error_message = 'Gagal memperbarui status';
        }
    }
}

// Handle delete
if (isset($_GET['delete']) && isset($_GET['type'])) {
    $id = (int)$_GET['delete'];
    $type = $_GET['type'];
    
    if ($type === 'guest' && $id > 0) {
        try {
            $stmt = $pdo->prepare("DELETE FROM guest_book WHERE id = ?");
            $stmt->execute([$id]);
            $success_message = 'Data buku tamu berhasil dihapus';
        } catch (PDOException $e) {
            $error_message = 'Gagal menghapus data';
        }
    } elseif ($type === 'dispensation' && $id > 0) {
        try {
            $stmt = $pdo->prepare("DELETE FROM dispensation WHERE id = ?");
            $stmt->execute([$id]);
            $success_message = 'Data dispensasi berhasil dihapus';
        } catch (PDOException $e) {
            $error_message = 'Gagal menghapus data';
        }
    }
}

                // Get recent data
$recent_guests = $pdo->query("SELECT * FROM guest_book ORDER BY created_at DESC LIMIT 10")->fetchAll();
$recent_dispensations = $pdo->query("SELECT * FROM dispensation ORDER BY created_at DESC LIMIT 10")->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title . ' - ' . SITE_NAME; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="admin-panel">
    <!-- Admin Header -->
    <div class="admin-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 class="mb-0">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard Admin
                    </h2>
                    <p class="mb-0 opacity-75">Selamat datang, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="../index.php" class="btn btn-outline-light me-2">
                        <i class="fas fa-home me-1"></i>Beranda
                    </a>
                    <a href="logout.php" class="btn btn-light">
                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <!-- Alert Messages -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo $success_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-number text-primary"><?php echo $stats['total_guests']; ?></div>
                    <div class="stats-label">Total Buku Tamu</div>
                    <small class="text-muted">
                        <i class="fas fa-calendar-day me-1"></i>
                        Hari ini: <?php echo $stats['guests_today']; ?>
                    </small>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-number text-info"><?php echo $stats['total_dispensations']; ?></div>
                    <div class="stats-label">Total Dispensasi</div>
                    <small class="text-muted">
                        <i class="fas fa-calendar-day me-1"></i>
                        Hari ini: <?php echo $stats['dispensations_today']; ?>
                    </small>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-number text-warning"><?php echo $stats['pending_dispensations']; ?></div>
                    <div class="stats-label">Dispensasi Pending</div>
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i>
                        Menunggu persetujuan
                    </small>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-number text-success"><?php echo $stats['approved_dispensations']; ?></div>
                    <div class="stats-label">Dispensasi Disetujui</div>
                    <small class="text-muted">
                        <i class="fas fa-check me-1"></i>
                        Sudah disetujui
                    </small>
                </div>
            </div>
        </div>

        <!-- Quick Access Cards -->
        <div class="row mb-4">
            <div class="col-lg-6 mb-4">
                <div class="card h-100 border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-book me-2"></i>
                            Kelola Buku Tamu
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Kelola semua data buku tamu yang masuk. Lihat detail pengunjung, hapus data, dan export ke Excel.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="text-primary mb-0"><?php echo $stats['total_guests']; ?></h4>
                                <small class="text-muted">Total Buku Tamu</small>
                            </div>
                            <a href="guest_book.php" class="btn btn-primary">
                                <i class="fas fa-arrow-right me-1"></i>Kelola
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4">
                <div class="card h-100 border-info">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-file-alt me-2"></i>
                            Kelola Dispensasi
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Kelola semua permohonan dispensasi. Update status, lihat foto bukti, dan export data.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="text-info mb-0"><?php echo $stats['total_dispensations']; ?></h4>
                                <small class="text-muted">Total Dispensasi</small>
                            </div>
                            <a href="dispensation.php" class="btn btn-info">
                                <i class="fas fa-arrow-right me-1"></i>Kelola
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-clock me-2"></i>
                            Buku Tamu Terbaru
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if ($recent_guests): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach (array_slice($recent_guests, 0, 5) as $guest): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold"><?php echo htmlspecialchars($guest['name']); ?></div>
                                            <small class="text-muted"><?php echo htmlspecialchars($guest['institution']); ?></small>
                                        </div>
                                        <small class="text-muted"><?php echo format_date($guest['created_at']); ?></small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="text-center mt-3">
                                <a href="guest_book.php" class="btn btn-outline-primary btn-sm">
                                    Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-3">
                                <i class="fas fa-book text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mt-2">Belum ada data buku tamu</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-clock me-2"></i>
                            Dispensasi Terbaru
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if ($recent_dispensations): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach (array_slice($recent_dispensations, 0, 5) as $dispensation): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold"><?php echo htmlspecialchars($dispensation['name']); ?></div>
                                            <small class="text-muted"><?php echo htmlspecialchars($dispensation['department']); ?></small>
                                        </div>
                                        <div class="text-end">
                                            <?php echo get_status_badge($dispensation['status']); ?>
                                            <br><small class="text-muted"><?php echo format_date($dispensation['created_at']); ?></small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="text-center mt-3">
                                <a href="dispensation.php" class="btn btn-outline-info btn-sm">
                                    Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-3">
                                <i class="fas fa-file-alt text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mt-2">Belum ada data dispensasi</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="../assets/js/script.js"></script>
    
    <script>
        // Search functionality
        document.getElementById('searchGuests').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#guestsTable tbody tr');
            
            tableRows.forEach(function(row) {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        document.getElementById('searchDispensations').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#dispensationsTable tbody tr');
            
            tableRows.forEach(function(row) {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Status update
        document.querySelectorAll('.status-select').forEach(function(select) {
            select.addEventListener('change', function() {
                const id = this.dataset.id;
                const status = this.value;
                
                const formData = new FormData();
                formData.append('action', 'update_status');
                formData.append('id', id);
                formData.append('status', status);
                
                fetch('dashboard.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memperbarui status');
                });
            });
        });
    </script>
</body>
</html>
