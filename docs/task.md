# Task List

- [x] Remove "Usuarios" from Sidebar and add to Configuration Hub for Super Admin
    - [x] Locate Sidebar rendering in `app/assets/js/core/app.js`
    - [x] Locate Configuration Hub rendering in `app/assets/js/core/app.js` or specific view
    - [x] Update logic to move "Usuarios"
- [x] Add Breadcrumbs to modules
    - [x] Identify common header or template where breadcrumbs should be placed
    - [x] Implement breadcrumb logic based on current route/view
- [x] Verify changes
    - [x] Test as Super Admin
    - [x] Test as Admin PAE
- [x] Adapt System to 2026 Age Groups (Resolution 0003)
    - [x] Update `nutritional_parameters` schema (Migration ready)
    - [x] Update `recipe_items` schema to support group grammages
    - [x] Modify `RecipeController.php` to handle multi-group recipes
    - [x] Arreglar error de guardado en Beneficiarios (Missing columns)
    - [x] Añadir columnas `observations` e `is_overage` a la BD
    - [x] Corregir binding de parámetros en `BeneficiaryController.php`
    - [x] Incluir `school_id` en la consulta de lista para poblar dropdowns
    - [x] Corregir codificación de ENUMs `ration_type` y `modality`
    - [x] Verificar persistencia total de datos de matrícula
    - [x] Implement calculation logic for nutrient validation per group
- [x] Create Inventarios Module
    - [x] Database Migration (Schemas, Groups, Modules)
    - [x] Fix DataTables initialization in Cotizaciones, Compras, Remisiones
    - [x] Correct character encoding in module names
    - [x] Implement modals for Compras and Remisiones
    - [x] Move Suppliers module to Inventory group
    - [x] Fix Supplier API response structure (JSON wrapper)
    - [x] Backend API Implementation (Controllers, Routes)
    - [x] Frontend Views (Quotes, Purchases, Entries, Remissions, Adjustments)
    - [x] Verify full flow

- [ ] Trabajar en el módulo de Ciclos
    - [x] Identificar archivos actuales (Controladores, Modelos, Vistas)
    - [x] Definir objetivos específicos (Generación Manual, Rotativa, Aleatoria)
    - [x] Implementar cambios Backend (Controller)
    - [x] Implementar cambios Frontend (Minutas JS)
    - [x] **Debugging**:
        - [x] Corregir persistencia al eliminar (Bug de enrutamiento y controlador).
        - [x] Corregir error SQL durante generación (Faltaba columna `date` en `menus`).
    - [ ] **Bloqueo Actual**:
        - [ ] Schema Inconsistency: `menu_items` espera `item_id` pero el sistema intenta guardar `recipe_id`. Requiere migración.
    - [ ] **Feature**: Explosión de Víveres (Pendiente de corrección de schema).



