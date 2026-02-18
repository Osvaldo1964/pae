-- Fix duplicate entry error in Cycle Templates
-- The previous unique key depended on 'meal_type' ENUM, which caused collisions

-- 1. Ensure index exists for Foreign Key on template_id (otherwise we can't drop the unique index)
CREATE INDEX idx_template_id ON cycle_template_days (template_id);

-- 2. Drop the restrictive legacy key
DROP INDEX unique_meal_per_day ON cycle_template_days;

-- 3. Add new key based on Ration Type ID (Dynamic)
-- Uses dynamic IDs to allow infinite flexibility in meal types
-- Note: MySQL allows multiple NULLs in unique index, so legacy rows are safe
CREATE UNIQUE INDEX unique_ration_per_day ON cycle_template_days (template_id, day_number, ration_type_id);
