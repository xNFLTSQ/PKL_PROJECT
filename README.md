# Sistem Buku Tamu & Dispensasi Pemerintahan

Sistem informasi berbasis web untuk mengelola buku tamu dan permohonan dispensasi di lingkungan pemerintahan. Dibangun dengan PHP, MySQL, Bootstrap, dan JavaScript.

## 🚀 Fitur Utama

### 📖 Buku Tamu
- Form pengisian buku tamu dengan validasi lengkap
- Data pengunjung tersimpan di database
- Tampilan data buku tamu untuk admin

### 📄 Dispensasi
- Form permohonan dispensasi dengan validasi
- **Fitur kamera real-time** untuk foto bukti sakit
- Update status dispensasi (pending/approved/rejected)
- Foto bukti dapat dilihat dan didownload admin

### 👨‍💼 Admin Panel
- **Dashboard terpisah** untuk buku tamu dan dispensasi
- Login admin dengan password terenkripsi
- **Export data ke Excel** (.xls/.xlsx)
- Search dan filter data
- Statistik lengkap
- CRUD operations (Create, Read, Update, Delete)

## 🛠️ Teknologi

- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, Bootstrap 5.1.3
- **JavaScript**: Vanilla JS + Camera API
- **Icons**: Font Awesome 6.0
- **Security**: Password hashing, CSRF protection, SQL injection prevention

## 📋 Persyaratan Sistem

- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau MariaDB 10.3+
- Web server (Apache/Nginx) atau PHP built-in server
- Browser modern dengan dukungan Camera API

## 🔧 Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/xNFLTSQ/PKL_PROJECT.git
cd PKL_PROJECT
```

### 2. Setup Database
1. Buat database MySQL baru
2. Import file `database.sql` atau jalankan setup otomatis
3. Edit konfigurasi database di `includes/config.php`

### 3. Setup Database Otomatis
Akses `http://localhost/PKL_PROJECT/setup_database.php` untuk setup otomatis database dan admin.

### 4. Jalankan Aplikasi
```bash
# Menggunakan PHP built-in server
php -S localhost:8000

# Atau deploy ke web server (Apache/Nginx)
```

## 🔐 Login Admin

**Default Admin:**
- Username: `admin`
- Password: `admin123`

**URL Admin:** `http://localhost:8000/admin/login.php`

## 📁 Struktur Project

```
PKL_PROJECT/
├── admin/                  # Panel admin
│   ├── dashboard.php      # Dashboard utama
│   ├── guest_book.php     # Kelola buku tamu
│   ├── dispensation.php   # Kelola dispensasi
│   ├── login.php          # Login admin
│   └── logout.php         # Logout admin
├── assets/                # Asset statis
│   ├── css/style.css      # Styling utama
│   ├── js/script.js       # JavaScript
│   └── images/            # Upload foto
├── includes/              # File PHP include
│   ├── config.php         # Konfigurasi database
│   ├── functions.php      # Helper functions
│   └── header.php         # Header template
├── index.php              # Halaman utama
├── guest_book.php         # Form buku tamu
├── dispensation.php       # Form dispensasi
├── setup_database.php     # Setup database otomatis
└── database.sql           # Schema database
```

## 🎯 Cara Penggunaan

### Untuk Pengunjung:
1. Akses halaman utama
2. Pilih "Buku Tamu" atau "Dispensasi"
3. Isi form dengan lengkap
4. Submit data

### Untuk Admin:
1. Login di `/admin/login.php`
2. Dashboard menampilkan statistik
3. Kelola data di halaman terpisah:
   - **Buku Tamu**: Lihat, hapus, export data
   - **Dispensasi**: Update status, lihat foto, export data

## 📊 Fitur Admin

### Dashboard
- Statistik real-time
- Quick access ke halaman kelola
- Recent activity

### Kelola Buku Tamu
- Tabel data lengkap
- Search dan filter
- Export Excel
- View detail dan delete

### Kelola Dispensasi
- Update status (pending/approved/rejected)
- **Lihat foto bukti** dengan modal
- Download foto bukti
- Export Excel
- Search dan filter

## 🔒 Keamanan

- Password admin di-hash dengan `password_hash()`
- CSRF protection pada semua form
- Input sanitization dan validation
- SQL injection prevention dengan prepared statements
- Session management yang aman

## 📱 Responsive Design

- Mobile-friendly interface
- Touch-friendly buttons
- Camera API berfungsi di mobile
- Bootstrap responsive grid

## 🚀 Deployment

### Localhost
```bash
php -S localhost:8000
```

### Production Server
1. Upload semua file ke web server
2. Setup database dan konfigurasi
3. Jalankan `setup_database.php` sekali
4. Hapus file `setup_database.php` dan `debug_admin.php`

## 🤝 Kontribusi

1. Fork repository
2. Buat branch fitur (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📝 License

Distributed under the MIT License. See `LICENSE` for more information.

## 📞 Kontak

Project Link: [https://github.com/xNFLTSQ/PKL_PROJECT](https://github.com/xNFLTSQ/PKL_PROJECT)

---

**Dikembangkan untuk PKL Project - Sistem Informasi Pemerintahan**
