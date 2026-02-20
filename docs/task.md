# Desarrollo: Dashboard Principal (Tablero de Control)

## Fase 1: Preparación Frontend y Librerías Locales
- [x] Descargar `chart.js` (minificado) y alojarlo en `app/assets/js/libs/chart.min.js`.
- [x] Incluir el tag de script `<script src="assets/js/libs/chart.min.js"></script>` en `app/index.php`.
- [x] Modificar `app/assets/js/core/app.js` para que la ruta raíz y `#module/dashboard` apunten a la vista del dashboard.

## Fase 2: Desarrollo del Backend API
- [x] Crear archivo `api/controllers/DashboardController.php`.
- [x] Desarrollar `DashboardController::getIndex()` que consolide:
  - `KPIs`: Total Beneficiarios, Total Sedes, Ejecución vs PPTO, Raciones Hoy.
  - `Grafica Lineal`: Raciones Entregadas en los últimos 7 días.
  - `Grafica Dona`: Desglose del Presupuesto o Distribución de Beneficiarios.
  - `Alertas`: SQL query de Kardex cruzando contra punto de re-orden (top 5 críticos).
- [x] Corregir error 500 en Dashboard (Select en DashboardController)
- [x] Corregir error "Chart is not defined"
    - [x] Verificar inclusión de chart.min.js en index.php
    - [x] Inicializar versión en App.state para evitar cache
    - [x] Asegurar carga de Chart antes de inicializar vista
- [x] Corregir conteo de "Sedes Activas" (Estandarización de estados active/ACTIVO)
- [x] Alinear saldo presupuestal Dashboard vs Planeación (Filtrado de rubros activos)
- [x] Optimizar aprobación de ciclo de menú (Rendimiento)
- [x] Corregir errores en reporte de presupuesto (Rutas y API)
- [x] Mejorar vista de impresión (Presupuesto)
- [x] Corregir eliminación de ítems de presupuesto (Efecto visual)
- [x] Validar flujo de creación/edición de presupuesto (ID passing).
- [x] Registrar el endpoint `GET /dashboard` en `api/index.php`.

## Fase 3: Integración y Vista (UI)
- [x] Crear el archivo `app/assets/js/views/dashboard.js`.
- [x] Diseñar el bloque superior (4 tarjetas de KPIs).
- [x] Diseñar el bloque central (2 canvas para Chart.js).
- [x] Diseñar el bloque inferior (tabla de stock crítico).
- [x] Desarrollar la función `loadData()` que consuma el API remoto y alimente el DOM y los gráficos usando `new Chart()`.
