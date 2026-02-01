# Módulo de Roles y Permisos - Documentación

**Fecha de Implementación:** 31 de Enero de 2026  
**Estado:** ✅ Completado

---

## 📋 Descripción

El módulo de Roles y Permisos permite gestionar el control de acceso basado en roles (RBAC) con soporte multitenancy. Los permisos son específicos para cada programa PAE, permitiendo que diferentes entidades tengan configuraciones independientes.

---

## 🎯 Reglas de Negocio

### Super Admin (pae_id = NULL)
- ✅ **CRUD completo de roles**: Crear, editar y eliminar roles
- ✅ **Gestión de permisos globales**: Asignar permisos que aplican a nivel sistema
- ✅ **Acceso total**: Sin restricciones de PAE

### Admin de PAE (pae_id específico)
- ❌ **NO puede crear roles**: Los roles son globales
- ❌ **NO puede eliminar roles**: Los roles son globales
- ✅ **Puede asignar/denegar permisos**: Solo para SU programa PAE
- ✅ **Permisos aislados**: Los permisos solo afectan a su PAE

### Usuarios Regulares
- 👁️ **Solo lectura**: Pueden ver sus permisos asignados
- ❌ **Sin gestión**: No pueden modificar permisos

---

## 🗄️ Estructura de Base de Datos

### Tabla: `module_permissions`

```sql
CREATE TABLE `module_permissions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `role_id` INT(11) NOT NULL,
  `pae_id` INT(11) DEFAULT NULL,  -- NULL = global (Super Admin)
  `module_id` INT(11) NOT NULL,
  `can_create` TINYINT(1) DEFAULT 0,
  `can_read` TINYINT(1) DEFAULT 0,
  `can_update` TINYINT(1) DEFAULT 0,
  `can_delete` TINYINT(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_role_module_pae` (`role_id`, `module_id`, `pae_id`),
  KEY `idx_perm_pae` (`pae_id`),
  CONSTRAINT `fk_perm_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_perm_module` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_perm_pae` FOREIGN KEY (`pae_id`) REFERENCES `pae_programs` (`id`) ON DELETE CASCADE
);
```

### Campos Clave

- **`pae_id`**: 
  - `NULL` = Permiso global (Super Admin)
  - `1, 2, 3...` = Permiso específico de un PAE

- **Permisos CRUD**:
  - `can_create`: Crear nuevos registros
  - `can_read`: Ver/listar registros
  - `can_update`: Editar registros existentes
  - `can_delete`: Eliminar registros

---

## 🔌 API Endpoints

### GET /api/permissions/roles
**Descripción:** Listar todos los roles  
**Autenticación:** JWT requerido  
**Respuesta:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "SUPER_ADMIN",
      "description": "Acceso total al sistema"
    }
  ],
  "can_modify_roles": true  // false para PAE Admin
}
```

---

### GET /api/permissions/modules
**Descripción:** Obtener todos los módulos agrupados  
**Autenticación:** JWT requerido  
**Respuesta:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Configuración",
      "icon": "fas fa-cogs",
      "modules": [
        {
          "id": 1,
          "name": "Usuarios",
          "description": "Gestión de usuarios y accesos",
          "route_key": "users"
        }
      ]
    }
  ]
}
```

---

### GET /api/permissions/matrix/{role_id}
**Descripción:** Obtener matriz de permisos para un rol  
**Autenticación:** JWT requerido  
**Parámetros:** `role_id` (int)  
**Respuesta:**
```json
{
  "success": true,
  "data": [
    {
      "module_id": 1,
      "can_create": 1,
      "can_read": 1,
      "can_update": 1,
      "can_delete": 1
    }
  ],
  "pae_id": null,  // null para Super Admin, ID para PAE Admin
  "is_global": true
}
```

**Lógica:**
- **Super Admin**: Obtiene permisos donde `pae_id IS NULL`
- **PAE Admin**: Obtiene permisos donde `pae_id = {su_pae_id}`

---

### PUT /api/permissions/update
**Descripción:** Actualizar permisos de un módulo  
**Autenticación:** JWT requerido  
**Body:**
```json
{
  "role_id": 2,
  "module_id": 1,
  "permissions": {
    "can_create": 1,
    "can_read": 1,
    "can_update": 0,
    "can_delete": 0
  }
}
```

**Respuesta:**
```json
{
  "success": true,
  "message": "Permisos actualizados exitosamente"
}
```

**Lógica:**
- **Super Admin**: Actualiza permisos con `pae_id = NULL`
- **PAE Admin**: Actualiza permisos con `pae_id = {su_pae_id}`

---

### POST /api/permissions/roles (Super Admin only)
**Descripción:** Crear un nuevo rol  
**Autenticación:** JWT requerido (Super Admin)  
**Body:**
```json
{
  "name": "NUEVO_ROL",
  "description": "Descripción del rol"
}
```

**Respuesta:**
```json
{
  "success": true,
  "message": "Rol creado exitosamente",
  "data": {
    "id": 6
  }
}
```

**Restricción:** Solo Super Admin puede crear roles

---

### DELETE /api/permissions/roles/{id} (Super Admin only)
**Descripción:** Eliminar un rol  
**Autenticación:** JWT requerido (Super Admin)  
**Parámetros:** `id` (int)  

**Respuesta:**
```json
{
  "success": true,
  "message": "Rol eliminado exitosamente"
}
```

**Restricciones:**
- Solo Super Admin puede eliminar roles
- No se puede eliminar el rol SUPER_ADMIN (id=1)
- No se puede eliminar si tiene usuarios asignados

---

## 💻 Frontend

### Archivo: `app/assets/js/views/roles.js`

### Componentes Principales

1. **Selector de Roles**
   - Lista de botones para cada rol
   - Indicador visual del rol seleccionado
   - Botones de gestión (solo Super Admin)

2. **Matriz de Permisos**
   - Tabla agrupada por categorías de módulos
   - Checkboxes interactivos para cada permiso
   - Actualización en tiempo real

3. **Modal de Nuevo Rol** (Solo Super Admin)
   - Formulario de creación
   - Validación de campos

### Funcionalidades

- ✅ Carga dinámica de roles y módulos
- ✅ Selección de rol para ver/editar permisos
- ✅ Actualización instantánea de permisos (checkbox)
- ✅ Creación de nuevos roles (Super Admin)
- ✅ Eliminación de roles (Super Admin)
- ✅ Validación de permisos según tipo de usuario
- ✅ Feedback visual con SweetAlert2

---

## 🔒 Seguridad

### Validaciones Backend

1. **Autenticación JWT**: Todos los endpoints requieren token válido
2. **Validación de Rol**: 
   - Super Admin: `pae_id === null`
   - PAE Admin: `pae_id !== null`
3. **Aislamiento de Datos**: Los permisos se filtran por `pae_id`
4. **Protección de Roles Críticos**: No se puede eliminar SUPER_ADMIN

### Validaciones Frontend

1. **Ocultación de Funciones**: Botones de gestión solo visibles para Super Admin
2. **Feedback Inmediato**: Alertas en caso de errores
3. **Reversión de Cambios**: Si falla la actualización, se revierte el checkbox

---

## 📊 Ejemplos de Uso

### Caso 1: Super Admin asigna permisos globales

```javascript
// Super Admin (pae_id = null) asigna permisos al rol "ADMIN_CENTRAL"
// Estos permisos aplican a TODOS los PAE

PUT /api/permissions/update
{
  "role_id": 2,  // ADMIN_CENTRAL
  "module_id": 1,  // Usuarios
  "permissions": {
    "can_create": 1,
    "can_read": 1,
    "can_update": 1,
    "can_delete": 0
  }
}

// Se guarda con pae_id = NULL (global)
```

### Caso 2: PAE Admin asigna permisos para su programa

```javascript
// PAE Admin (pae_id = 5) asigna permisos al rol "OPERADOR_LOGISTICO"
// Estos permisos solo aplican al PAE #5

PUT /api/permissions/update
{
  "role_id": 3,  // OPERADOR_LOGISTICO
  "module_id": 8,  // Almacén
  "permissions": {
    "can_create": 1,
    "can_read": 1,
    "can_update": 1,
    "can_delete": 1
  }
}

// Se guarda con pae_id = 5 (específico)
```

### Caso 3: Consulta de permisos

```sql
-- Super Admin consulta permisos globales
SELECT * FROM module_permissions 
WHERE role_id = 2 AND pae_id IS NULL;

-- PAE Admin consulta permisos de su PAE
SELECT * FROM module_permissions 
WHERE role_id = 3 AND pae_id = 5;
```

---

## 🧪 Testing

### Pruebas Manuales

1. **Como Super Admin:**
   - [ ] Crear un nuevo rol
   - [ ] Asignar permisos a un rol
   - [ ] Verificar que los permisos se guardan con `pae_id = NULL`
   - [ ] Eliminar un rol sin usuarios
   - [ ] Intentar eliminar SUPER_ADMIN (debe fallar)

2. **Como PAE Admin:**
   - [ ] Verificar que NO aparecen botones de gestión de roles
   - [ ] Asignar permisos a un rol
   - [ ] Verificar que los permisos se guardan con `pae_id = {mi_pae}`
   - [ ] Verificar que solo veo permisos de mi PAE

3. **Multitenancy:**
   - [ ] Crear permisos para PAE #1
   - [ ] Crear permisos para PAE #2
   - [ ] Verificar que cada PAE solo ve sus permisos

---

## 📝 Notas de Implementación

### Decisiones Técnicas

1. **Permisos por PAE**: Se eligió el enfoque de permisos específicos por PAE en lugar de herencia, para mayor flexibilidad.

2. **Roles Globales**: Los roles son globales (no por PAE) para mantener consistencia en la nomenclatura y evitar duplicación.

3. **Actualización en Tiempo Real**: Los checkboxes actualizan inmediatamente para mejor UX, con reversión en caso de error.

4. **Validación Doble**: Se valida tanto en frontend (UX) como en backend (seguridad).

### Limitaciones Conocidas

- ⚠️ No hay historial de cambios de permisos (auditoría)
- ⚠️ No hay permisos a nivel de campo (solo CRUD)
- ⚠️ No hay roles jerárquicos (herencia de permisos)

### Mejoras Futuras

- [ ] Implementar auditoría de cambios de permisos
- [ ] Agregar permisos a nivel de campo
- [ ] Implementar herencia de permisos entre roles
- [ ] Agregar bulk update de permisos
- [ ] Exportar/importar configuración de permisos

---

## 🐛 Troubleshooting

### Error: "No tienes permisos para crear roles"
**Causa:** Usuario PAE Admin intentando crear un rol  
**Solución:** Solo Super Admin puede crear roles

### Error: "No se puede eliminar el rol porque tiene usuarios asignados"
**Causa:** Intentando eliminar un rol con usuarios activos  
**Solución:** Reasignar usuarios a otro rol antes de eliminar

### Error: "Token inválido o expirado"
**Causa:** JWT expirado o inválido  
**Solución:** Hacer logout y login nuevamente

---

## 📞 Soporte

**Documentación:** `/docs`  
**Desarrollador:** OVCSYSTEMS S.A.S.

---

**Última Actualización:** 31 de Enero de 2026, 10:16 PM
