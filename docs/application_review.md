# Revisión Completa de la Aplicación PAE

Este documento detalla el estado actual de la aplicación, las áreas de optimización identificadas y la documentación de los scripts de migración.

## 1. Revisión de Base de Datos y Tablas
La base de datos sigue una estructura relacional sólida, recientemente actualizada para soportar tipos de ración dinámicos.

### Tablas Principales
- **pae_programs**: Tabla central que identifica a cada operador/inquilino (Tenants).
- **beneficiaries**: Almacena los beneficiarios asociados a una sede y un programa PAE.
- **pae_ration_types**: [NUEVA] Gestiona tipos de ración (Desayuno, Almuerzo, etc.) de forma dinámica por programa.
- **schools / school_branches**: Estructura jerárquica de instituciones y sedes.
- **menus / recipes / items**: Gestión de minutas, recetas e insumos.
- **users / roles / permissions**: Control de acceso basado en roles (Super Admin, Admin Programa, etc.).

---

## 2. Implementación de JWT (JSON Web Token)
La seguridad se basa en tokens JWT para autenticar cada petición al API.

### Estado Actual
- **Backend (`api/utils/JWT.php`)**: Implementación manual de `HS256`. Los tokens expiran en 1 hora.
- **Flujo de Auth**: `AuthController.php` gestiona el login. Soporta "Mascarada" para Super Admins Globales, permitiéndoles entrar en cualquier programa PAE.
- **Frontend (`config.js` & `helper.js`)**: El token se almacena en `localStorage` y se envía automáticamente en el encabezado `Authorization: Bearer <token>` mediante `Helper.fetchAPI`.

### Recomendaciones de Mejora
- **Rotación de Secret Key**: Actualmente la clave está integrada en el código. Se recomienda moverla a variables de entorno (`.env`).
- **Refresh Tokens**: Implementar una estrategia de refresco para mejorar la experiencia de usuario sin comprometer la seguridad.

---

## 3. Optimización y Código Duplicado
Se han identificado patrones repetitivos que pueden centralizarse para mejorar la mantenibilidad.

### Áreas de Oportunidad
- **Centralización de Auth**: Casi todos los controladores repiten el código para extraer el `pae_id` del token. Esto debería moverse a un **Middleware** o un **Controlador Base**.
- **Capa de Modelos**: Actualmente los controladores contienen SQL directo. Implementar una capa de `Models` (ya prevista en el autoloader) permitiría reutilizar lógica de datos y limpiar los controladores.
- **Estandarización de Respuestas**: Crear un método `sendResponse($data, $code)` para evitar el uso repetitivo de `json_encode` y `http_response_code`.

---

## 4. Documentación de Scripts de Migración
A continuación se listan los scripts ubicados en `api/migrations/` y su función:

| Script | Descripción |
| :--- | :--- |
| `20260207_dynamic_ration_types.php` | Migración principal para convertir tipos de ración estáticos en dinámicos (Tabla `pae_ration_types`). |
| `20260207_hr_module.php` | Crea las tablas necesarias para el módulo de Recursos Humanos (Empleados y Cargos). |
| `20260207_update_meal_types.php` | Actualiza registros existentes para asegurar compatibilidad con la nueva estructura de raciones. |
| `20260207_reorder_menu_groups.php` | Organiza el orden de aparición de los módulos en el menú lateral. |
| `create_daily_consumptions_table.php` | Crea la tabla para registrar consumos diarios de beneficiarios. |
| `add_date_to_menus.php` | Pequeño ajuste para añadir campos de fecha a la gestión de minutas. |
| `run_step_by_step.php` | Script de utilidad diseñado para ejecutar migraciones de forma secuencial y controlada. |

---

## Próximos Pasos Sugeridos
1. **Refactorizar `BeneficiaryController`**: Como piloto para implementar el Controlador Base y limpiar el código duplicado.
2. **Seguridad**: Mover credenciales de DB y JWT a un archivo de configuración externo protegido.
