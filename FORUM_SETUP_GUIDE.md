# 📚 Panduan Setup Forum Groups

## 🎯 Tujuan
Panduan ini akan membantu Anda memperbaiki masalah forum dimana:
- Grup tidak muncul di sidebar
- Pesan tidak bisa terkirim
- Error "Group ID tidak valid"

## 🚀 Cara Menjalankan Setup

### Opsi 1: Menggunakan PHP Script (Recommended)

```bash
php run_forum_setup.php
```

Script ini akan:
- ✅ Membuat tabel `forum_groups` jika belum ada
- ✅ Menambah kolom `group_id` ke tabel `forum_messages`
- ✅ Membuat 2 grup default untuk setiap komunitas
- ✅ Memigrasikan pesan lama ke grup default
- ✅ Menampilkan statistik dan verifikasi

### Opsi 2: Menggunakan SQL Langsung

Jika Anda menggunakan pgAdmin atau psql:

```bash
psql -U postgres -d younifirst -f setup_forum_groups_complete.sql
```

## 📊 Struktur Database

### Tabel `forum_groups`

| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| `group_id` | SERIAL | Primary key |
| `komunitas_id` | INTEGER | Foreign key ke `forum_komunitas` |
| `name` | VARCHAR(100) | Nama grup (contoh: "Diskusi Umum") |
| `icon` | VARCHAR(50) | Icon Bootstrap (contoh: "message-circle") |
| `created_at` | TIMESTAMP | Waktu pembuatan |

### Grup Default

Setiap komunitas akan memiliki 2 grup default:

1. **Pengumuman** 📢
   - Icon: `volume-up`
   - Untuk pengumuman resmi dari admin/moderator

2. **Diskusi Umum** 💬
   - Icon: `message-circle`
   - Untuk diskusi bebas antar anggota

## 🔧 Troubleshooting

### Problem: "Table forum_groups already exists"

**Solusi:** Ini normal jika tabel sudah ada. Script akan skip pembuatan tabel.

### Problem: "Pesan masih tidak bisa terkirim"

**Checklist:**
1. ✅ Pastikan script setup sudah dijalankan
2. ✅ Refresh halaman browser (Ctrl + F5)
3. ✅ Pilih salah satu grup di sidebar kiri
4. ✅ Cek console browser (F12) untuk error JavaScript

### Problem: "Grup tidak muncul di sidebar"

**Solusi:**
```bash
# Verifikasi data di database
php check_forum.php
```

Jika tidak ada grup, jalankan ulang setup:
```bash
php run_forum_setup.php
```

### Problem: "Error: Group ID tidak valid"

**Penyebab:** Tidak ada grup yang dipilih atau grup tidak ada.

**Solusi:**
1. Pastikan ada minimal 1 grup di komunitas
2. Klik salah satu grup di sidebar kiri
3. URL harus berisi `?id=X&group_id=Y`

## ➕ Cara Menambah Grup Baru

### Via UI (Recommended)

1. Buka halaman forum komunitas
2. Klik tombol **"Tambah Grup"** di sidebar kiri
3. Masukkan nama grup (contoh: "Tugas Kuliah")
4. Klik **"Buat Grup"**

### Via SQL

```sql
INSERT INTO forum_groups (komunitas_id, name, icon)
VALUES (1, 'Tugas Kuliah', 'book');
```

### Icon yang Tersedia

Gunakan icon dari [Bootstrap Icons](https://icons.getbootstrap.com/):
- `message-circle` - Chat/diskusi
- `book` - Tugas/akademik
- `code-slash` - Programming
- `trophy` - Kompetisi
- `calendar-event` - Event
- `lightbulb` - Ide/brainstorming
- `question-circle` - Q&A
- `volume-up` - Pengumuman

## 🧪 Verifikasi Setup

### 1. Cek Database

```bash
php check_forum.php
```

Output yang diharapkan:
```
✅ Tabel forum_komunitas ada
✅ Tabel forum_groups ada
✅ Tabel forum_messages ada
📊 Total komunitas: 5
📊 Total groups: 10
📊 Total messages: 25
```

### 2. Cek UI

1. Buka browser: `http://localhost:8000/forum`
2. Login jika belum
3. Klik salah satu komunitas
4. **Verifikasi:**
   - ✅ Sidebar kiri menampilkan minimal 2 grup
   - ✅ Grup "Pengumuman" ada
   - ✅ Grup "Diskusi Umum" ada
   - ✅ Bisa klik grup untuk pindah
   - ✅ URL berubah saat klik grup

### 3. Test Kirim Pesan

1. Pilih grup "Diskusi Umum"
2. Ketik pesan di input box
3. Tekan Enter atau klik tombol kirim
4. **Verifikasi:**
   - ✅ Pesan muncul di chat area
   - ✅ Pesan ada avatar dan timestamp
   - ✅ Refresh halaman, pesan masih ada

## 📝 Struktur Kode

### Backend (PHP)

- **Model:** `app/Model/Forum.php`
  - `getGroups($komunitas_id)` - Ambil semua grup
  - `createGroup($komunitas_id, $name, $icon)` - Buat grup baru
  - `sendMessage($komunitas_id, $user_id, $text, $reply_to, $group_id)` - Kirim pesan

- **Controller:** `app/Controller/ForumController.php`
  - `chat()` - Tampilkan halaman chat
  - `createGroup()` - Handle pembuatan grup
  - `sendMessage()` - Handle pengiriman pesan

### Frontend (JavaScript)

- **File:** `public/js/forum.js` (atau inline di `chat.php`)
  - `sendMessage()` - Kirim pesan via AJAX
  - Modal handling untuk tambah grup
  - Auto-scroll chat area

## 🔄 Migrasi Data

Jika Anda sudah punya pesan lama, script setup akan otomatis:
1. Membuat grup "Diskusi Umum" untuk setiap komunitas
2. Memindahkan semua pesan lama ke grup tersebut
3. Tidak ada data yang hilang

## 📞 Support

Jika masih ada masalah:
1. Cek log error di browser console (F12)
2. Cek log PHP di terminal
3. Verifikasi koneksi database
4. Pastikan server PHP berjalan: `php -S localhost:8000 -t public`

## 🎉 Selesai!

Setelah setup berhasil, Anda bisa:
- ✅ Membuat grup baru untuk setiap komunitas
- ✅ Mengirim pesan di grup yang berbeda
- ✅ Melihat riwayat pesan per grup
- ✅ Anggota bisa diskusi terorganisir per topik
