-- Add image_url column to forum_messages table
ALTER TABLE forum_messages ADD COLUMN IF NOT EXISTS image_url VARCHAR(255) DEFAULT NULL;

-- Verify the column was added
SELECT column_name, data_type, character_maximum_length 
FROM information_schema.columns 
WHERE table_name = 'forum_messages' AND column_name = 'image_url';
