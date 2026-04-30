# Sistem Inventory Sync - Setup & Deployment Guide

## 🏗️ Arsitektur Sistem

```
┌─────────────────────────────────────────────────────────────────┐
│                    LARAVEL APPLICATION                          │
│                                                                 │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │              Authentication & Session                   │  │
│  │  - Login dengan Username/Password (MySQL)              │  │
│  │  - Session management                                  │  │
│  └──────────────────────────────────────────────────────────┘  │
│                                                                 │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │             Dashboard & Monitoring                      │  │
│  │  - Analytics dari Odoo (Read-Only)                     │  │
│  │  - Real-time statistics                                │  │
│  └──────────────────────────────────────────────────────────┘  │
│                                                                 │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │           Multi-Source Data Integration                │  │
│  │  - Produk (Read-Only dari Odoo)                        │  │
│  │  - Stok per Warehouse (Read-Only)                      │  │
│  │  - Pergerakan Barang (Read-Only)                       │  │
│  │  - Customers & Sales (Read-Only)                       │  │
│  └──────────────────────────────────────────────────────────┘  │
│                                                                 │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │         Backup & Data Warehouse                        │  │
│  │  - Backup automation                                   │  │
│  │  - Backup history & logs                               │  │
│  └──────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
       ↓                    ↓                         ↓
   ┌────────────────┐  ┌─────────────┐      ┌──────────────────┐
   │  PostgreSQL    │  │   MySQL     │      │   SQL Server     │
   │  (Odoo)        │  │ (Auth & App)│      │   (Backup DB)    │
   │                │  │             │      │                  │
   │ - Products     │  │ - Users     │      │ - Backup Logs    │
   │ - Stock Quant  │  │ - Sessions  │      │ - Products       │
   │ - Warehouses   │  │ - Cache     │      │ - Stocks         │
   │ - Movements    │  │             │      │                  │
   │ - Sales        │  │             │      │                  │
   └────────────────┘  └─────────────┘      └──────────────────┘
   Read-Only Access    Read-Write         Write-Only (Backup)
```

## 📋 Prerequisites

- **PHP**: 8.2 atau lebih tinggi
- **Composer**: v2.0 atau lebih tinggi
- **Database**:
    - PostgreSQL (untuk Odoo)
    - MySQL (untuk Laravel app)
    - SQL Server (untuk backup)
- **Driver**:
    - `pdo_pgsql` (PostgreSQL)
    - `pdo_mysql` (MySQL)
    - `pdo_sqlsrv` (SQL Server) - via `pecl install pdo_sqlsrv`

## 🚀 Installation Steps

### 1. Setup Environment & Dependencies

```bash
# Clone repository
git clone <repository-url>
cd inventory-sync-system

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 2. Database Configuration

Edit file `.env` dan konfigurasi koneksi database:

```env
# MySQL (Laravel Default - Authentication)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_sync_db
DB_USERNAME=root
DB_PASSWORD=

# PostgreSQL (Odoo - Read-Only)
DB_ODOO_HOST=127.0.0.1
DB_ODOO_PORT=5432
DB_ODOO_DATABASE=odoo_inventory_db
DB_ODOO_USERNAME=openpg
DB_ODOO_PASSWORD=openpgpwd
DB_ODOO_SSLMODE=prefer

# SQL Server (Backup - Write-Only)
DB_SQLSRV_HOST=127.0.0.1
DB_SQLSRV_PORT=1433
DB_SQLSRV_DATABASE=backup_inventory_db
DB_SQLSRV_USERNAME=sa
DB_SQLSRV_PASSWORD=Password123
```

### 3. Create MySQL Databases & Tables

```bash
# Login ke MySQL
mysql -u root -p

# Create database
CREATE DATABASE inventory_sync_db;
USE inventory_sync_db;
```

Jalankan migrations:

```bash
# Migration untuk users table (MySQL)
php artisan migrate

# Seed data
php artisan db:seed --class=UserSeeder
```

### 4. Create SQL Server Backup Tables

Jalankan script SQL berikut di SQL Server:

```sql
-- Create Backup Logs Table
CREATE TABLE backup_logs (
    id BIGINT PRIMARY KEY IDENTITY(1,1),
    product_count INT DEFAULT 0,
    stock_count INT DEFAULT 0,
    warehouse_count INT DEFAULT 0,
    backup_size VARCHAR(50) NULL,
    status VARCHAR(20) DEFAULT 'pending',
    message NVARCHAR(MAX) NULL,
    started_at DATETIME2 NULL,
    completed_at DATETIME2 NULL,
    created_at DATETIME2 DEFAULT GETDATE(),
    updated_at DATETIME2 DEFAULT GETDATE()
);

-- Create Backup Products Table
CREATE TABLE backup_products (
    id BIGINT PRIMARY KEY IDENTITY(1,1),
    product_id BIGINT,
    name NVARCHAR(255),
    default_code VARCHAR(50) NULL,
    list_price DECIMAL(12, 2) NULL,
    created_at DATETIME2 DEFAULT GETDATE(),
    updated_at DATETIME2 DEFAULT GETDATE()
);

-- Create Backup Stocks Table
CREATE TABLE backup_stocks (
    id BIGINT PRIMARY KEY IDENTITY(1,1),
    product_id BIGINT,
    product_name NVARCHAR(255),
    location_id BIGINT,
    location_name NVARCHAR(255),
    warehouse_id BIGINT NULL,
    warehouse_name NVARCHAR(255) NULL,
    quantity DECIMAL(12, 2) DEFAULT 0,
    reserved_quantity DECIMAL(12, 2) DEFAULT 0,
    created_at DATETIME2 DEFAULT GETDATE(),
    updated_at DATETIME2 DEFAULT GETDATE()
);
```

### 5. Configure Odoo Database Connection

Pastikan koneksi PostgreSQL ke Odoo sudah benar. Odoo memiliki struktur database standar dengan tabel-tabel berikut yang akan digunakan:

- `product_template` - Data produk utama
- `product_product` - Varian produk
- `stock_warehouse` - Daftar warehouse
- `stock_location` - Lokasi penyimpanan
- `stock_quant` - Stok aktual per lokasi
- `stock_move` - Riwayat pergerakan stok
- `stock_picking` - Dokumen pengiriman
- `res_partner` - Data pelanggan
- `sale_order` - Pesanan penjualan
- `sale_order_line` - Item dalam pesanan

## 📝 Default User Credentials

```
Username: admin
Password: admin123
```

> ⚠️ Ubah password pada production!

## 🔐 User Management

### Menambah User Baru

```bash
php artisan tinker
```

```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'username' => 'newuser',
    'password' => Hash::make('password123'),
]);
```

### Reset Password

```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::where('username', 'admin')->first();
$user->update(['password' => Hash::make('newpassword')]);
```

## 🔄 Backup Workflow

### Manual Backup

1. Login ke dashboard
2. Pilih menu "Backup Data"
3. Klik tombol "Jalankan Backup Sekarang"
4. Sistem akan:
    - Baca data dari Odoo (PostgreSQL)
    - Simpan ke SQL Server backup tables
    - Catat log backup
    - Tampilkan hasil

### Automatic Backup

Konfigurasi di halaman Backup Data atau via command:

```bash
# Run backup via command
php artisan backup:execute

# Schedule backup (via Laravel Scheduler)
# Edit app/Console/Kernel.php
```

### Backup Schedule Configuration

Edit `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Backup setiap hari jam 2 pagi
    $schedule->command('backup:execute')
        ->dailyAt('02:00')
        ->withoutOverlapping();
}
```

Jalankan scheduler:

```bash
# Development
php artisan schedule:work

# Production - Setup cron job
* * * * * cd /path/to/app && php artisan schedule:run >> /dev/null 2>&1
```

## 📊 Menu & Fitur

### 1. Dashboard

- Total Produk (dari Odoo)
- Total Stok Keseluruhan
- Jumlah Warehouse
- Total Pergerakan Hari Ini
- Grafik Pergerakan Stok (7 hari terakhir)
- Aktivitas Terbaru

### 2. Produk

- Daftar produk dari Odoo
- Pencarian berdasarkan nama/SKU
- Informasi: ID, Nama, SKU, Harga
- Pagination
- Lihat detail produk

### 3. Stok Warehouse

- Multi-warehouse view
- Filter berdasarkan warehouse
- Informasi: Produk, Lokasi, Qty, Qty Reserved
- Export ke CSV
- View berdasarkan lokasi

### 4. Pergerakan Barang

- Riwayat stock movements
- Filter: Tipe (Masuk/Keluar/Internal), Status
- Informasi: Tanggal, Produk, Qty, Dari/Ke, Status
- Statistik pergerakan

### 5. Backup Data

- Tombol backup manual
- Riwayat backup dengan status
- Backup size estimation
- Download backup
- Delete backup

### 6. Log Backup

- Timeline log backup
- Filter berdasarkan status & tanggal
- Detail error messages
- Durasi backup

## 🔍 API Endpoints

### Authentication

- `POST /login` - Login user
- `POST /logout` - Logout user

### Protected Routes

- `GET /dashboard` - Dashboard
- `GET /products` - Daftar produk
- `GET /stock-warehouse` - Stok warehouse
- `GET /movement-items` - Pergerakan barang
- `GET /backup-data` - Halaman backup
- `POST /backup-data/backup` - Execute backup
- `GET /backup-logs` - Log backup

## 🛠️ Troubleshooting

### Masalah: MySQL Connection Refused

```
SQLSTATE[HY000] [2002] No connection could be made
```

**Solusi:**

- Pastikan MySQL service berjalan: `mysql.server start` (macOS) atau check Services (Windows)
- Verifikasi host/port di `.env`
- Buat database: `CREATE DATABASE inventory_sync_db;`

### Masalah: PostgreSQL Connection Failed

```
could not connect to server: No such file or directory
```

**Solusi:**

- Pastikan PostgreSQL service berjalan
- Verifikasi credentials Odoo di `.env`
- Test koneksi dengan psql CLI

### Masalah: SQL Server Connection Error

```
SQLSTATE[IMSSP]: This extension requires the Microsoft ODBC Driver for SQL Server to be installed
```

**Solusi:**

```bash
# Install SQL Server ODBC Driver
# Windows: Download dari https://www.microsoft.com/en-us/download/details.aspx?id=56567
# macOS: brew tap microsoft/mssql-release && brew install msodbcsql17
# Linux: https://docs.microsoft.com/sql/connect/odbc/linux-mac/installing-the-microsoft-odbc-driver-for-sql-server
```

### Masalah: Backup gagal

- Periksa free space SQL Server
- Verifikasi permissions user SQL Server
- Check log backup di halaman Log Backup
- Lihat detail error di storage/logs/laravel.log

## 📈 Performance Optimization

### Caching

```php
// Cache product list (1 jam)
$products = Cache::remember('products', 3600, function () {
    return ProductTemplate::all();
});
```

### Database Indexing

Di PostgreSQL Odoo:

```sql
CREATE INDEX idx_product_template_name ON product_template(name);
CREATE INDEX idx_stock_quant_product_id ON stock_quant(product_id);
CREATE INDEX idx_stock_quant_location_id ON stock_quant(location_id);
CREATE INDEX idx_stock_move_create_date ON stock_move(create_date);
```

### Query Optimization

- Gunakan eager loading: `with(['relations'])`
- Limit data dengan pagination
- Gunakan select() untuk memilih kolom tertentu

## 🔒 Security Best Practices

1. **Environment Variables**
    - Jangan commit `.env` file
    - Gunakan `.env.example` sebagai template

2. **Database Credentials**
    - Gunakan strong passwords
    - Buat dedicated database users dengan minimal privileges
    - SQL Server: User hanya untuk backup (read-write limited tables)
    - PostgreSQL: User hanya untuk read operations

3. **Access Control**
    - Semua route protected dengan middleware `auth`
    - Implement role-based access control untuk production

4. **CORS & CSRF**
    - CSRF protection aktif untuk semua forms
    - Configure CORS jika ada API access dari domain lain

## 📱 Mobile Responsive

Sistem sudah responsive dengan Tailwind CSS:

- Desktop (1280px+)
- Tablet (768px - 1279px)
- Mobile (< 768px)

## 🌙 Dark Mode

Sistem support dark mode otomatis berdasarkan preferensi sistem operasi.

## 📞 Support & Documentation

- Laravel Documentation: https://laravel.com/docs
- Odoo Documentation: https://www.odoo.com/documentation/
- Database Documentation:
    - PostgreSQL: https://www.postgresql.org/docs/
    - MySQL: https://dev.mysql.com/doc/
    - SQL Server: https://docs.microsoft.com/sql/

## 📄 License

MIT License

---

**Version**: 1.0.0
**Last Updated**: April 29, 2026
