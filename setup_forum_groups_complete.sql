-- ============================================
-- Script Setup Forum Groups Lengkap
-- Jalankan script ini untuk memperbaiki forum
-- ============================================

-- 1. Buat tabel forum_groups jika belum ada
CREATE TABLE IF NOT EXISTS forum_groups (
    group_id SERIAL PRIMARY KEY,
    komunitas_id INTEGER NOT NULL REFERENCES forum_komunitas(komunitas_id) ON DELETE CASCADE,
    name VARCHAR(100) NOT NULL,
    icon VARCHAR(50) DEFAULT 'hash',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(komunitas_id, name)
);

-- 2. Tambah kolom group_id ke forum_messages jika belum ada
DO $$ 
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.columns 
        WHERE table_name = 'forum_messages' AND column_name = 'group_id'
    ) THEN
        ALTER TABLE forum_messages 
        ADD COLUMN group_id INTEGER REFERENCES forum_groups(group_id) ON DELETE SET NULL;
    END IF;
END $$;

-- 3. Buat index untuk performa
CREATE INDEX IF NOT EXISTS idx_forum_groups_komunitas ON forum_groups(komunitas_id);
CREATE INDEX IF NOT EXISTS idx_forum_messages_group ON forum_messages(group_id);

-- 4. Buat grup default "Pengumuman" untuk setiap komunitas
INSERT INTO forum_groups (komunitas_id, name, icon)
SELECT komunitas_id, 'Pengumuman', 'volume-up'
FROM forum_komunitas
WHERE NOT EXISTS (
    SELECT 1 FROM forum_groups 
    WHERE forum_groups.komunitas_id = forum_komunitas.komunitas_id 
    AND forum_groups.name = 'Pengumuman'
)
ON CONFLICT (komunitas_id, name) DO NOTHING;

-- 5. Buat grup default "Diskusi Umum" untuk setiap komunitas
INSERT INTO forum_groups (komunitas_id, name, icon)
SELECT komunitas_id, 'Diskusi Umum', 'message-circle'
FROM forum_komunitas
WHERE NOT EXISTS (
    SELECT 1 FROM forum_groups 
    WHERE forum_groups.komunitas_id = forum_komunitas.komunitas_id 
    AND forum_groups.name = 'Diskusi Umum'
)
ON CONFLICT (komunitas_id, name) DO NOTHING;

-- 6. Migrasikan semua pesan lama yang belum punya group_id ke "Diskusi Umum"
UPDATE forum_messages m
SET group_id = (
    SELECT g.group_id 
    FROM forum_groups g 
    WHERE g.komunitas_id = m.komunitas_id 
    AND g.name = 'Diskusi Umum'
    LIMIT 1
)
WHERE m.group_id IS NULL;

-- 7. Verifikasi hasil
SELECT 
    'Setup Complete!' as status,
    (SELECT COUNT(*) FROM forum_groups) as total_groups,
    (SELECT COUNT(*) FROM forum_komunitas) as total_komunitas,
    (SELECT COUNT(*) FROM forum_messages WHERE group_id IS NOT NULL) as messages_with_group,
    (SELECT COUNT(*) FROM forum_messages WHERE group_id IS NULL) as messages_without_group;

-- 8. Tampilkan detail grup per komunitas
SELECT 
    k.nama_komunitas,
    g.name as group_name,
    g.icon,
    COUNT(m.message_id) as message_count
FROM forum_komunitas k
LEFT JOIN forum_groups g ON k.komunitas_id = g.komunitas_id
LEFT JOIN forum_messages m ON g.group_id = m.group_id
GROUP BY k.komunitas_id, k.nama_komunitas, g.group_id, g.name, g.icon
ORDER BY k.nama_komunitas, g.name;
