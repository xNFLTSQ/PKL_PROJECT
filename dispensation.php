<?php
require_once 'includes/config.example.php';
require_once 'includes/functions.php';

$page_title = 'Dispensasi';
$success_message = '';
$error_message = '';

// Handle form submission
if ($_POST) {
    // Verify CSRF token
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error_message = 'Token keamanan tidak valid. Silakan coba lagi.';
    } else {
        // Sanitize input
        $name = sanitize_input($_POST['name'] ?? '');
        $nim_nip = sanitize_input($_POST['nim_nip'] ?? '');
        $department = sanitize_input($_POST['department'] ?? '');
        $reason = sanitize_input($_POST['reason'] ?? '');
        $start_date = sanitize_input($_POST['start_date'] ?? '');
        $end_date = sanitize_input($_POST['end_date'] ?? '');
        $proof_photo = $_POST['proof_photo'] ?? '';
        
        // Validation
        $errors = [];
        
        if (empty($name)) {
            $errors[] = 'Nama wajib diisi';
        }
        
        if (empty($department)) {
            $errors[] = 'Jurusan/Bagian wajib diisi';
        }
        
        if (empty($reason)) {
            $errors[] = 'Alasan dispensasi wajib diisi';
        }
        
        if (strlen($reason) < 20) {
            $errors[] = 'Alasan dispensasi minimal 20 karakter';
        }
        
        if (empty($start_date)) {
            $errors[] = 'Tanggal mulai wajib diisi';
        }
        
        if (empty($end_date)) {
            $errors[] = 'Tanggal selesai wajib diisi';
        }
        
        if (!empty($start_date) && !empty($end_date)) {
            if (strtotime($start_date) < strtotime(date('Y-m-d'))) {
                $errors[] = 'Tanggal mulai tidak boleh kurang dari hari ini';
            }
            
            if (strtotime($end_date) < strtotime($start_date)) {
                $errors[] = 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai';
            }
        }
        
        if (empty($errors)) {
            try {
                // Save photo if provided
                $photo_filename = null;
                if (!empty($proof_photo)) {
                    // Remove data:image/jpeg;base64, prefix
                    $photo_data = preg_replace('/^data:image\/[a-z]+;base64,/', '', $proof_photo);
                    $photo_data = base64_decode($photo_data);
                    
                    // Generate unique filename
                    $photo_filename = 'proof_' . time() . '_' . uniqid() . '.jpg';
                    $photo_path = 'assets/images/' . $photo_filename;
                    
                    // Save photo to file
                    if (file_put_contents($photo_path, $photo_data) === false) {
                        $errors[] = 'Gagal menyimpan foto bukti';
                    }
                }
                
                if (empty($errors)) {
                    $stmt = $pdo->prepare("INSERT INTO dispensation (name, nim_nip, department, reason, start_date, end_date, proof_photo) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$name, $nim_nip, $department, $reason, $start_date, $end_date, $photo_filename]);
                    
                    $success_message = 'Permohonan dispensasi Anda telah berhasil diajukan. Status dapat dipantau melalui admin.';
                    
                    // Clear form data
                    $_POST = [];
                }
            } catch (PDOException $e) {
                $error_message = 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.';
            }
        }
        
        if (!empty($errors)) {
            $error_message = implode('<br>', $errors);
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<main class="main-content">
    <div class="container">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                        <li class="breadcrumb-item active">Dispensasi</li>
                    </ol>
                </nav>
                <h2 class="display-6 mb-3">
                    <i class="fas fa-file-alt text-primary me-3"></i>
                    Permohonan Dispensasi
                </h2>
                <p class="lead text-muted">Ajukan permohonan dispensasi untuk keperluan resmi Anda</p>
            </div>
        </div>

        <!-- Alert Messages -->
        <div id="alertContainer">
            <?php if ($success_message): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo $success_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php echo $error_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Dispensation Form -->
                <div class="form-container">
                    <div class="text-center mb-4">
                        <i class="fas fa-paper-plane text-primary" style="font-size: 3rem;"></i>
                        <h4 class="mt-3">Form Permohonan Dispensasi</h4>
                        <p class="text-muted">Mohon isi data dengan lengkap dan benar</p>
                    </div>

                    <form id="dispensationForm" method="POST" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="name" 
                                       name="name" 
                                       value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                                       required 
                                       maxlength="100"
                                       placeholder="Masukkan nama lengkap">
                                <div class="invalid-feedback">
                                    Nama lengkap wajib diisi
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nim_nip" class="form-label">NIM/NIP</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nim_nip" 
                                       name="nim_nip" 
                                       value="<?php echo htmlspecialchars($_POST['nim_nip'] ?? ''); ?>"
                                       maxlength="50"
                                       placeholder="Nomor Induk Mahasiswa/Pegawai">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="department" class="form-label">
                                Jurusan/Bagian <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="department" 
                                   name="department" 
                                   value="<?php echo htmlspecialchars($_POST['department'] ?? ''); ?>"
                                   required 
                                   maxlength="100"
                                   placeholder="Nama jurusan atau bagian">
                            <div class="invalid-feedback">
                                Jurusan/Bagian wajib diisi
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">
                                Alasan Dispensasi <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" 
                                      id="reason" 
                                      name="reason" 
                                      rows="4" 
                                      required 
                                      minlength="20"
                                      maxlength="1000"
                                      placeholder="Jelaskan alasan mengapa Anda memerlukan dispensasi..."><?php echo htmlspecialchars($_POST['reason'] ?? ''); ?></textarea>
                            <div class="invalid-feedback">
                                Alasan dispensasi wajib diisi minimal 20 karakter
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">
                                    Tanggal Mulai <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control" 
                                       id="start_date" 
                                       name="start_date" 
                                       value="<?php echo htmlspecialchars($_POST['start_date'] ?? ''); ?>"
                                       required 
                                       min="<?php echo date('Y-m-d'); ?>">
                                <div class="invalid-feedback">
                                    Tanggal mulai wajib diisi
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">
                                    Tanggal Selesai <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control" 
                                       id="end_date" 
                                       name="end_date" 
                                       value="<?php echo htmlspecialchars($_POST['end_date'] ?? ''); ?>"
                                       required 
                                       min="<?php echo date('Y-m-d'); ?>">
                                <div class="invalid-feedback">
                                    Tanggal selesai wajib diisi
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="proof_photo" class="form-label">
                                Foto Bukti Sakit (Opsional)
                            </label>
                            <div class="card">
                                <div class="card-body text-center">
                                    <div id="cameraContainer" style="display: none;">
                                        <video id="camera" width="100%" height="300" autoplay></video>
                                        <canvas id="canvas" style="display: none;"></canvas>
                                    </div>
                                    <div id="photoPreview" style="display: none;">
                                        <img id="capturedPhoto" src="" alt="Foto Bukti" style="max-width: 100%; height: 300px; object-fit: cover;">
                                    </div>
                                    <div id="cameraButtons" class="mt-3">
                                        <button type="button" id="startCamera" class="btn btn-primary me-2">
                                            <i class="fas fa-camera me-2"></i>Buka Kamera
                                        </button>
                                        <button type="button" id="capturePhoto" class="btn btn-success me-2" style="display: none;">
                                            <i class="fas fa-camera-retro me-2"></i>Ambil Foto
                                        </button>
                                        <button type="button" id="retakePhoto" class="btn btn-warning" style="display: none;">
                                            <i class="fas fa-redo me-2"></i>Foto Ulang
                                        </button>
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Ambil foto yang jelas sebagai bukti kondisi sakit Anda
                                    </small>
                                </div>
                            </div>
                            <input type="hidden" id="proof_photo" name="proof_photo">
                            <div class="invalid-feedback">
                                Foto bukti sakit wajib diambil
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Catatan:</strong> Permohonan dispensasi akan diproses dalam 1-3 hari kerja. 
                            Status permohonan dapat dipantau melalui panel admin.
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="button" class="btn btn-outline-secondary me-md-2" onclick="resetForm('dispensationForm')">
                                <i class="fas fa-undo me-2"></i>Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Ajukan Dispensasi
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Requirements Card -->
                <div class="card border-warning mt-4">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Persyaratan Dispensasi
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Mengisi formulir dengan lengkap dan benar
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Alasan dispensasi harus jelas dan dapat dipertanggungjawabkan
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Tanggal dispensasi tidak boleh mundur dari hari ini
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Permohonan diajukan minimal H-1 sebelum tanggal yang diminta
                            </li>
                            <li class="mb-0">
                                <i class="fas fa-check text-success me-2"></i>
                                Menunggu persetujuan dari pihak yang berwenang
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Dispensation Status -->
        <div class="row mt-5">
            <div class="col-12">
                <h4 class="mb-4">
                    <i class="fas fa-list-alt text-primary me-2"></i>
                    Status Dispensasi Terbaru
                </h4>
                
                <?php
                // Get recent dispensation entries (last 5)
                try {
                    $stmt = $pdo->prepare("SELECT name, department, reason, start_date, end_date, status, created_at FROM dispensation ORDER BY created_at DESC LIMIT ?");
                    $stmt->execute([5]);
                    $recent_dispensations = $stmt->fetchAll();
                } catch (PDOException $e) {
                    $recent_dispensations = [];
                    error_log("Database error: " . $e->getMessage());
                }
                ?>
                
                <?php if ($recent_dispensations): ?>
                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Jurusan/Bagian</th>
                                        <th>Periode</th>
                                        <th>Status</th>
                                        <th>Tanggal Ajuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_dispensations as $dispensation): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($dispensation['name']); ?></strong>
                                            </td>
                                            <td><?php echo htmlspecialchars($dispensation['department']); ?></td>
                                            <td>
                                                <small>
                                                    <?php echo date('d/m/Y', strtotime($dispensation['start_date'])); ?> - 
                                                    <?php echo date('d/m/Y', strtotime($dispensation['end_date'])); ?>
                                                </small>
                                            </td>
                                            <td><?php echo get_status_badge($dispensation['status']); ?></td>
                                            <td>
                                                <small class="text-muted">
                                                    <?php echo format_date($dispensation['created_at']); ?>
                                                </small>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-file-alt text-muted" style="font-size: 4rem;"></i>
                        <h5 class="mt-3 text-muted">Belum ada permohonan dispensasi</h5>
                        <p class="text-muted">Jadilah yang pertama mengajukan dispensasi!</p>
                    </div>
                <?php endif; ?>
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
