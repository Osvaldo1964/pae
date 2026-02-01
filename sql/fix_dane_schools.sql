-- Add dane_code column to schools table if it doesn't exist
ALTER TABLE schools ADD COLUMN dane_code VARCHAR(50) AFTER pae_id;
