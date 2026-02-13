# API Reference - PAE Control WebApp

**Versión:** 1.0  
**Base URL:** `http://localhost/pae/api`  
**Formato:** JSON  
**Autenticación:** JWT Bearer Token

---

## 📋 Tabla de Contenidos

1. [Autenticación](#autenticación)
2. [Usuarios](#usuarios)
3. [Roles](#roles)
4. [PAE (Entidades)](#pae-entidades)
5. [Beneficiarios](#beneficiarios-1)
6. [Cocina - Ítems](#cocina-ítems)
7. [Cocina - Recetas](#cocina-recetas)
8. [Minutas y Ciclos](#minutas-y-ciclos)
9. [Códigos de Estado](#códigos-de-estado)
10. [Manejo de Errores](#manejo-de-errores)

---

## 🔐 Autenticación

Todos los endpoints (excepto `/auth/login`) requieren un token JWT en el header:

```http
Authorization: Bearer {token}
```

### POST /auth/login

Autenticar usuario y obtener token JWT.

**Request:**
```json
{
  "username": "admin",
  "password": "admin"
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Login exitoso",
  "data": {
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "user": {
      "id": 1,
      "username": "admin",
      "full_name": "Administrador del Sistema",
      "email": "admin@pae.com",
      "role_id": 1,
      "role_name": "Super Admin",
      "pae_id": null,
      "pae_name": null
    }
  }
}
```

**Errores:**
- `401 Unauthorized` - Credenciales inválidas
- `400 Bad Request` - Datos faltantes

---

### POST /auth/logout

Cerrar sesión (invalidar token).

**Headers:**
```http
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Sesión cerrada exitosamente"
}
```

---

### GET /auth/me

Obtener información del usuario autenticado.

**Headers:**
```http
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "username": "admin",
    "full_name": "Administrador del Sistema",
    "email": "admin@pae.com",
    "role_id": 1,
    "role_name": "Super Admin",
    "pae_id": null
  }
}
```

---

## 👥 Usuarios

### GET /users

Listar todos los usuarios.

**Headers:**
```http
Authorization: Bearer {token}
```

**Query Parameters:**
- `pae_id` (opcional) - Filtrar por PAE específico

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "username": "admin",
      "full_name": "Administrador del Sistema",
      "email": "admin@pae.com",
      "address": "Calle 123 #45-67",
      "phone": "3001234567",
      "role_id": 1,
      "role_name": "Super Admin",
      "pae_id": null,
      "pae_name": null,
      "is_active": 1,
      "created_at": "2026-01-15 10:00:00"
    }
  ]
}
```

---

### GET /users/{id}

Obtener un usuario específico.

**Headers:**
```http
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "username": "admin",
    "full_name": "Administrador del Sistema",
    "email": "admin@pae.com",
    "address": "Calle 123 #45-67",
    "phone": "3001234567",
    "role_id": 1,
    "role_name": "Super Admin",
    "pae_id": null,
    "pae_name": null,
    "is_active": 1,
    "created_at": "2026-01-15 10:00:00"
  }
}
```

**Errores:**
- `404 Not Found` - Usuario no encontrado

---

### POST /users

Crear un nuevo usuario.

**Headers:**
```http
Authorization: Bearer {token}
Content-Type: application/json
```

**Request:**
```json
{
  "username": "jperez",
  "password": "password123",
  "full_name": "Juan Pérez",
  "email": "jperez@example.com",
  "address": "Carrera 10 #20-30",
  "phone": "3009876543",
  "role_id": 2,
  "pae_id": 1,
  "is_active": 1
}
```

**Campos Requeridos:**
- `username` (string, único)
- `password` (string, mínimo 6 caracteres)
- `full_name` (string)
- `email` (string, formato email válido)
- `role_id` (integer)

**Campos Opcionales:**
- `address` (string)
- `phone` (string)
- `pae_id` (integer, null para Super Admin)
- `is_active` (boolean, default: 1)

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Usuario creado exitosamente",
  "data": {
    "id": 5,
    "username": "jperez"
  }
}
```

**Errores:**
- `400 Bad Request` - Datos inválidos o faltantes
- `409 Conflict` - Username o email ya existe

---

### PUT /users/{id}

Actualizar un usuario existente.

**Headers:**
```http
Authorization: Bearer {token}
Content-Type: application/json
```

**Request:**
```json
{
  "full_name": "Juan Carlos Pérez",
  "email": "jcperez@example.com",
  "address": "Carrera 15 #25-35",
  "phone": "3009876543",
  "role_id": 2,
  "pae_id": 1,
  "is_active": 1,
  "password": "newpassword123"
}
```

**Notas:**
- Todos los campos son opcionales
- `password` solo se actualiza si se envía
- `username` no se puede modificar

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Usuario actualizado exitosamente"
}
```

**Errores:**
- `404 Not Found` - Usuario no encontrado
- `400 Bad Request` - Datos inválidos

---

### DELETE /users/{id}

Eliminar un usuario.

**Headers:**
```http
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Usuario eliminado exitosamente"
}
```

**Errores:**
- `404 Not Found` - Usuario no encontrado
- `403 Forbidden` - No se puede eliminar el propio usuario

---

## 🎭 Roles

### GET /roles

Listar todos los roles disponibles.

**Headers:**
```http
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Super Admin",
      "description": "Acceso total al sistema"
    },
    {
      "id": 2,
      "name": "Admin",
      "description": "Administrador de PAE"
    }
  ]
}
```

---

## 🏢 PAE (Entidades)

### GET /tenants

Listar todos los programas PAE.

**Headers:**
```http
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "PAE Bogotá",
      "entity_name": "Secretaría de Educación Distrital",
      "operator_name": "Operador ABC S.A.S.",
      "is_active": 1
    }
  ]
}
```

---

## 👶 Beneficiarios

### GET /beneficiarios

Listar todos los beneficiarios del programa actual.

**Query Parameters:**
- `search` (opcional) - Buscar por nombre o documento
- `school_id` (opcional) - Filtrar por colegio
- `grade` (opcional) - Filtrar por grado

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 10,
      "full_name": "PEDRO PÉREZ",
      "document_number": "12345678",
      "school_name": "COLEGIO DISTRITAL",
      "grade": "5",
      "status": "ACTIVO"
    }
  ]
}
```

---

## 🍎 Cocina - Ítems

### GET /items

Listar todos los insumos/ingredientes disponibles.

**Query Parameters:**
- `food_group_id` (opcional) - Filtrar por categoría

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 5,
      "name": "ARROZ BLANCO",
      "food_group_name": "Cereales",
      "unit_abbreviation": "kg",
      "calories": 350.00
    }
  ]
}
```

---

## 🍲 Cocina - Recetas

### GET /recipes

Listar el recetario maestro.

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "ARROZ CON POLLO TIPO A",
      "meal_type": "ALMUERZO",
      "total_calories": 450.50
    }
  ]
}
```

---

## 📅 Minutas y Ciclos

### GET /cycle-templates

Listar plantillas maestras de 20 días.

---

### POST /menu-cycles/generate

Generar un ciclo completo a partir de una plantilla.

**Request:**
```json
{
  "name": "Ciclo Marzo 2026",
  "start_date": "2026-03-02",
  "template_id": 1
}
```

---

---

## 💰 Finanzas

### GET /terceros
Listar proveedores, empleados y contratistas.

---

### GET /presupuesto
Obtener el plan de presupuesto por rubros y centros de costo.

---

### GET /movimientos
Listar egresos y ejecución presupuestal.

---

### POST /movimientos
Registrar un nuevo gasto (soporta archivos vía `multipart/form-data`).

---

### GET /traslados
Listar traslados internos de recursos.

---

## 📊 Códigos de Estado HTTP

| Código | Significado | Uso |
|--------|-------------|-----|
| `200` | OK | Operación exitosa |
| `201` | Created | Recurso creado exitosamente |
| `400` | Bad Request | Datos inválidos o faltantes |
| `401` | Unauthorized | Token inválido o expirado |
| `403` | Forbidden | Sin permisos para la operación |
| `404` | Not Found | Recurso no encontrado |
| `409` | Conflict | Conflicto (ej: username duplicado) |
| `500` | Internal Server Error | Error del servidor |

---

## ⚠️ Manejo de Errores

Todos los errores siguen el mismo formato:

```json
{
  "success": false,
  "message": "Descripción del error"
}
```

---

**Última Actualización:** 13 de Febrero de 2026, 18:00 PM
