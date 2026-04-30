# Quick Start Guide - Bootstrap UI

## 🚀 Memulai

### 1. Install Dependencies

```bash
# Install npm packages
npm install

# Install composer packages
composer install
```

### 2. Link Asset

Bootstrap sudah di-link secara otomatis di `resources/views/layouts/app.blade.php`.

Jika perlu menambah CSS custom, tambahkan di `resources/css/bootstrap-theme.css`.

### 3. Jalankan Development Server

```bash
npm run dev
```

## 📁 Struktur File

```
resources/
├── views/
│   ├── layouts/
│   │   └── app.blade.php (Main Layout)
│   ├── components/
│   │   ├── stat-card.blade.php
│   │   ├── card.blade.php
│   │   ├── alert.blade.php
│   │   ├── badge.blade.php
│   │   ├── button.blade.php
│   │   ├── data-table.blade.php
│   │   └── loading.blade.php
│   ├── dashboard.blade.php
│   ├── products-bootstrap.blade.php
│   ├── warehouse-stock-bootstrap.blade.php
│   ├── stock-movements-bootstrap.blade.php
│   ├── backup-data-bootstrap.blade.php
│   └── backup-logs-bootstrap.blade.php
└── css/
    └── bootstrap-theme.css (Custom Theme)
```

## 🎨 Color Reference

| Color   | Hex       | Usage            |
| ------- | --------- | ---------------- |
| Primary | `#030213` | Buttons, Headers |
| Success | `#10b981` | Positive status  |
| Info    | `#3b82f6` | Information      |
| Warning | `#f59e0b` | Caution          |
| Danger  | `#ef4444` | Errors           |

## 💡 Common Components

### Stat Card

Untuk menampilkan statistik dengan icon dan warna:

```blade
@include('components.stat-card', [
    'title' => 'Total Produk',
    'value' => '1,234',
    'color' => 'blue',
    'icon' => '<i class="bi bi-box"></i>'
])
```

### Alert Message

Untuk menampilkan pesan status:

```blade
@include('components.alert', [
    'type' => 'success',
    'icon' => 'check-circle',
    'message' => 'Operation successful!'
])
```

### Badge

Untuk menampilkan status label:

```blade
@include('components.badge', ['variant' => 'success'])
    Active
@endinclude
```

## 📊 Sidebar Navigation

Sidebar otomatis highlight menu item berdasarkan route saat ini. Update navigation di `resources/views/layouts/app.blade.php`:

```blade
<a href="{{ route('dashboard') }}"
   class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
    <i class="bi bi-graph-up"></i>
    <span>Dashboard</span>
</a>
```

## 🔄 Responsive Design

Bootstrap grid system:

```blade
<div class="row">
    <div class="col-md-6 col-lg-3">Column 1</div>
    <div class="col-md-6 col-lg-3">Column 2</div>
</div>
```

Breakpoints:

- `col-` : Extra small (< 576px)
- `col-sm-` : Small (≥ 576px)
- `col-md-` : Medium (≥ 768px)
- `col-lg-` : Large (≥ 992px)
- `col-xl-` : Extra large (≥ 1200px)

## 🎯 Pages Updated

- ✅ Dashboard
- ✅ Products
- ✅ Warehouse Stock
- ✅ Stock Movements
- ✅ Backup Data
- ✅ Backup Logs
- ✅ Main Layout

## 📝 Usage Tips

### 1. Use Bootstrap Classes

Instead of custom CSS, use Bootstrap classes:

```blade
<!-- Good ✅ -->
<div class="d-flex justify-content-between align-items-center">

<!-- Avoid ❌ -->
<div style="display: flex; justify-content: space-between;">
```

### 2. Icons from Bootstrap Icons

```blade
<!-- Use bootstrap-icons -->
<i class="bi bi-search"></i>
<i class="bi bi-check-circle"></i>

<!-- Not fontawesome -->
<i class="fas fa-search"></i>
```

### 3. Consistent Spacing

```blade
<!-- Use consistent margin/padding -->
<div class="mb-3">Item 1</div>
<div class="mb-3">Item 2</div>

<!-- Or use gap for flex -->
<div class="d-flex gap-3">
    <div>Item 1</div>
    <div>Item 2</div>
</div>
```

## 🐛 Debugging

### Check Bootstrap is loaded

Open browser DevTools (F12) → Elements → Check CSS files are loaded

### Check Icons display

Make sure Bootstrap Icons CSS is loaded:

```html
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css"
/>
```

### Inspect element

Use browser DevTools to inspect element and check applied classes

## 📚 Resources

- [Bootstrap 5 Docs](https://getbootstrap.com/docs/5.3/)
- [Bootstrap Icons](https://icons.getbootstrap.com/)
- [Bootstrap Components](https://getbootstrap.com/docs/5.3/components/alerts/)

## 🎓 Learning Path

1. Learn Bootstrap grid: columns & rows
2. Learn Bootstrap components: buttons, cards, forms
3. Learn Bootstrap utilities: spacing, colors, display
4. Learn Bootstrap layout: navbar, sidebar, footer
5. Learn Bootstrap JavaScript: modal, dropdown, offcanvas

---

**Happy Coding! 🚀**
