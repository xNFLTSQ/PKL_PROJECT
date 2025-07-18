# 📤 Panduan Upload ke GitHub

Panduan lengkap untuk upload project PKL ke GitHub repository: https://github.com/xNFLTSQ/PKL_PROJECT.git

## 🔧 Persiapan Sebelum Upload

### 1. Install Git (jika belum ada)
```bash
# Windows: Download dari https://git-scm.com/
# macOS: brew install git
# Linux: sudo apt install git
```

### 2. Konfigurasi Git (pertama kali)
```bash
git config --global user.name "Your Name"
git config --global user.email "your.email@example.com"
```

## 📁 Persiapan File

### File yang TIDAK akan di-upload (sudah ada di .gitignore):
- `includes/config.php` (konfigurasi database sensitif)
- `debug_admin.php` (file debug)
- `assets/images/*.jpg` (foto upload user)

### File yang AKAN di-upload:
- Semua file PHP utama
- File CSS, JS, HTML
- `database.sql` (schema database)
- `includes/config.example.php` (template konfigurasi)
- `README.md` (dokumentasi)

## 🚀 Langkah Upload ke GitHub

### 1. Buka Terminal/Command Prompt
```bash
# Masuk ke folder project
cd d:/PKL_PROJECCT2
```

### 2. Inisialisasi Git Repository
```bash
git init
```

### 3. Tambahkan Remote Repository
```bash
git remote add origin https://github.com/xNFLTSQ/PKL_PROJECT.git
```

### 4. Tambahkan Semua File
```bash
git add .
```

### 5. Commit Pertama
```bash
git commit -m "Initial commit: Sistem Buku Tamu & Dispensasi"
```

### 6. Push ke GitHub
```bash
git push -u origin main
```

## 🔐 Jika Diminta Login GitHub

### Opsi 1: Personal Access Token (Recommended)
1. Buka GitHub.com → Settings → Developer settings → Personal access tokens
2. Generate new token dengan scope `repo`
3. Copy token dan gunakan sebagai password

### Opsi 2: GitHub CLI
```bash
# Install GitHub CLI
gh auth login
```

## 📋 Perintah Git Lengkap (Copy-Paste)

```bash
# 1. Masuk ke folder project
cd d:/PKL_PROJECCT2

# 2. Inisialisasi git
git init

# 3. Tambahkan remote
git remote add origin https://github.com/xNFLTSQ/PKL_PROJECT.git

# 4. Tambahkan semua file
git add .

# 5. Commit
git commit -m "Initial commit: Sistem Buku Tamu & Dispensasi

Features:
- Guest book form with validation
- Dispensation request with camera photo
- Separate admin panels for guest book and dispensation
- Excel export functionality
- Real-time status updates
- Photo proof viewing and download
- Responsive design
- Security features (CSRF, password hashing)"

# 6. Push ke GitHub
git push -u origin main
```

## 🔄 Update Project di Masa Depan

### Setelah membuat perubahan:
```bash
# 1. Tambahkan file yang berubah
git add .

# 2. Commit dengan pesan
git commit -m "Update: deskripsi perubahan"

# 3. Push ke GitHub
git push
```

## 📝 Template Commit Messages

```bash
# Fitur baru
git commit -m "Add: fitur export PDF"

# Perbaikan bug
git commit -m "Fix: masalah upload foto"

# Update UI
git commit -m "Update: tampilan dashboard admin"

# Keamanan
git commit -m "Security: perbaikan validasi form"
```

## 🚨 Troubleshooting

### Error: "Repository not found"
```bash
# Pastikan URL repository benar
git remote -v
git remote set-url origin https://github.com/xNFLTSQ/PKL_PROJECT.git
```

### Error: "Permission denied"
```bash
# Gunakan Personal Access Token sebagai password
# Atau setup SSH key
```

### Error: "Branch main doesn't exist"
```bash
# Buat branch main
git branch -M main
git push -u origin main
```

## ✅ Verifikasi Upload Berhasil

1. Buka https://github.com/xNFLTSQ/PKL_PROJECT
2. Pastikan semua file sudah terupload
3. Cek README.md tampil dengan baik
4. Pastikan file sensitif tidak terupload

## 📖 Setelah Upload

### Update README di GitHub:
1. Edit file README.md di GitHub
2. Tambahkan screenshot jika perlu
3. Update link demo jika ada

### Setup GitHub Pages (opsional):
1. Settings → Pages
2. Source: Deploy from branch
3. Branch: main
4. Akses di: https://xNFLTSQ.github.io/PKL_PROJECT

## 🎯 Checklist Upload

- [ ] Git sudah terinstall
- [ ] Repository GitHub sudah dibuat
- [ ] File sensitif sudah di .gitignore
- [ ] README.md sudah dibuat
- [ ] Git init dan remote add
- [ ] Git add dan commit
- [ ] Git push berhasil
- [ ] Verifikasi di GitHub
- [ ] Update dokumentasi jika perlu

---

**Selamat! Project PKL Anda sudah berhasil di-upload ke GitHub! 🎉**
