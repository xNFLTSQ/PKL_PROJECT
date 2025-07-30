<?php
require_once '../includes/config.example.php';
require_once '../includes/functions.php';

// Check if admin is logged in
require_admin();

$page_title = 'Kelola Dispensasi';

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
if (isset($_GET['delete']) && $_GET['delete'] > 0) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM dispensation WHERE id = ?");
        $stmt->execute([$id]);
        $success_message = 'Data dispensasi berhasil dihapus';
    } catch (PDOException $e) {
        $error_message = 'Gagal menghapus data';
    }
}

// Get all dispensation data
$stmt = $pdo->query("SELECT * FROM dispensation ORDER BY created_at DESC");
$dispensations = $stmt->fetchAll();

// Get statistics
$total_dispensations = count($dispensations);
$dispensations_today = 0;
$pending_dispensations = 0;
$approved_dispensations = 0;

foreach ($dispensations as $dispensation) {
    if (date('Y-m-d', strtotime($dispensation['created_at'])) === date('Y-m-d')) {
        $dispensations_today++;
    }
    if ($dispensation['status'] === 'pending') {
        $pending_dispensations++;
    }
    if ($dispensation['status'] === 'approved') {
        $approved_dispensations++;
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
                      <h2 class="mb-0 d-flex align-items-center">
            <img src="../assets/foto/logo.png" alt="Logo TEKKOM INFODIK" style="height: 40px; margin-right: 10px;">
            <?= $page_title ?>
        </h2>
                    <p class="mb-0 opacity-75">Selamat datang, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="dashboard.php" class="btn btn-outline-light me-2">
                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                    <a href="guest_book.php" class="btn btn-outline-light me-2">
                        <i class="fas fa-book me-1"></i>Buku Tamu
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
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-number text-info"><?php echo $total_dispensations; ?></div>
                    <div class="stats-label">Total Dispensasi</div>
                    <small class="text-muted">
                        <i class="fas fa-calendar-day me-1"></i>
                        Semua data dispensasi
                    </small>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-number text-success"><?php echo $dispensations_today; ?></div>
                    <div class="stats-label">Dispensasi Hari Ini</div>
                    <small class="text-muted">
                        <i class="fas fa-calendar-day me-1"></i>
                        Data hari ini: <?php echo date('d/m/Y'); ?>
                    </small>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-number text-warning"><?php echo $pending_dispensations; ?></div>
                    <div class="stats-label">Dispensasi Pending</div>
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i>
                        Menunggu persetujuan
                    </small>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-number text-primary"><?php echo $approved_dispensations; ?></div>
                    <div class="stats-label">Dispensasi Disetujui</div>
                    <small class="text-muted">
                        <i class="fas fa-check me-1"></i>
                        Sudah disetujui
                    </small>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Data Dispensasi</h4>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success btn-sm" onclick="exportToExcel('dispensationsTable', 'Data_Dispensasi')">
                            <i class="fas fa-file-excel me-1"></i>Export Excel
                        </button>
                        <input type="text" id="searchDispensations" class="form-control" placeholder="Cari dispensasi..." style="width: 250px;">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="dispensationsTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIM/NIP</th>
                                <th>Jurusan/Bagian</th>
                                <th>Periode</th>
                                <th>Status</th>
                                <th>Tanggal Ajuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dispensations as $index => $dispensation): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><strong><?php echo htmlspecialchars($dispensation['name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($dispensation['nim_nip']); ?></td>
                                    <td><?php echo htmlspecialchars($dispensation['department']); ?></td>
                                    <td>
                                        <small>
                                            <?php echo date('d/m/Y', strtotime($dispensation['start_date'])); ?> - 
                                            <?php echo date('d/m/Y', strtotime($dispensation['end_date'])); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm status-select" 
                                                data-id="<?php echo $dispensation['id']; ?>" 
                                                data-type="dispensation">
                                            <option value="pending" <?php echo $dispensation['status'] === 'pending' ? 'selected' : ''; ?>>Menunggu</option>
                                            <option value="approved" <?php echo $dispensation['status'] === 'approved' ? 'selected' : ''; ?>>Disetujui</option>
                                            <option value="rejected" <?php echo $dispensation['status'] === 'rejected' ? 'selected' : ''; ?>>Ditolak</option>
                                        </select>
                                    </td>
                                    <td>
                                        <small><?php echo format_date($dispensation['created_at']); ?></small>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewDispensationModal<?php echo $dispensation['id']; ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <?php if ($dispensation['proof_photo']): ?>
                                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#viewPhotoModal<?php echo $dispensation['id']; ?>">
                                                <i class="fas fa-camera"></i>
                                            </button>
                                        <?php endif; ?>
                                        <a href="?delete=<?php echo $dispensation['id']; ?>" 
                                           class="btn btn-sm btn-danger btn-delete" 
                                           data-name="<?php echo htmlspecialchars($dispensation['name']); ?>">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                                <!-- View Dispensation Modal -->
                                <div class="modal fade" id="viewDispensationModal<?php echo $dispensation['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detail Dispensasi</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-borderless">
                                                    <tr>
                                                        <td><strong>Nama:</strong></td>
                                                        <td><?php echo htmlspecialchars($dispensation['name']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>NIM/NIP:</strong></td>
                                                        <td><?php echo htmlspecialchars($dispensation['nim_nip']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Jurusan/Bagian:</strong></td>
                                                        <td><?php echo htmlspecialchars($dispensation['department']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Alasan:</strong></td>
                                                        <td><?php echo nl2br(htmlspecialchars($dispensation['reason'])); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Tanggal Mulai:</strong></td>
                                                        <td><?php echo date('d/m/Y', strtotime($dispensation['start_date'])); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Tanggal Selesai:</strong></td>
                                                        <td><?php echo date('d/m/Y', strtotime($dispensation['end_date'])); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Status:</strong></td>
                                                        <td><?php echo get_status_badge($dispensation['status']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Tanggal Ajuan:</strong></td>
                                                        <td><?php echo format_date($dispensation['created_at']); ?></td>
                                                    </tr>
                                                    <?php if ($dispensation['proof_photo']): ?>
                                                    <tr>
                                                        <td><strong>Foto Bukti:</strong></td>
                                                        <td>
                                                            <img src="../assets/images/<?php echo htmlspecialchars($dispensation['proof_photo']); ?>" 
                                                                 alt="Foto Bukti" 
                                                                 style="max-width: 200px; height: auto; border-radius: 5px;">
                                                        </td>
                                                    </tr>
                                                    <?php endif; ?>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- View Photo Modal -->
                                <?php if ($dispensation['proof_photo']): ?>
                                <div class="modal fade" id="viewPhotoModal<?php echo $dispensation['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Foto Bukti Sakit - <?php echo htmlspecialchars($dispensation['name']); ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="../assets/images/<?php echo htmlspecialchars($dispensation['proof_photo']); ?>" 
                                                     alt="Foto Bukti Sakit" 
                                                     style="max-width: 100%; height: auto; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                                <div class="mt-3">
                                                    <small class="text-muted">
                                                        Foto diambil pada: <?php echo format_date($dispensation['created_at']); ?>
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <a href="../assets/images/<?php echo htmlspecialchars($dispensation['proof_photo']); ?>" 
                                                   download="bukti_sakit_<?php echo htmlspecialchars($dispensation['name']); ?>.jpg" 
                                                   class="btn btn-primary">
                                                    <i class="fas fa-download me-2"></i>Download Foto
                                                </a>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
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
                
                fetch('dispensation.php', {
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
