-- Add dane_code column to school_branches table if it doesn't exist
ALTER TABLE school_branches ADD COLUMN dane_code VARCHAR(50) AFTER pae_id;
