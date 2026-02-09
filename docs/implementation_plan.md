# Implementation Plan: Fix Cycle Generation Calculation

The user reported that cycle calculation only works for one branch. Investigation revealed that:
1. Many beneficiaries have `ration_type_id` as NULL, preventing matches with recipes.
2. `NeedsReportController` uses inconsistent age group naming (`PRIMARIAA` vs `PRIMARIA_A`).
3. Some beneficiaries are missing both `ration_type` string and ID.

## Proposed Changes

### [Database]
- Update all beneficiaries with `ration_type_id` based on their `ration_type` string.
- Set a default `ration_type_id` (e.g., ALMUERZO) for those with missing ration types, or alert the user.

### [API Controllers]

#### [MODIFY] [NeedsReportController.php](file:///c:/xampp/htdocs/pae/api/controllers/NeedsReportController.php)
- Correct age group naming in `classifyAgeGroup` to match the database (`PRIMARIA_A`, `PRIMARIA_B`).

#### [MODIFY] [MenuCycleController.php](file:///c:/xampp/htdocs/pae/api/controllers/MenuCycleController.php)
- Enhance logging or error reporting during approval if beneficiaries are skipped due to missing ration types.

## Verification Plan

### Automated Tests
- Run a diagnostic script to count calculateable beneficiaries per branch before and after the fix.
- Verify that the Requerimientos report now shows data for all branches.
