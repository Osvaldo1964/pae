# Estado de Desarrollo - PAE Control WebApp

**Última actualización**: 08 de Febrero 2026, 13:15 PM
**Versión:** 1.6.6 (Fase 4 - Operación: Estabilización de Ítems)

---

## 📊 Resumen Ejecutivo

| Categoría | Estado | Progreso |
|-----------|--------|----------|
| **Backend API** | 🟢 Funcional | 99% |
| **Frontend Core** | 🟢 Funcional | 99% |
| **App Móvil (PWA)** | 🔴 Bloqueado | 70% |
| **Base de Datos** | 🟢 Estable | 99% |
| **Módulos Admin** | 🟢 Funcional | 100% |
| **Módulos Operativos** | 🟢 Funcional | 90% |
| **Documentación** | 🟢 Actualizada | 100% |

---

## ✅ COMPLETADO

### 1. Infraestructura Base ✅
- [x] Estructura MVC y Enrutador REST.
- [x] Gestión de sesión JWT con expiración segregada.
- [x] Multitenancy (aislamiento de datos por `pae_id`).
- [x] Helpers de sistema para fetch, alertas y validaciones.
- [x] Sistema de versionado global para cache-busting.

### 2. Módulo de Usuarios ✅
- [x] CRUD completo con filtros de seguridad por PAE.
- [x] **Casing Automático:** Nombres en MAYÚSCULAS, emails en minúsculas.
- [x] UI robusta con listado DataTable y modales contextuales.
- [x] Campos adicionales: Dirección y teléfono.

### 3. Módulo de Entorno (Colegios, Sedes y Proveedores) ✅
- [x] Gestión de Instituciones Educativas (Colegios).
- [x] Gestión de Sedes físicas asignadas.
- [x] **Códigos DANE:** Implementados en Colegios y Sedes (independientes).
- [x] **Gestión de Proveedores:** Directorio con aislamiento por programa.
- [x] **Gestión de Logos:** Subida y visualización unificada.
- [x] Sede principal generada automáticamente al crear colegio.
- [x] Autonomía de datos: Solo visibles para el programa actual.

### 4. Módulo de Roles y Permisos (REDISEÑADO) ✅
- [x] **Nueva Interfaz:** DataTable para roles con acceso vía "Llave".
- [x] **Matriz de Permisos:** Modal con autoscroll y guardado masivo.
- [x] Permisos específicos CRUD por módulo y por programa PAE.
- [x] Protección de niveles jerárquicos (Super Admin vs PAE Admin).

### 5. Gestión de Programas (Super Admin) ✅
- [x] Dashboard de gestión de inquilinos (PAE Programs).
- [x] Configuración de logos de operador y entidad territorial.

### 6. Módulo de Beneficiarios (Estudiantes) ✅ ⭐ NUEVO
- [x] **Backend:** `BeneficiaryController.php` con CRUD completo.
- [x] **Frontend:** Formulario multi-pestaña (4 secciones):
  - Identificación (Documento, nombres, etnia, SISBEN)
  - Matrícula (Colegio, sede, grado, jornada)
  - Contacto (Dirección, teléfono, acudiente)
  - Salud y Otros (Discapacidad, población víctima/migrante)
- [x] **Base de Datos:**
  - Tablas maestras: `document_types`, `ethnic_groups`
  - Tabla principal: `beneficiaries` (30+ campos)
  - Migraciones de refinamiento aplicadas
- [x] **Cumplimiento Resolución 0003 de 2026**
- [x] Validación de duplicados por documento
- [x] Filtros personalizados (Documento, Colegio, Grado)
- [x] Integración con códigos DANE
- [x] Autorización de datos (Habeas Data)

### 7. Módulo de Cocina - Ítems ✅ ⭐ NUEVO
- [x] **Backend:** `ItemController.php` con CRUD completo
- [x] **Frontend:** Formulario multi-pestaña (4 secciones):
  - Información Básica (Nombre, código, grupo, unidad, rendimiento)
  - Información Nutricional (10 nutrientes completos)
  - Alérgenos (6 alérgenos principales)
  - Logística y Costos (Compra local, trazabilidad, costos)
- [x] **Base de Datos:**
  - Tablas maestras: `food_groups`, `measurement_units`
  - Tabla principal: `items` (35+ campos)
  - Tablas preparadas para minutas: `menu_cycles`, `menus`, `menu_items`, `nutritional_parameters`
- [x] **Cumplimiento Resolución 0003 de 2026:**
  - Clasificación por grupo de alimento (9 categorías)
  - Factor de rendimiento (peso bruto vs neto)
  - Compra local (Ley 2046 - 30%)
  - Trazabilidad (registro sanitario, refrigeración, vida útil)
  - Control de alérgenos y sodio
- [x] Cálculo automático de % desperdicio
- [x] Filtros por grupo, compra local y estado
- [x] Badges de colores por grupo de alimento
- [x] **Lógica de Perecederos:** Campo explícito `is_perishable` para diferenciar logística de frío vs rotación rápida (Pan, Huevos).
- [x] **Indicadores Visuales:** Iconos de reloj (Perecedero 🕒) y nieve (Refrigerado ❄️) integrados en Ítems y Almacén.

### 8. Módulo de Cocina - Recetario Maestro ✅ ⭐ NUEVO
- [x] **Backend:** `RecipeController.php` con CRUD y motor de recalculación.
- [x] **Base de Datos:** Estructura de recetas, ingredientes patrón y plantillas de ciclo.
- [x] **Frontend:** Diseño de tarjetas compactas (4 columnas) con indicadores nutricionales.
- [x] **Cálculos:** Motor automático basado en 100g de ingrediente (ICBF).
- [x] **UX:** Scroll interno y modales dinámicos para gestión a gran escala.
- [x] **Bug Fixes:** Corrección de redirecciones y carga de ingredientes en edición.

### 9. Módulo de Minutas y Ciclos ✅ ⭐ NUEVO
- [x] **Backend:** `CycleTemplateController.php` y `MenuCycleController.php`.
- [x] **Plantillas Maestras:** Estructura de 20 días con platos base vinculados al recetario.
- [x] **Generador de Ciclos:** Motor de calendario automático que omite sábados y domingos.
- [x] **Frontend:** Interfaz de doble pestaña (Ciclos Activos vs Plantillas Standard).
- [x] **Aplicación Rápida:** Funcionalidad de clonación de plantilla a calendario mensual.
- [x] **Validaciones:** Restricción de eliminación para ciclos activos o validados nutricionalmente.
- [x] **Refinamiento:** Borrado en cascada (limpia menús e ítems asociados).
- [x] **Reportes:** Explosión de insumos detallada por sede y edad (Excel/PDF).
- [x] **Tipos de Ración:** Reubicación funcional al módulo de Cocina con ordenamiento manual (Items > Tipos Ración > Recetario > Ciclos).

### 10. Módulo de Almacén (Inventario) 🟡 ⭐ EN CURSO
- [x] **Backend:** `InventoryController.php` con gestión de stock y movimientos.
- [x] **Stock Actual:** Listado con alertas de existencias críticas.
- [x] **Movimientos:** Registro de entradas y salidas con trazabilidad.
- [x] **Integración:** Vinculación con proveedores y ítems maestros.
- [ ] **Ajustes:** Toma física y auditoría.

### 11. Módulo de Entregas (Resolución 003) - Fase 1 & 2 ✅ ⭐ NUEVO
- [x] **Identificación Digital:** Generador de Carnet Estudiantil (PDF/Print).
- [x] **QR Tokenizado:** Código único (`PAE:[ID]:[DOC]`) para validación de entregas.
- [x] **Diseño:** Tarjeta estándar tipo documento de identidad (Ajustada a 560px para evitar cortes de QR).
- [x] **App Móvil (PWA):** Interfaz optimizada para tablet/celular en `/movil/`.
- [x] **Escáner QR:** Integración con `html5-qrcode` para lectura rápida de carnets.
- [x] **Lógica de Entrega:** Registro automático de AM/ALMUERZO/PM según horario.
- [x] **Validación Anti-Fraude:** Bloqueo de doble entrega del mismo complemento en el mismo día.

### 13. Reporte de Asistencia y Consumo (QR) ✅ ⭐ NUEVO
- [x] **Backend:** `ConsumptionController.php` con endpoint `/consumptions/report`.
- [x] **Filtros Dinámicos:** Consulta por Institución, Sede, Fecha y Jornada.
- [x] **Frontend:** `consumos.js` con visualización de registros en tiempo real.
- [x] **Planilla Oficial:** Formato de impresión según Resolución 0003 con logos y firmas.
- [x] **Aislamiento:** Filtrado estricto por `pae_id` para seguridad multitenancy.
- [x] **UX:** Integración de estados de carga (`Helper.loading`).

### 12. Módulo de Almacén - Reporte de Necesidades ✅ ⭐ NUEVO
- [x] **Comparativa Dinámica:** Reporte que cruza Inventario Actual vs Requerimientos de Menú Programado.
- [x] **Cálculo de Déficit:** Identificación automática de insumos faltantes para la operación.
- [x] **Filtros:** Por rango de fechas y sedes.
- [x] **UX Navegación:** Reordenamiento del menú lateral (Recurso Humano antes de Reportes) para flujo lógico de operación.

---

## 🚧 EN DESARROLLO

### 7. Dashboard Principal 🟡
- [ ] Widgets de estadísticas operativas.
- [ ] Integración de gráficos de gestión.

---

## 📅 PRÓXIMOS PASOS

### Fase 3 (Cocina) - EN CURSO
- [x] **Ítems:** COMPLETADO ✅
- [x] **Recetario:** COMPLETADO ✅
- [x] **Minutas:** COMPLETADO ✅
- [ ] **Almacén:** Entradas, salidas e inventario
- [ ] **Novedades:** Reporte de ausentismos y retiros

### Fase 4 (Operación) - FUTURO
- [ ] Entregas diarias
- [ ] Reportes gerenciales
- [ ] Integración con SIMAT


---

## 🔧 CORRECCIONES RECIENTES (v1.6.0)

### Módulo de Operatividad y Logística
- ✅ **Estabilización de Ítems:** Corregida extracción de `pae_id` del token JWT y normalización de códigos automáticos.
- ✅ **Fix de UI:** Corregido orden de argumentos en `Helper.alert` para mostrar iconos correctos en SweetAlert2.
- ✅ **Lógica de Perecederos:** Implementada distinción explícita entre productos refrigerados y de alta rotación (Perecederos).
- ✅ **Tipos de Ración:** Corregido SyntaxError de re-declaración y warning de "status" en el controlador.
- ✅ **Navegación:** Ajustado orden de grupos en el Sidebar y manual ordering en Hub de Cocina.
- ✅ **Reporte de Asistencia (QR):** Implementado desde cero para auditoría de raciones capturadas en móvil.
- ✅ **Fix de UX:** Añadido `Helper.loading()` para feedback visual en búsquedas pesadas.
- ✅ **Estabilización de Almacén:** Corregida lógica de saldos en remociones de órdenes de compra y visualización de iconos logísticos.

### Módulo de Almacén
- ✅ Corregida ruta de API para proveedores (`/proveedores`).
- ✅ Ajustado mapeo de datos para peticiones concurrentes (Inventory, Movements, Suppliers).

### Módulo de Minutas
- ✅ Implementado borrado funcional de ciclos (eliminación en cascada).
- ✅ Activada vista de detalle de ciclo con alertas informativas.
- ✅ Corregida inconsistencia de carga de recetas en el listado.

### Módulo Móvil de Entregas
- ✅ **Bypass de Apache:** Solución robusta para pérdida de header `Authorization` usando `X-Auth-Token` y reglas de `.htaccess`.
- ✅ **Fix Login:** Sincronización de parámetros `username`/`email` entre App y API.
- ✅ **Layout Carnet:** Incrementada altura a 560px y habilitado `overflow:visible` para garantizar legibilidad de QR.
- ✅ **Versioning:** Implementado `?v=1.0.2` en scripts móviles para forzar limpieza de caché en despliegue.

### General
- ✅ **Ruteo Dinámico:** El sistema ahora es agnóstico a la subcarpeta de instalación (localhost/pae/ vs dominio.com/).
- ✅ **Estabilidad:** Mejorado el manejo de respuestas JSON vacías o malformadas.
- ✅ **Diagnóstico:** Reforzados los logs en `BranchController` y respuestas con `debug` info para trazabilidad de errores 403.

---

## 📝 NOTAS TÉCNICAS (v1.5.0)

### Seguridad
- **JWT:** Todas las peticiones validan el `pae_id` del token para evitar filtraciones entre programas.
- **Multitenancy:** Aislamiento estricto por programa PAE.

### Frontend
- **Vista Minutas:** Usa `Helper.fetchAPI` para llamadas asíncronas concurrentes (Templates, Cycles, Recipes).
- **SweetAlert2:** Integrado para confirmaciones de borrado y alertas de validación.

---

## 📂 Archivos Clave - Minutas y Ciclos

### Backend
- `api/controllers/CycleTemplateController.php` - Plantillas maestras
- `api/controllers/MenuCycleController.php` - Generación de ciclos
- `api/controllers/NeedsReportController.php` - Lógica de reporte de insumos
- `api/index.php` - Rutas de minutas (líneas 315-333 approx)

### Frontend
- `app/assets/js/views/minutas.js` - Vista completa de gestión
- `app/assets/js/core/app.js` - Router (ruta: `minutas`)

### Base de Datos
- `sql/16_recipes_schema.sql` - Estructura de recetas y plantillas
- `sql/09_kitchen_schema.sql` - Estructura de ciclos y menús

---

**Documentación adicional:** Ver `docs/ESTADO_SISTEMA.md` para resumen ejecutivo de módulos.
