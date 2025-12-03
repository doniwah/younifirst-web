-- Update event table to match new form fields
-- Run this SQL to update your database schema

-- Check if columns exist and add them if they don't
ALTER TABLE event 
ADD COLUMN IF NOT EXISTS tanggal_selesai DATE AFTER tanggal_mulai,
ADD COLUMN IF NOT EXISTS dl_pendaftaran TIMESTAMP NULL AFTER harga,
ADD COLUMN IF NOT EXISTS waktu_pelaksanaan TIME NULL AFTER dl_pendaftaran,
ADD COLUMN IF NOT EXISTS user_id VARCHAR(255) NULL AFTER waktu_pelaksanaan,
ADD COLUMN IF NOT EXISTS contact_person TEXT NULL AFTER user_id,
ADD COLUMN IF NOT EXISTS url_instagram TEXT NULL AFTER contact_person;

-- Note: Based on your screenshot, these columns already exist:
-- - event_id (bigchar)
-- - nama_event (varchar)
-- - status (status_enum)
-- - poster_event (varchar)
-- - tanggal_mulai (date)
-- - lokasi (varchar)
-- - organizer (varchar)
-- - kapasitas (varchar)
-- - peserta_terdaftar (varchar)
-- - deskripsi (varchar)
-- - created_at (timestamp)
-- - kategori (text)
-- - harga (varchar)
