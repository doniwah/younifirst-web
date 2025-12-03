-- Add image_url column to forum_komunitas
ALTER TABLE forum_komunitas ADD COLUMN IF NOT EXISTS image_url TEXT;

-- Update existing forums with some nice images
UPDATE forum_komunitas SET image_url = 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=400&h=400&fit=crop' WHERE nama_komunitas = 'Komunitas Global';
UPDATE forum_komunitas SET image_url = 'https://images.unsplash.com/photo-1571171637578-41bc2dd41cd2?w=400&h=400&fit=crop' WHERE nama_komunitas = 'Teknik Informatika';
UPDATE forum_komunitas SET image_url = 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=400&h=400&fit=crop' WHERE nama_komunitas = 'Teknik Sipil';
UPDATE forum_komunitas SET image_url = 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=400&h=400&fit=crop' WHERE nama_komunitas = 'Teknik Industri';
UPDATE forum_komunitas SET image_url = 'https://images.unsplash.com/photo-1531973576160-7125cdcd63e7?w=400&h=400&fit=crop' WHERE nama_komunitas = 'Teknik Mesin';
