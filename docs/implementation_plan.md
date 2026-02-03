# Implementation Plan - Adaptación Resolución 0003 de 2026 e Inventarios PAE

Se requiere actualizar el sistema para soportar la nueva segmentación de grupos etarios dictada por la Resolución 0003 de 2026, lo cual impacta el recetario, las minutas y los parámetros nutricionales.

## Proposed Changes

### [Database]

#### [NEW] [18_age_groups_2026_migration.sql](file:///c:/xampp/htdocs/pae/sql/18_age_groups_2026_migration.sql)
- **Actualizar Enums**: Cambiar `ENUM('PREESCOLAR', 'PRIMARIA', 'BACHILLERATO')` por `ENUM('PREESCOLAR', 'PRIMARIA_A', 'PRIMARIA_B', 'SECUNDARIA')` en las tablas:
    - `nutritional_parameters`
    - `menus`
- **Actualizar `recipe_items`**:
    - Agregar columna `age_group`.
    - Repetir ítems existentes para los 4 nuevos grupos (migración de datos base).
    - Actualizar índice único para incluir `age_group`.

---

### [Backend API]

#### [MODIFY] [RecipeController.php](file:///c:/xampp/htdocs/pae/api/controllers/RecipeController.php)
- Ajustar métodos `show`, `store` y `update` para manejar gramajes por grupo etario.

#### [MODIFY] [MenuController.php](file:///c:/xampp/htdocs/pae/api/controllers/MenuController.php)
- Asegurar que el cálculo de totales nutricionales use el gramaje correspondiente al grupo etario de la minuta.

#### [MODIFY] [BeneficiaryController.php](file:///c:/xampp/htdocs/pae/api/controllers/BeneficiaryController.php)
- Agregar soporte para el campo `is_overage` (Extraedad).

---

### [Frontend]

#### [MODIFY] [recetario.js](file:///c:/xampp/htdocs/pae/app/assets/js/views/recetario.js)
- Rediseñar el modal de ingredientes para permitir ingresar gramajes para los 4 grupos simultáneamente.

#### [MODIFY] [beneficiaries.js](file:///c:/xampp/htdocs/pae/app/assets/js/views/beneficiaries.js)
- Agregar checkbox de "Extraedad".
- Implementar cálculo automático de grupo etario sugerido basado en la fecha de nacimiento.
- [ ] **Verification**: Ensure that switching schools/branches in the modal correctly updates the corresponding record in the database.

---

### Inventarios Module [DONE]
- **Database**: Schemas for `inventory`, `quotes`, `purchase_orders`, and `remissions` implemented.
- **Backend**: `InventoryController.php` with full CRUD for the new entities.
- **Frontend**: `cotizaciones.js`, `compras.js`, `remisiones.js` implemented.

---

## Verification Plan

### Manual Verification
1.  **Recetario**: Crear una receta y asignar gramajes diferentes a "Preescolar" y "Secundaria".
2.  **Cálculo Nutricional**: Verificar que al agregar una receta a una minuta de "Secundaria", los totales nutricionales se calculen con el gramaje de "Secundaria".
3.  **Base de Datos**: Verificar que los tipos de datos se actualizaron correctamente sin pérdida de información previa.
