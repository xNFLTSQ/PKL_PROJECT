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
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Header with Photo Slider -->
    <header class="header-section">
        <div class="container-fluid p-0">
            <!-- Photo Slider -->
            <div id="headerSlider" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#headerSlider" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#headerSlider" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#headerSlider" data-bs-slide-to="2"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="slide-bg slide-1">
                            <div class="slide-overlay">
                                <div class="container">
                                    <div class="row justify-content-center text-center">
                                        <div class="col-lg-8">
                                            <h1 class="display-4 text-white mb-3">Selamat Datang</h1>
                                            <p class="lead text-white">Sistem Informasi Buku Tamu dan Dispensasi</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="slide-bg slide-2">
                            <div class="slide-overlay">
                                <div class="container">
                                    <div class="row justify-content-center text-center">
                                        <div class="col-lg-8">
                                            <h1 class="display-4 text-white mb-3">Pelayanan Terbaik</h1>
                                            <p class="lead text-white">Melayani dengan Profesional dan Transparan</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="slide-bg slide-3">
                            <div class="slide-overlay">
                                <div class="container">
                                    <div class="row justify-content-center text-center">
                                        <div class="col-lg-8">
                                            <h1 class="display-4 text-white mb-3">Inovasi Digital</h1>
                                            <p class="lead text-white">Transformasi Digital untuk Pelayanan Prima</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#headerSlider" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#headerSlider" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>
        
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
