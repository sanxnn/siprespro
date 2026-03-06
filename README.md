# SIPRESPRO

Sistem Presensi Profesional berbasis Laravel 11 + TailwindCSS. Dibangun untuk kebutuhan manajemen presensi yang modern, cepat, dan aman.

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php)
![License](https://img.shields.io/badge/license-MIT-blue?style=flat-square)

## 🔗 Link Penting

- **Demo Live**: [https://welcomed-redbird-vital.ngrok-free.app](https://welcomed-redbird-vital.ngrok-free.app)
- **Repository**: [https://github.com/sanxnn/siprespro](https://github.com/sanxnn/siprespro)

## 🛠 Tech Stack

| Kategori | Teknologi |
|----------|-----------|
| **Backend** | Laravel 11, PHP 8.2+ |
| **Frontend** | Blade, TailwindCSS, Alpine.js |
| **Library** | Chart.js, Micromodal.js, Font Awesome |
| **Database** | MySQL (Railway/Local) |
| **Mailer** | SMTP Gmail |

## 🚀 Instalasi & Setup

Ikuti langkah berikut untuk menjalankan project di lingkungan lokal:

### 1. Clone Repository
```bash
git clone https://github.com/sanxnn/siprespro.git
cd siprespro
```
### 2. Install Dependencies
```bash
composer install
npm install && npm run build
```
### 3. Konfigurasi Environment
```bash
cp .env.example .env
php artisan key:generate
```
### 4. Migration & Seeding
```bash
php artisan migrate --seed
```
### 5. Jalankan Server
```bash
php artisan serve
```