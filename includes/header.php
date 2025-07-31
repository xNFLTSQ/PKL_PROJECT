<?php
/**
 * Header template for Government Guest Book & Dispensation System
 * 
 * Contains HTML head, navigation, and header slider
 */

class HeaderHelper {
    private static $slides = [
        [
            'image' => 'assets/foto/uptd-1.jpg',
            'alt' => 'Gedung UPTD',
            'title' => 'Selamat Datang',
            'subtitle' => 'Sistem Informasi Buku Tamu dan Dispensasi'
        ],
        [
            'image' => 'assets/foto/uptd.jpg',
            'alt' => 'Pelayanan Terbaik',
            'title' => 'Pelayanan Terbaik',
            'subtitle' => 'Melayani dengan Profesional dan Transparan'
        ],
        [
            'image' => 'assets/foto/upptd-2.jpg',
            'alt' => 'Inovasi Digital',
            'title' => 'Inovasi Digital',
            'subtitle' => 'Transformasi Digital untuk Pelayanan Prima'
        ]
    ];
    
    private static $navigation = [
        ['url' => 'index.php', 'icon' => 'fas fa-home', 'text' => 'Beranda'],
        ['url' => 'guest_book.php', 'icon' => 'fas fa-book', 'text' => 'Buku Tamu'],
        ['url' => 'dispensation.php', 'icon' => 'fas fa-file-alt', 'text' => 'Dispensasi'],
        ['url' => 'admin/login.php', 'icon' => 'fas fa-user-shield', 'text' => 'Admin']
    ];
    
    public static function renderSlides() {
        $output = '';
        foreach (self::$slides as $index => $slide) {
            $activeClass = $index === 0 ? 'active' : '';
            $output .= sprintf(
                '<div class="carousel-item %s">
                    <div class="fullscreen-slide position-relative">
                        <img src="%s" class="d-block w-100" alt="%s">
                        <div class="carousel-caption d-flex flex-column justify-content-center align-items-center text-center">
                            <h1 class="display-4 text-white mb-3">%s</h1>
                            <p class="lead text-white">%s</p>
                        </div>
                    </div>
                </div>',
                $activeClass,
                htmlspecialchars($slide['image']),
                htmlspecialchars($slide['alt']),
                htmlspecialchars($slide['title']),
                htmlspecialchars($slide['subtitle'])
            );
        }
        return $output;
    }
    
    public static function renderIndicators() {
        $output = '';
        foreach (self::$slides as $index => $slide) {
            $activeClass = $index === 0 ? 'active' : '';
            $output .= sprintf(
                '<button type="button" data-bs-target="#headerSlider" data-bs-slide-to="%d" class="%s"></button>',
                $index,
                $activeClass
            );
        }
        return $output;
    }
    
    public static function renderNavigation() {
        $output = '';
        foreach (self::$navigation as $nav) {
            $output .= sprintf(
                '<li class="nav-item">
                    <a class="nav-link" href="%s">
                        <i class="%s me-1"></i>%s
                    </a>
                </li>',
                htmlspecialchars($nav['url']),
                htmlspecialchars($nav['icon']),
                htmlspecialchars($nav['text'])
            );
        }
        return $output;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    <meta name="description" content="<?php echo htmlspecialchars(SITE_DESCRIPTION); ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Header with Photo Slider -->
    <header class="header-section">
        <div class="container-fluid p-0">
            <div id="headerSlider" class="carousel slide" data-bs-ride="carousel">
                
                <!-- Carousel Indicators -->
                <div class="carousel-indicators">
                    <?php echo HeaderHelper::renderIndicators(); ?>
                </div>

                <!-- Carousel Slides -->
                <div class="carousel-inner">
                    <?php echo HeaderHelper::renderSlides(); ?>
                </div>

                <!-- Carousel Controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#headerSlider" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Sebelumnya</span>
                </button>

                <button class="carousel-control-next" type="button" data-bs-target="#headerSlider" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Selanjutnya</span>
                </button>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <i class="fas fa-building me-2"></i>
                    <?php echo htmlspecialchars(SITE_NAME); ?>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <?php echo HeaderHelper::renderNavigation(); ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
