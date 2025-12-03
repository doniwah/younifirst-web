-- Add image_url column to forum_groups table
ALTER TABLE forum_groups ADD COLUMN IF NOT EXISTS image_url VARCHAR(255);

-- Verify the change
SELECT column_name, data_type 
FROM information_schema.columns 
WHERE table_name = 'forum_groups';
