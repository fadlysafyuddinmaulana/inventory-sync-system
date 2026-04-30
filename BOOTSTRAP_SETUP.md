# Bootstrap UI Remake - Dokumentasi

## Ringkasan

Project ini telah di-remake dengan menggunakan **Bootstrap 5.3** sebagai framework CSS. Semua halaman telah diperbarui dengan desain yang konsisten, modern, dan responsif menggunakan custom color palette dari project.

## File-file Penting

### 1. Layout Utama

- **`resources/views/layouts/app.blade.php`** - Layout master yang digunakan semua halaman
    - Sidebar navigation dengan Bootstrap classes
    - Top navigation bar
    - Alert & error display
    - User menu dropdown

### 2. CSS Theme

- **`resources/css/bootstrap-theme.css`** - Custom Bootstrap theme dengan color palette project
    - CSS Variables untuk colors, shadows, dan spacing
    - Custom component styles (stat-card, sidebar, card, buttons, table, dll)
    - Dark mode support
    - Responsive design

### 3. Reusable Components

Di folder `resources/views/components/`:

| File                   | Deskripsi                          | Usage                     |
| ---------------------- | ---------------------------------- | ------------------------- |
| `stat-card.blade.php`  | Card statistik dengan icon & warna | Dashboard, overview pages |
| `card.blade.php`       | Card wrapper generik               | Wrapper content sections  |
| `data-table.blade.php` | Table wrapper dengan header        | Daftar data               |
| `alert.blade.php`      | Alert messages                     | Status messages           |
| `button.blade.php`     | Reusable button                    | Actions                   |
| `badge.blade.php`      | Status badges                      | Status indicators         |
| `loading.blade.php`    | Loading spinner                    | Async operations          |

### 4. Bootstrap Pages

- **`resources/views/dashboard.blade.php`** - Dashboard dengan stat cards & activity
- **`resources/views/products-bootstrap.blade.php`** - Daftar produk dengan filter
- **`resources/views/warehouse-stock-bootstrap.blade.php`** - Warehouse management
- **`resources/views/stock-movements-bootstrap.blade.php`** - Stock movements tracking
- **`resources/views/backup-data-bootstrap.blade.php`** - Backup management
- **`resources/views/backup-logs-bootstrap.blade.php`** - Backup logs

## Color Palette

```css
Primary Color:     #030213 (Dark Navy)
Secondary Color:   #f3f3f5 (Light Gray)
Success Color:     #10b981 (Green)
Info Color:        #3b82f6 (Blue)
Warning Color:     #f59e0b (Orange)
Danger Color:      #ef4444 (Red)
Purple:            #8b5cf6 (Purple)
Background:        #ffffff (White)
Sidebar:           #030213 (Dark Navy)
```

## Cara Menggunakan Components

### Stat Card

```blade
@include('components.stat-card', [
    'title' => 'Total Produk',
    'value' => '1,234',
    'subtitle' => '↑ 12% dari bulan lalu',
    'subtitleColor' => 'success',
    'color' => 'blue',
    'icon' => '<i class="bi bi-box"></i>'
])
```

**Props:**

- `title` - Judul card
- `value` - Nilai/angka yang ditampilkan
- `subtitle` - Subtitle opsional
- `subtitleColor` - Warna subtitle (success, warning, danger, dll)
- `color` - Warna accent (blue, green, orange, red, purple)
- `icon` - HTML icon (gunakan Bootstrap Icons)

### Badge

```blade
@include('components.badge', ['variant' => 'success', 'icon' => 'check-circle'])
    Status Aktif
@endinclude
```

**Props:**

- `variant` - Varian warna (primary, success, info, warning, danger)
- `icon` - Bootstrap icon class (opsional)
- `class` - Custom CSS classes (opsional)

### Alert

```blade
@include('components.alert', [
    'type' => 'success',
    'icon' => 'check-circle',
    'title' => 'Sukses!',
    'message' => 'Data berhasil disimpan'
])
```

**Props:**

- `type` - Tipe alert (success, info, warning, danger)
- `icon` - Bootstrap icon class
- `title` - Judul alert (opsional)
- `message` - Pesan alert (opsional, atau gunakan slot)

### Card

```blade
@include('components.card', [
    'title' => 'Informasi Produk',
    'icon' => 'box',
    'showHeader' => true,
    'showFooter' => true,
    'footer' => 'Footer content'
])
    Card content here
@endinclude
```

## Setup & Installation

### 1. Install Dependencies

```bash
npm install
composer install
```

### 2. Link Bootstrap CSS di Layout

Bootstrap CSS sudah di-link di `app.blade.php`:

```html
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
/>
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css"
    rel="stylesheet"
/>
<link href="{{ asset('css/bootstrap-theme.css') }}" rel="stylesheet" />
```

### 3. Bootstrap JS di Footer

```html
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
```

## Bootstrap Icons

Project menggunakan **Bootstrap Icons v1.11.0** untuk semua icon. Beberapa icon yang sering digunakan:

- `bi-box` - Produk
- `bi-building` - Warehouse
- `bi-arrow-left-right` - Pergerakan
- `bi-cloud-check` - Backup sukses
- `bi-exclamation-triangle` - Warning
- `bi-check-circle` - Sukses
- `bi-x-circle` - Error
- `bi-download` - Download
- `bi-search` - Search
- `bi-funnel` - Filter

Lihat [Bootstrap Icons](https://icons.getbootstrap.com/) untuk daftar lengkap.

## CSS Classes yang Sering Digunakan

### Layout

- `d-flex` - Display flex
- `justify-content-between` - Space between
- `align-items-center` - Center vertically
- `gap-2`, `gap-3` - Gap between items

### Spacing

- `mb-3`, `mt-3` - Margin bottom/top
- `px-4`, `py-3` - Padding horizontal/vertical
- `ms-auto` - Margin left auto (push right)

### Colors

- `text-primary`, `text-success`, `text-danger` - Text colors
- `bg-primary`, `bg-success` - Background colors
- `border-primary` - Border colors

### Components

- `btn btn-primary` - Button
- `form-control` - Input
- `form-label` - Label
- `badge bg-success` - Badge
- `alert alert-success` - Alert
- `card` - Card container
- `table table-hover` - Table

## Tips & Tricks

### 1. Responsive Grid

```blade
<div class="row">
    <div class="col-md-6 col-lg-3">Responsive column</div>
</div>
```

### 2. Modal

```blade
<button data-bs-toggle="modal" data-bs-target="#myModal">Open Modal</button>

<div class="modal fade" id="myModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">Header</div>
            <div class="modal-body">Content</div>
            <div class="modal-footer">Footer</div>
        </div>
    </div>
</div>
```

### 3. Offcanvas Panel

```blade
<button data-bs-toggle="offcanvas" data-bs-target="#filterPanel">Filter</button>

<div class="offcanvas offcanvas-end" id="filterPanel">
    <div class="offcanvas-header">Header</div>
    <div class="offcanvas-body">Content</div>
</div>
```

### 4. Table Responsive

```blade
<div class="table-responsive">
    <table class="table table-hover">
        ...
    </table>
</div>
```

## Migrasi dari View Lama

Jika ada view lama yang belum di-update, ikuti pattern berikut:

1. Gunakan layout `@extends('layouts.app')`
2. Set `@section('page-title', 'Page Title')`
3. Gunakan Bootstrap grid: `<div class="row"><div class="col-lg-6">...</div></div>`
4. Gunakan card components untuk sections
5. Gunakan table-responsive untuk tables
6. Gunakan stat-card untuk statistics

## Dark Mode Support

Color theme sudah support dark mode. Untuk mengaktifkan, tambahkan class `dark` ke element:

```html
<html class="dark"></html>
```

CSS akan otomatis menyesuaikan colors berdasarkan `@media (prefers-color-scheme: dark)`.

## Package.json Dependencies

```json
{
    "dependencies": {
        "bootstrap": "^5.3.0",
        "bootstrap-icons": "^1.11.0"
    }
}
```

## Next Steps

1. ✅ Install dependencies: `npm install && composer install`
2. ✅ Setup database migrations
3. ✅ Create model relationships
4. ✅ Implement controller methods untuk setiap page
5. ⏳ Add JavaScript functionality untuk filter, search, dll
6. ⏳ Implement data pagination
7. ⏳ Add charts dengan Chart.js atau library lainnya
8. ⏳ Setup API endpoints untuk dynamic content

## Troubleshooting

### Bootstrap styles tidak muncul

- Pastikan Bootstrap CSS di-link di layout: `<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">`
- Clear browser cache: Ctrl+Shift+Del

### Icons tidak tampil

- Pastikan Bootstrap Icons CSS di-link: `<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">`
- Gunakan format yang benar: `<i class="bi bi-icon-name"></i>`

### Modal tidak berfungsi

- Pastikan Bootstrap JS di-load di footer: `<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>`

## Resources

- [Bootstrap 5 Documentation](https://getbootstrap.com/docs/5.3/)
- [Bootstrap Icons](https://icons.getbootstrap.com/)
- [Laravel Blade Templates](https://laravel.com/docs/blade)

---

**Last Updated**: 2024-04-28
**Version**: 1.0.0
