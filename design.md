# SobatBogor - Panduan Desain Frontend & UI/UX

Dokumen ini adalah acuan standar visual untuk seluruh halaman publik dan halaman admin SobatBogor. Pustaka dan gaya di bawah ini wajib diikuti oleh pengembang dan agen AI agar tampilan visual tetap konsisten, premium, dan estetik.

---

## 🎨 Palet Warna (Color System)
Kami menggunakan palet warna alam tropis (hijau, oranye cerah, dan abu-abu gelap) untuk merepresentasikan keindahan pariwisata Bogor:

- **Primary (Oranye Hangat):** `#ea580c` (Tailwind orange-600) / HSL: `20, 92%, 48%`
- **Primary Dark (Oranye Gelap):** `#c2410c` (Tailwind orange-700)
- **Secondary / Accent (Hijau Tropis):** `#16a34a` (Tailwind green-600)
- **Dark Neutral (Teks Utama & Header):** `#0f172a` (Tailwind slate-900)
- **Light Neutral (Background Utama):** `#f8fafc` (Tailwind slate-50)
- **Border / Soft Gray (Garis Tipis):** `#e2e8f0` (Tailwind slate-200)

---

## 📐 Tipografi & Grid
- **Font Utama:** `'Outfit', sans-serif` atau `'Plus Jakarta Sans', sans-serif` (Google Fonts). Memberikan kesan modern dan ramah dibanding default browser.
- **Grid Layout:** Menggunakan grid 12 kolom Bootstrap 5.
  - Desktop: 3 atau 4 kolom untuk daftar tempat wisata (cards).
  - Tablet: 2 kolom.
  - Mobile: 1 kolom penuh.

---

## 📦 Komponen UI Standar

### 1. Kartu Destinasi (Destination Card)
Setiap kartu destinasi publik wajib mengikuti struktur berikut:
- Sudut membulat (`border-radius: 1rem` / `rounded-4`).
- Efek bayangan halus (`box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05)` / `shadow-sm`).
- Hover effect: Card terangkat sedikit (`transform: translateY(-5px)`) dan bayangan menebal secara smooth (`transition: all 0.3s ease`).
- Tombol Wishlist melayang di pojok kanan atas foto menggunakan icon hati FontAwesome.

### 2. Tombol (Buttons)
- **Tombol Utama (Cari, Masuk, Daftar):** Latar belakang oranye/hijau bulat penuh (`btn-success` / `btn-orange`), tanpa border tajam (`rounded-pill` atau `rounded-3`).
- **Filter Kapsul (Category Pills):** Tombol filter kategori di beranda berbentuk kapsul dengan background abu-abu sangat muda `#f1f5f9`, berubah warna menjadi hijau `#16a34a` dengan teks putih saat aktif.

---

## 🚀 Library UI Penunjang (Wajib Disertakan di Layout `main.php`)
1. **Bootstrap 5.3:** Framework CSS utama.
2. **FontAwesome 6.4:** Sumber ikon menu dan detail.
3. **Swiper.js:** Slider galeri foto di halaman detail dan carousel hero.
4. **AOS.js (Animate on Scroll):** Efek animasi kemunculan elemen.
   - Pemicu standar: `data-aos="fade-up"`, `data-aos-delay="100"`.
5. **SweetAlert2:** Untuk feedback aksi user (misalnya simpan wishlist).

---

## 🔒 Konsistensi Fitur
Aplikasi frontend wajib menyesuaikan dengan database skema asli:
- **Destinasi:** Menampilkan Harga Tiket (format Rupiah atau 'Gratis'), Jam Buka, Alamat, Kategori.
- **Review:** Menampilkan rating bintang (1-5), nama pengirim, dan komentar yang hanya muncul jika ulasan disetujui admin (`is_visible = 1`).
- **Wishlist:** Tombol simpan yang memicu perubahan warna ikon hati (merah jika ter-wishlist, transparan jika belum).
