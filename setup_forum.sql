-- Script SQL untuk membuat data sample forum
-- Jalankan langsung di PostgreSQL atau via pgAdmin

-- 1. Insert Komunitas (Forum Communities)
INSERT INTO forum_komunitas (nama_komunitas, deskripsi, icon_type, jurusan_filter) VALUES
('Komunitas Global', 'Forum untuk semua mahasiswa dari berbagai jurusan', 'globe', NULL),
('Teknik Informatika', 'TI - Diskusi seputar programming, AI, dan teknologi', 'users', 'Informatika'),
('Teknik Sipil', 'TS - Berbagi ilmu konstruksi dan infrastruktur', 'users', 'Teknik Sipil'),
('Teknik Industri', 'IND - Forum optimasi dan manajemen produksi', 'users', 'Teknik Industri'),
('Teknik Mesin', 'TM - Diskusi mesin dan manufaktur', 'users', 'Teknik Mesin')
ON CONFLICT DO NOTHING;

-- 2. Tambahkan semua user ke Komunitas Global (ID 1)
-- Ganti dengan user_id yang ada di database Anda
INSERT INTO forum_anggota (komunitas_id, user_id, joined_at)
SELECT 
    1 as komunitas_id,
    user_id,
    NOW() as joined_at
FROM users
ON CONFLICT DO NOTHING;

-- 3. Tambahkan user ke komunitas sesuai jurusan mereka
-- Informatika -> Teknik Informatika (ID 2)
INSERT INTO forum_anggota (komunitas_id, user_id, joined_at)
SELECT 
    2 as komunitas_id,
    user_id,
    NOW() as joined_at
FROM users
WHERE jurusan = 'Informatika'
ON CONFLICT DO NOTHING;

-- Teknik Sipil -> Teknik Sipil (ID 3)
INSERT INTO forum_anggota (komunitas_id, user_id, joined_at)
SELECT 
    3 as komunitas_id,
    user_id,
    NOW() as joined_at
FROM users
WHERE jurusan = 'Teknik Sipil'
ON CONFLICT DO NOTHING;

-- Teknik Industri -> Teknik Industri (ID 4)
INSERT INTO forum_anggota (komunitas_id, user_id, joined_at)
SELECT 
    4 as komunitas_id,
    user_id,
    NOW() as joined_at
FROM users
WHERE jurusan = 'Teknik Industri'
ON CONFLICT DO NOTHING;

-- Teknik Mesin -> Teknik Mesin (ID 5)
INSERT INTO forum_anggota (komunitas_id, user_id, joined_at)
SELECT 
    5 as komunitas_id,
    user_id,
    NOW() as joined_at
FROM users
WHERE jurusan = 'Teknik Mesin'
ON CONFLICT DO NOTHING;

-- 4. Buat sample messages untuk Komunitas Global
-- Ambil user pertama dari database
DO $$
DECLARE
    v_user_id VARCHAR;
BEGIN
    SELECT user_id INTO v_user_id FROM users ORDER BY user_id LIMIT 1;
    
    INSERT INTO forum_messages (komunitas_id, user_id, message_text, created_at) VALUES
    (1, v_user_id, 'Halo semua! Selamat datang di Komunitas Global 👋', NOW() - INTERVAL '5 hours'),
    (1, v_user_id, 'Ada event menarik minggu depan, yuk gabung!', NOW() - INTERVAL '4 hours'),
    (1, v_user_id, 'Siapa yang mau ikut workshop programming?', NOW() - INTERVAL '3 hours'),
    (1, v_user_id, 'Diskusi project bersama yuk!', NOW() -INTERVAL '2 hours'),
    (1, v_user_id, 'Tips belajar efektif dong guys', NOW() - INTERVAL '1 hour');
END $$;

-- Verifikasi hasil
SELECT 'Komunitas' as tabel, COUNT(*) as jumlah FROM forum_komunitas
UNION ALL
SELECT 'Anggota' as tabel, COUNT(*) as jumlah FROM forum_anggota
UNION ALL
SELECT 'Messages' as tabel, COUNT(*) as jumlah FROM forum_messages;
