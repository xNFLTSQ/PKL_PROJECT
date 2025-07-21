<?php
require_once '../includes/config.example.php';
require_once '../includes/functions.php';

// Check if admin is logged in
require_admin();

$page_title = 'Kelola Buku Tamu';

// Handle delete
if (isset($_GET['delete']) && $_GET['delete'] > 0) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM guest_book WHERE id = ?");
        $stmt->execute([$id]);
        $success_message = 'Data buku tamu berhasil dihapus';
    } catch (PDOException $e) {
        $error_message = 'Gagal menghapus data';
    }
}

// Get all guest book data
$stmt = $pdo->query("SELECT * FROM guest_book ORDER BY created_at DESC");
$guests = $stmt->fetchAll();

// Get statistics
$total_guests = count($guests);
$guests_today = 0;
foreach ($guests as $guest) {
    if (date('Y-m-d', strtotime($guest['created_at'])) === date('Y-m-d')) {
        $guests_today++;
    }
}
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
                        <i class="fas fa-book me-2"></i>
                        Kelola Buku Tamu
                    </h2>
                    <p class="mb-0 opacity-75">Selamat datang, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="dashboard.php" class="btn btn-outline-light me-2">
                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                    <a href="dispensation.php" class="btn btn-outline-light me-2">
                        <i class="fas fa-file-alt me-1"></i>Dispensasi
                    </a>
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
            <div class="col-lg-6 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-number text-primary"><?php echo $total_guests; ?></div>
                    <div class="stats-label">Total Buku Tamu</div>
                    <small class="text-muted">
                        <i class="fas fa-calendar-day me-1"></i>
                        Semua data buku tamu
                    </small>
                </div>
            </div>
            
            <div class="col-lg-6 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-number text-success"><?php echo $guests_today; ?></div>
                    <div class="stats-label">Buku Tamu Hari Ini</div>
                    <small class="text-muted">
                        <i class="fas fa-calendar-day me-1"></i>
                        Data hari ini: <?php echo date('d/m/Y'); ?>
                    </small>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Data Buku Tamu</h4>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success btn-sm" onclick="exportToExcel('guestsTable', 'Data_Buku_Tamu')">
                            <i class="fas fa-file-excel me-1"></i>Export Excel
                        </button>
                        <input type="text" id="searchGuests" class="form-control" placeholder="Cari buku tamu..." style="width: 250px;">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="guestsTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Instansi</th>
                                <th>Pesan</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($guests as $index => $guest): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><strong><?php echo htmlspecialchars($guest['name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($guest['email']); ?></td>
                                    <td><?php echo htmlspecialchars($guest['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($guest['institution']); ?></td>
                                    <td>
                                        <span data-bs-toggle="tooltip" title="<?php echo htmlspecialchars($guest['message']); ?>">
                                            <?php echo htmlspecialchars(substr($guest['message'], 0, 50)); ?>...
                                        </span>
                                    </td>
                                    <td>
                                        <small><?php echo format_date($guest['created_at']); ?></small>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewGuestModal<?php echo $guest['id']; ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="?delete=<?php echo $guest['id']; ?>" 
                                           class="btn btn-sm btn-danger btn-delete" 
                                           data-name="<?php echo htmlspecialchars($guest['name']); ?>">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                                <!-- View Guest Modal -->
                                <div class="modal fade" id="viewGuestModal<?php echo $guest['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detail Buku Tamu</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-borderless">
                                                    <tr>
                                                        <td><strong>Nama:</strong></td>
                                                        <td><?php echo htmlspecialchars($guest['name']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Email:</strong></td>
                                                        <td><?php echo htmlspecialchars($guest['email']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Telepon:</strong></td>
                                                        <td><?php echo htmlspecialchars($guest['phone']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Instansi:</strong></td>
                                                        <td><?php echo htmlspecialchars($guest['institution']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Pesan:</strong></td>
                                                        <td><?php echo nl2br(htmlspecialchars($guest['message'])); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Tanggal:</strong></td>
                                                        <td><?php echo format_date($guest['created_at']); ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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

        // Delete confirmation
        document.querySelectorAll('.btn-delete').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const name = this.dataset.name;
                if (confirm(`Apakah Anda yakin ingin menghapus data "${name}"?`)) {
                    window.location.href = this.href;
                }
            });
        });
    </script>
</body>
</html>
