# Database Setup & Configuration Guide

## Quick Setup Commands

```bash
# 1. Install dependencies
composer install
npm install

# 2. Generate application key (if not already done)
php artisan key:generate

# 3. Create MySQL database
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS inventory_sync_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 4. Run migrations
php artisan migrate:refresh --force

# 5. Seed admin user
php artisan db:seed --class=UserSeeder

# 6. Build assets
npm run build

# 7. Start development server
php artisan serve
```

Access at: `http://localhost:8000`

## Environment Variables

Create or update `.env` with:

```env
# Application
APP_NAME=InventorySyncSystem
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# MySQL (Laravel Auth Database)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_sync_db
DB_USERNAME=root
DB_PASSWORD=

# PostgreSQL (Odoo Data Source)
DB_ODOO_HOST=127.0.0.1
DB_ODOO_DATABASE=odoo_inventory_db
DB_ODOO_USERNAME=openpg
DB_ODOO_PASSWORD=openpgpwd

# SQL Server (Backup Storage)
DB_SQLSRV_HOST=127.0.0.1
DB_SQLSRV_PORT=1433
DB_SQLSRV_DATABASE=backup_inventory_db
DB_SQLSRV_USERNAME=sa
DB_SQLSRV_PASSWORD=Password123
```

## Database Tables Structure

### users (MySQL)

```sql
CREATE TABLE users (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name varchar(255) NOT NULL,
    email varchar(255) NOT NULL UNIQUE,
    email_verified_at timestamp NULL,
    password varchar(255) NOT NULL,
    remember_token varchar(100),
    created_at timestamp,
    updated_at timestamp,
    INDEX idx_email (email)
);
```

### backup_logs (MySQL)

```sql
CREATE TABLE backup_logs (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    status varchar(50) NOT NULL DEFAULT 'pending',
    total_data int unsigned DEFAULT 0,
    started_at timestamp NULL,
    completed_at timestamp NULL,
    message longtext,
    created_at timestamp,
    updated_at timestamp,
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
);
```

### Odoo PostgreSQL Tables (Read-Only)

```sql
-- product_template
SELECT id, name, list_price FROM product_template;

-- product_product
SELECT id, product_tmpl_id, default_code, name FROM product_product;

-- stock_quant
SELECT id, product_id, location_id, quantity, reserved_quantity FROM stock_quant;

-- stock_location
SELECT id, name, usage, warehouse_id FROM stock_location;

-- stock_move
SELECT id, product_id, location_id, location_dest_id, product_uom_qty AS quantity_done, state, create_date FROM stock_move;

-- stock_warehouse
SELECT id, name, code FROM stock_warehouse;
```

### SQL Server Tables (Backup Storage)

```sql
-- backup_products
CREATE TABLE backup_products (
    id int PRIMARY KEY,
    product_id int,
    name nvarchar(255),
    code nvarchar(100),
    price decimal(10,2),
    created_at datetime,
    INDEX idx_product_id (product_id)
);

-- backup_stocks
CREATE TABLE backup_stocks (
    id int PRIMARY KEY,
    product_id int,
    product_name nvarchar(255),
    location_id int,
    location_name nvarchar(255),
    warehouse_id int,
    warehouse_name nvarchar(255),
    quantity decimal(12,2),
    reserved_quantity decimal(12,2),
    created_at datetime,
    INDEX idx_warehouse (warehouse_id),
    INDEX idx_location (location_id)
);

-- backup_logs
CREATE TABLE backup_logs (
    id bigint PRIMARY KEY,
    status varchar(50),
    total_data int,
    started_at datetime,
    completed_at datetime,
    message nvarchar(max),
    created_at datetime,
    updated_at datetime,
    INDEX idx_status (status)
);
```

## Testing Database Connections

### Test MySQL Connection

```php
php artisan tinker
>>> DB::connection('mysql')->select('SHOW DATABASES;');
```

### Test PostgreSQL Connection

```php
php artisan tinker
>>> DB::connection('pgsql_odoo')->select('SELECT version();');
```

### Test SQL Server Connection

```php
php artisan tinker
>>> DB::connection('sqlsrv_backup')->select('SELECT @@VERSION;');
```

## Seeding Admin User

Run the UserSeeder:

```bash
php artisan db:seed --class=UserSeeder
```

This creates:

- Email: admin@example.com
- Password: admin123 (hashed with bcrypt)

## Backup Tables Setup

The backup tables should be created in SQL Server with the following schema:

```sql
-- Run these commands in SQL Server
CREATE DATABASE backup_inventory_db;

USE backup_inventory_db;

CREATE TABLE backup_products (
    id int PRIMARY KEY,
    product_id int,
    name nvarchar(255),
    code nvarchar(100),
    price decimal(10,2),
    created_at datetime DEFAULT GETDATE()
);

CREATE TABLE backup_stocks (
    id int PRIMARY KEY,
    product_id int,
    product_name nvarchar(255),
    location_id int,
    location_name nvarchar(255),
    warehouse_id int,
    warehouse_name nvarchar(255),
    quantity decimal(12,2),
    reserved_quantity decimal(12,2),
    created_at datetime DEFAULT GETDATE()
);

CREATE TABLE backup_logs (
    id bigint PRIMARY KEY,
    status varchar(50),
    total_data int,
    started_at datetime,
    completed_at datetime,
    message nvarchar(max),
    created_at datetime,
    updated_at datetime
);
```

## Indexing for Performance

### MySQL Indexes

```sql
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_backup_logs_status ON backup_logs(status);
CREATE INDEX idx_backup_logs_created_at ON backup_logs(created_at);
```

### PostgreSQL Indexes (Optional - Odoo)

```sql
CREATE INDEX idx_product_template_name ON product_template(name);
CREATE INDEX idx_stock_quant_product ON stock_quant(product_id);
CREATE INDEX idx_stock_quant_location ON stock_quant(location_id);
CREATE INDEX idx_stock_move_state ON stock_move(state);
CREATE INDEX idx_stock_location_warehouse ON stock_location(warehouse_id);
```

## Connection Troubleshooting

### MySQL Connection Issues

```bash
# Test connection
mysql -h 127.0.0.1 -u root -p -e "SELECT 1"

# Check if MySQL is running
netstat -an | grep 3306
```

### PostgreSQL Connection Issues

```bash
# Test connection
psql -h 127.0.0.1 -U openpg -d odoo_inventory_db -c "SELECT 1"

# Check if PostgreSQL is running
netstat -an | grep 5432
```

### SQL Server Connection Issues

```bash
# Test connection using sqlcmd
sqlcmd -S 127.0.0.1 -U sa -P Password123 -Q "SELECT 1"

# Check if SQL Server is running
netstat -an | grep 1433
```

## Backup Automation

### Windows Scheduled Task

```batch
cd d:\fadly\inventory-sync-system
php artisan backup:execute
```

### Linux Cron Job

```bash
0 2 * * * cd /path/to/inventory-sync-system && php artisan backup:execute
```

## Maintenance

### Clear Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### View Logs

```bash
tail -f storage/logs/laravel.log
```

### Database Backup

```bash
# MySQL backup
mysqldump -u root -p inventory_sync_db > backup.sql

# PostgreSQL backup
pg_dump -h 127.0.0.1 -U openpg odoo_inventory_db > backup.sql
```
