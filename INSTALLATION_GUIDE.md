# Inventory Sync System - Setup Instructions

## System Overview

This is a Laravel-based inventory synchronization system that integrates multiple databases:

- **PostgreSQL (Odoo)**: Main data source for products, stock, movements (read-only)
- **MySQL (Laravel)**: Authentication and user management
- **SQL Server**: Backup storage (data warehouse)

## Prerequisites

### Required Software

- PHP 8.2 or higher
- Composer
- MySQL Server
- PostgreSQL Server (with Odoo database)
- SQL Server

### Required Database Connections

1. **MySQL** - For Laravel authentication
    - Host: 127.0.0.1 (or your MySQL server)
    - Port: 3306
    - Database: `inventory_sync_db`
    - Username: `root` (or your MySQL user)
    - Password: (configure in .env)

2. **PostgreSQL** - For Odoo data
    - Host: 127.0.0.1 (or your PostgreSQL server)
    - Port: 5432
    - Database: `odoo_inventory_db`
    - Username: `openpg`
    - Password: `openpgpwd`

3. **SQL Server** - For backup storage
    - Host: 127.0.0.1 (or your SQL Server)
    - Port: 1433
    - Database: `backup_inventory_db`
    - Username: `sa`
    - Password: `Password123`

## Installation Steps

### Step 1: Clone/Setup the Project

```bash
cd d:\fadly\inventory-sync-system
```

### Step 2: Update Environment Variables

Edit `.env` file with your database credentials:

```env
# MySQL Configuration (Laravel Auth DB)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_sync_db
DB_USERNAME=root
DB_PASSWORD=your_mysql_password

# PostgreSQL Configuration (Odoo DB)
DB_ODOO_HOST=127.0.0.1
DB_ODOO_DATABASE=odoo_inventory_db
DB_ODOO_USERNAME=openpg
DB_ODOO_PASSWORD=openpgpwd

# SQL Server Configuration (Backup DB)
DB_SQLSRV_HOST=127.0.0.1
DB_SQLSRV_DATABASE=backup_inventory_db
DB_SQLSRV_USERNAME=sa
DB_SQLSRV_PASSWORD=Password123
```

### Step 3: Install Dependencies

```bash
composer install
npm install
npm run build
```

### Step 4: Create Database and Tables

```bash
# Create MySQL database if not exists
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS inventory_sync_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Run Laravel migrations (creates users table, backup logs, etc.)
php artisan migrate --force

# Seed the database with admin user
php artisan db:seed --class=UserSeeder
```

### Step 5: Start the Development Server

```bash
# Build front-end assets
npm run dev

# In another terminal, start the Laravel server
php artisan serve
```

The application will be available at: `http://localhost:8000`

## Login Credentials

After running the seeder, use these credentials to log in:

**Email:** admin@example.com
**Password:** admin123

## Features

### 1. Dashboard

- View total products, stock quantity, warehouses, and movements
- See recent movements and warehouse summary
- All data sourced from Odoo PostgreSQL database

### 2. Products

- Browse all products from Odoo
- View product details (ID, name, price, quantity)
- Display quantity on hand from stock_quant

### 3. Stock Warehouse

- View stock across multiple warehouses
- Filter by warehouse or search by product name/SKU
- Multi-warehouse support
- Export stock data to CSV

### 4. Stock by Location

- Analyze stock distribution by location
- View total quantity per location

### 5. Movements (Pergerakan Barang)

- Track stock movements between locations
- Filter by status (done, pending, cancelled)
- View movement history

### 6. Backup Data

- Backup products from Odoo to SQL Server
- Backup stock information to SQL Server data warehouse
- Track backup progress and status

### 7. Backup Logs

- View all backup history
- Filter by status
- See backup details (date, total data, message)

## Database Schema

### MySQL (Laravel)

```sql
-- Users table
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255),
    remember_token VARCHAR(100),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Backup logs
CREATE TABLE backup_logs (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    status VARCHAR(50),
    total_data INT,
    started_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    message TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### SQL Server (Backup Storage)

```sql
-- Backup products
CREATE TABLE backup_products (
    id INT PRIMARY KEY,
    product_id INT,
    name NVARCHAR(255),
    code NVARCHAR(100),
    price DECIMAL(10,2),
    created_at DATETIME
);

-- Backup stocks
CREATE TABLE backup_stocks (
    id INT PRIMARY KEY,
    product_id INT,
    product_name NVARCHAR(255),
    location_id INT,
    location_name NVARCHAR(255),
    warehouse_id INT,
    warehouse_name NVARCHAR(255),
    quantity DECIMAL(12,2),
    reserved_quantity DECIMAL(12,2),
    created_at DATETIME
);

-- Backup logs
CREATE TABLE backup_logs (
    id BIGINT PRIMARY KEY,
    status VARCHAR(50),
    total_data INT,
    started_at DATETIME NULL,
    completed_at DATETIME NULL,
    message NVARCHAR(MAX),
    created_at DATETIME,
    updated_at DATETIME
);
```

## API Routes

### Authentication

- `POST /login` - User login
- `POST /logout` - User logout

### Protected Routes (require authentication)

- `GET /dashboard` - Dashboard
- `GET /products` - Product list
- `GET /products/{id}` - Product details
- `GET /stock-warehouse` - Stock by warehouse
- `GET /stock/by-location` - Stock by location
- `GET /stock/export` - Export stock to CSV
- `GET /movement-items` - Stock movements
- `GET /movement/statistics` - Movement statistics
- `GET /backup-data` - Backup page
- `POST /backup-data/backup` - Execute backup
- `GET /backup-logs` - Backup logs list
- `GET /backup-logs/{id}` - Backup log details

## Multi-Database Configuration

The system uses Laravel's multi-database support:

1. **Default connection (MySQL)**: Used for Laravel authentication, users, and backup logs
2. **pgsql_odoo**: PostgreSQL connection for Odoo data (read-only)
3. **sqlsrv_backup**: SQL Server connection for backup storage

Each controller uses `DB::connection()` to query the appropriate database:

```php
// Query from Odoo
$products = DB::connection('pgsql_odoo')->select("SELECT ...");

// Query from SQL Server backup
$backups = DB::connection('sqlsrv_backup')->table('backup_logs')->get();
```

## Models

### Laravel Models (MySQL)

- `App\Models\User` - User model with authentication
- `App\Models\BackupLog` - Backup log tracking

### Odoo Models (PostgreSQL) - Read-Only

- `App\Models\Odoo\ProductTemplate` - Product templates
- `App\Models\Odoo\ProductProduct` - Products
- `App\Models\Odoo\StockQuant` - Stock quantities
- `App\Models\Odoo\StockLocation` - Warehouse locations
- `App\Models\Odoo\StockMove` - Stock movements
- `App\Models\Odoo\StockWarehouse` - Warehouses

## Troubleshooting

### Database Connection Errors

1. Verify all database servers are running
2. Check credentials in `.env` file
3. Ensure database names exist
4. Check firewall rules allow connections

### Authentication Issues

1. Ensure MySQL users table is created: `php artisan migrate`
2. Verify admin user exists: Check users table in MySQL
3. Check that password is bcrypt hashed

### Backup Failures

1. Verify SQL Server backup database exists
2. Check backup tables are created in SQL Server
3. Verify connection credentials for SQL Server
4. Check that Odoo PostgreSQL is accessible

### Missing Views

If you get "View not found" errors:

1. Verify all blade files exist in `resources/views/`
2. Check that AdminLTE assets are available in `public/asset/AdminLTE-3.2.0/`
3. Verify view names match route redirects

## Performance Optimization

### For Large Datasets

1. Add pagination to product and stock lists
2. Implement query caching for frequently accessed data
3. Add database indexes on frequently queried columns
4. Consider denormalizing backup tables for faster queries

### Database Indexes (PostgreSQL - Odoo)

```sql
CREATE INDEX idx_stock_quant_product ON stock_quant(product_id);
CREATE INDEX idx_stock_quant_location ON stock_quant(location_id);
CREATE INDEX idx_stock_move_state ON stock_move(state);
CREATE INDEX idx_product_template_name ON product_template(name);
```

## Security Notes

1. **Change default password** after first login
2. **Use environment variables** for sensitive data (never commit .env to git)
3. **Enable HTTPS** in production
4. **Implement rate limiting** on authentication endpoints
5. **Add CSRF protection** (already included in Laravel)
6. **Implement input validation** on all forms
7. **Sanitize data** before displaying to prevent XSS

## Support

For issues or questions:

1. Check the Laravel documentation: https://laravel.com/docs
2. Check AdminLTE documentation: https://adminlte.io/
3. Review error logs in `storage/logs/`

## Next Steps

1. Configure real database connections
2. Test all features with actual data
3. Customize AdminLTE theme colors if needed
4. Add additional reporting features
5. Implement automated backup scheduling
6. Add user roles and permissions
