# SobatBogor

Platform direktori dan perencana perjalanan wisata Bogor.

## Tech Stack
- **Backend:** PHP (Native MVC)
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, Bootstrap 5, Alpine.js
- **APIs:** Google Maps, OpenWeatherMap

## Struktur Folder
```
sobatbogor/
├── app/
│   ├── controllers/        ← Logic handler per fitur
│   │   └── admin/          ← Admin-specific controllers
│   ├── models/             ← Database model classes
│   └── views/              ← PHP template files
│       ├── layouts/        ← Base layouts (main, admin)
│       ├── partials/       ← Reusable snippets (navbar, footer)
│       ├── auth/           ← Login & Register pages
│       ├── home/           ← Home page
│       ├── destinations/   ← Catalog & Detail pages
│       ├── wishlist/       ← Wishlist page
│       └── admin/          ← Admin dashboard pages
├── core/                   ← Framework core (Router, DB, Controller, Model)
├── middleware/             ← Auth & Admin role guards
├── config/                 ← Database & app configuration
├── database/
│   └── migrations/         ← SQL schema files
├── routes/
│   └── web.php             ← All URL route definitions
└── public/                 ← Web root (entry point + assets)
    ├── index.php           ← Single entry point
    ├── .htaccess
    └── assets/
        ├── css/
        ├── js/
        ├── images/
        └── uploads/        ← User-uploaded destination images
```

## Cara Menjalankan
1. Pastikan XAMPP berjalan (Apache + MySQL aktif).
2. Buat database: jalankan `database/migrations/schema.sql` di phpMyAdmin.
3. Sesuaikan konfigurasi di `config/database.php` dan `config/app.php`.
4. Akses di browser: `http://localhost/sobatbogor`

## Fase Implementasi
| Tugas | Scope |
|-------|-------|
| 1     | Setup koneksi DB, Router, Model & Controller base |
| 2     | Sistem Auth (Login, Register, Middleware role) |
| 3     | Admin Panel CRUD (Categories, Destinations, Reviews) |
| 4     | Frontend publik (Home, Catalog, Detail) |
| 5     | Integrasi Maps API, Weather API, Wishlist, Review |
