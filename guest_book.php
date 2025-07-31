<?php
require_once 'includes/config.example.php';
require_once 'includes/functions.php';

class GuestBookController {
    private $pdo;
    private $success_message = '';
    private $error_message = '';
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function handleFormSubmission() {
        if (!$_POST) return;
        
        if (!$this->validateCSRFToken()) {
            $this->error_message = 'Token keamanan tidak valid. Silakan coba lagi.';
            return;
        }
        
        $formData = $this->sanitizeFormData();
        $errors = $this->validateFormData($formData);
        
        if (empty($errors)) {
            $this->saveGuestBookEntry($formData);
        } else {
            $this->error_message = implode('<br>', $errors);
        }
    }
    
    private function validateCSRFToken() {
        return verify_csrf_token($_POST['csrf_token'] ?? '');
    }
    
    private function sanitizeFormData() {
        return [
            'name' => sanitize_input($_POST['name'] ?? ''),
            'email' => sanitize_input($_POST['email'] ?? ''),
            'phone' => sanitize_input($_POST['phone'] ?? ''),
            'institution' => sanitize_input($_POST['institution'] ?? ''),
            'message' => sanitize_input($_POST['message'] ?? '')
        ];
    }
    
    private function validateFormData($data) {
        $errors = [];
        
        if (empty($data['name'])) {
            $errors[] = 'Nama wajib diisi';
        }
        
        if (!empty($data['email']) && !is_valid_email($data['email'])) {
            $errors[] = 'Format email tidak valid';
        }
        
        if (empty($data['message'])) {
            $errors[] = 'Pesan wajib diisi';
        }
        
        if (strlen($data['message']) < 10) {
            $errors[] = 'Pesan minimal 10 karakter';
        }
        
        return $errors;
    }
    
    private function saveGuestBookEntry($data) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO guest_book (name, email, phone, institution, message) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$data['name'], $data['email'], $data['phone'], $data['institution'], $data['message']]);
            
            $this->success_message = 'Terima kasih! Buku tamu Anda telah berhasil disimpan.';
            $_POST = []; // Clear form data
        } catch (PDOException $e) {
            $this->error_message = 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.';
            error_log("Guest book save error: " . $e->getMessage());
        }
    }
    
    public function getRecentEntries($limit = 5) {
        try {
            $stmt = $this->pdo->prepare("SELECT name, institution, message, created_at FROM guest_book ORDER BY created_at DESC LIMIT ?");
            $stmt->execute([$limit]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Get recent entries error: " . $e->getMessage());
            return [];
        }
    }
    
    public function getSuccessMessage() { return $this->success_message; }
    public function getErrorMessage() { return $this->error_message; }
}

$page_title = 'Buku Tamu';
$guestBook = new GuestBookController($pdo);
$guestBook->handleFormSubmission();
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
                        <li class="breadcrumb-item active">Buku Tamu</li>
                    </ol>
                </nav>
                <h2 class="display-6 mb-3">
                    <i class="fas fa-book text-primary me-3"></i>
                    Buku Tamu
                </h2>
                <p class="lead text-muted">Silakan isi buku tamu untuk memberikan kesan dan pesan Anda</p>
            </div>
        </div>

        <!-- Alert Messages -->
        <div id="alertContainer">
            <?php if ($guestBook->getSuccessMessage()): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo $guestBook->getSuccessMessage(); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($guestBook->getErrorMessage()): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php echo $guestBook->getErrorMessage(); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Guest Book Form -->
                <div class="form-container">
                    <div class="text-center mb-4">
                        <i class="fas fa-edit text-primary" style="font-size: 3rem;"></i>
                        <h4 class="mt-3">Form Buku Tamu</h4>
                        <p class="text-muted">Mohon isi data dengan lengkap dan benar</p>
                    </div>

                    <form id="guestBookForm" method="POST" class="needs-validation" novalidate>
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
                                <label for="email" class="form-label">Email</label>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email" 
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                       maxlength="100"
                                       placeholder="contoh@email.com">
                                <div class="invalid-feedback">
                                    Format email tidak valid
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="tel" 
                                       class="form-control" 
                                       id="phone" 
                                       name="phone" 
                                       value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                                       maxlength="20"
                                       placeholder="08xxxxxxxxxx">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="institution" class="form-label">Instansi/Organisasi</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="institution" 
                                       name="institution" 
                                       value="<?php echo htmlspecialchars($_POST['institution'] ?? ''); ?>"
                                       maxlength="100"
                                       placeholder="Nama instansi atau organisasi">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="message" class="form-label">
                                Pesan/Kesan <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" 
                                      id="message" 
                                      name="message" 
                                      rows="5" 
                                      required 
                                      minlength="10"
                                      maxlength="1000"
                                      placeholder="Tuliskan kesan, pesan, atau saran Anda..."><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                            <div class="invalid-feedback">
                                Pesan wajib diisi minimal 10 karakter
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="button" class="btn btn-outline-secondary me-md-2" onclick="resetForm('guestBookForm')">
                                <i class="fas fa-undo me-2"></i>Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Kirim Buku Tamu
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Information Card -->
                <div class="card border-info mt-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Informasi
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Data yang bertanda <span class="text-danger">*</span> wajib diisi
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Pesan minimal 10 karakter dan maksimal 1000 karakter
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Data Anda akan dijaga kerahasiaannya
                            </li>
                            <li class="mb-0">
                                <i class="fas fa-check text-success me-2"></i>
                                Terima kasih atas partisipasi Anda
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Guest Book Entries -->
        <div class="row mt-5">
            <div class="col-12">
                <h4 class="mb-4">
                    <i class="fas fa-comments text-primary me-2"></i>
                    Buku Tamu Terbaru
                </h4>
                
                <?php
                // Get recent guest book entries (last 5)
                $recent_entries = $guestBook->getRecentEntries();
                ?>
                
                <?php if ($recent_entries): ?>
                    <div class="row">
                        <?php foreach ($recent_entries as $entry): ?>
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="flex-shrink-0">
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1"><?php echo htmlspecialchars($entry['name']); ?></h6>
                                                <?php if ($entry['institution']): ?>
                                                    <small class="text-muted"><?php echo htmlspecialchars($entry['institution']); ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <p class="card-text"><?php echo nl2br(htmlspecialchars(substr($entry['message'], 0, 150))); ?><?php echo strlen($entry['message']) > 150 ? '...' : ''; ?></p>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            <?php echo format_date($entry['created_at']); ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-book-open text-muted" style="font-size: 4rem;"></i>
                        <h5 class="mt-3 text-muted">Belum ada buku tamu</h5>
                        <p class="text-muted">Jadilah yang pertama mengisi buku tamu!</p>
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
