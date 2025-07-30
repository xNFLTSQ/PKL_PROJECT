<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    <meta name="description" content="<?php echo SITE_DESCRIPTION; ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Header with Photo Slider -->
   <!-- Header dengan Slider -->
<header class="header-section">
  <div class="container-fluid p-0">
    <div id="headerSlider" class="carousel slide" data-bs-ride="carousel">
      
      <!-- Indicator Bulat -->
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#headerSlider" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#headerSlider" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#headerSlider" data-bs-slide-to="2"></button>
      </div>

      <!-- Slides -->
      <div class="carousel-inner">
        <!-- Slide 1 -->
        <div class="carousel-item active">
          <div class="fullscreen-slide position-relative">
            <img src="assets/foto/uptd-1.jpg" class="d-block w-100" alt="Gedung UPTD">
            <div class="carousel-caption d-flex flex-column justify-content-center align-items-center text-center">
              <h1 class="display-4 text-white mb-3">Selamat Datang</h1>
              <p class="lead text-white">Sistem Informasi Buku Tamu dan Dispensasi</p>
            </div>
          </div>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-item">
          <div class="fullscreen-slide position-relative">
            <img src="assets/foto/uptd.jpg" class="d-block w-100" alt="Pelayanan Terbaik">
            <div class="carousel-caption d-flex flex-column justify-content-center align-items-center text-center">
              <h1 class="display-4 text-white mb-3">Pelayanan Terbaik</h1>
              <p class="lead text-white">Melayani dengan Profesional dan Transparan</p>
            </div>
          </div>
        </div>

        <!-- Slide 3 -->
        <div class="carousel-item">
          <div class="fullscreen-slide position-relative">
            <img src="assets/foto/upptd-2.jpg" class="d-block w-100" alt="Inovasi Digital">
            <div class="carousel-caption d-flex flex-column justify-content-center align-items-center text-center">
              <h1 class="display-4 text-white mb-3">Inovasi Digital</h1>
              <p class="lead text-white">Transformasi Digital untuk Pelayanan Prima</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Tombol Prev -->
      <button class="carousel-control-prev" type="button" data-bs-target="#headerSlider" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Sebelumnya</span>
      </button>

      <!-- Tombol Next -->
      <button class="carousel-control-next" type="button" data-bs-target="#headerSlider" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Selanjutnya</span>
      </button>
    </div>
  </div>
</header>
        
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <i class="fas fa-building me-2"></i>
                    <?php echo SITE_NAME; ?>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">
                                <i class="fas fa-home me-1"></i>Beranda
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="guest_book.php">
                                <i class="fas fa-book me-1"></i>Buku Tamu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="dispensation.php">
                                <i class="fas fa-file-alt me-1"></i>Dispensasi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="admin/login.php">
                                <i class="fas fa-user-shield me-1"></i>Admin
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
