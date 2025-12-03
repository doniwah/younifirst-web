-- Create forum_groups table
CREATE TABLE IF NOT EXISTS forum_groups (
    group_id SERIAL PRIMARY KEY,
    komunitas_id INTEGER REFERENCES forum_komunitas(komunitas_id) ON DELETE CASCADE,
    name VARCHAR(100) NOT NULL,
    icon VARCHAR(50) DEFAULT 'hash',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Add group_id to forum_messages
ALTER TABLE forum_messages ADD COLUMN IF NOT EXISTS group_id INTEGER REFERENCES forum_groups(group_id) ON DELETE SET NULL;

-- Create default 'Diskusi Umum' and 'Pengumuman' groups for existing communities
INSERT INTO forum_groups (komunitas_id, name, icon)
SELECT komunitas_id, 'Pengumuman', 'volume-up'
FROM forum_komunitas
WHERE NOT EXISTS (SELECT 1 FROM forum_groups WHERE komunitas_id = forum_komunitas.komunitas_id AND name = 'Pengumuman');

INSERT INTO forum_groups (komunitas_id, name, icon)
SELECT komunitas_id, 'Diskusi Umum', 'message-circle'
FROM forum_komunitas
WHERE NOT EXISTS (SELECT 1 FROM forum_groups WHERE komunitas_id = forum_komunitas.komunitas_id AND name = 'Diskusi Umum');

-- Update existing messages to belong to 'Diskusi Umum'
UPDATE forum_messages m
SET group_id = (
    SELECT group_id FROM forum_groups g 
    WHERE g.komunitas_id = m.komunitas_id AND g.name = 'Diskusi Umum'
    LIMIT 1
)
WHERE group_id IS NULL;
