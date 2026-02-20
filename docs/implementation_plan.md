# Dashboard Principal - PAE Control

Implementación del panel de control analítico y operativo de la plataforma, priorizando la ejecución local sin dependencias externas (CDNs) para garantizar autonomía y velocidad.

## Proposed Changes

### 1. Archivos Externos (Librerías Locales)
#### [NEW] `chart.js`(file:///c:/xampp/htdocs/pae/app/assets/js/libs/chart.min.js)
- Descarga y alojamiento local de la librería Chart.js v4 para la renderización de las gráficas de dona y líneas.

### Dashboard (KPI Presupuesto)
Alinear el cálculo del presupuesto en el tablero con la visualización del módulo de Planeación.

#### [MODIFY] [DashboardController.php](file:///c:/xampp/htdocs/pae/api/controllers/DashboardController.php)
- Modificar la consulta del KPI de presupuesto para unirla con la tabla `presupuesto_items` y filtrar únicamente por rubros con `estado = 1` (Activos).

## Verification Plan:
  - `kpis`: Totales (beneficiarios, sedes, presupuesto, raciones hoy).
  - `charts`: Datos agrupados para las gráficas (entregas de los últimos 7 días, distribución de sedes/raciones).
  - `alerts`: Listados operativos (top 5 insumos críticos, minuto del día).

### 2. Backend (API)
#### [NEW] `DashboardController.php`(file:///c:/xampp/htdocs/pae/api/controllers/DashboardController.php)
- Controlador encargado de recolectar estadísticas consolidadas. Retornará un payload estructurado con:
  - `kpis`: Totales (beneficiarios, sedes, presupuesto, raciones hoy).
  - `charts`: Datos agrupados para las gráficas (entregas de los últimos 7 días, distribución de sedes/raciones).
  - `alerts`: Listados operativos (top 5 insumos críticos, minuto del día).

#### [MODIFY] `index.php`(file:///c:/xampp/htdocs/pae/api/index.php)
- Añadir la ruta `GET /dashboard` e instanciar el nuevo controlador.

### 3. Frontend (UI)
#### [NEW] `dashboard.js`(file:///c:/xampp/htdocs/pae/app/assets/js/views/dashboard.js)
- Vista principal que será cargada al iniciar sesión. Contendrá:
  - Layout Grid de Bootstrap 5.
  - Invocación a Chart.js para inicializar los lienzos (canvas).
  - Funciones de formateo (moneda y números).

#### [MODIFY] `app.js`(file:///c:/xampp/htdocs/pae/app/assets/js/core/app.js)
- Actualizar el router para que la ruta `#module/dashboard` e inicial default carguen `DashboardView`.

#### [MODIFY] `index.php`(file:///c:/xampp/htdocs/pae/app/index.php)
- Incluir la etiqueta `<script>` para pre-cargar `chart.min.js` y `dashboard.js` localmente.

---

## 4. Optimización de Rendimiento (Ciclos)
El motor de explosión de víveres actualmente tiene una complejidad de $O(N \times M)$ y genera miles de líneas de log, lo que causa bloqueos en el navegador.

### Backend (Optimización)
#### [MODIFY] [MenuCycleController.php](file:///c:/xampp/htdocs/pae/api/controllers/MenuCycleController.php)
- **Eliminar `error_log`** dentro de los bucles de cálculo de demanda.
- **Agrupar recetas por ración y edad** antes de procesar la población para reducir iteraciones.
- **Transacción atómica** para la inserción masiva de resultados.
