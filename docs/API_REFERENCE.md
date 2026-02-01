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
5. [Códigos de Estado](#códigos-de-estado)
6. [Manejo de Errores](#manejo-de-errores)

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
    },
    {
      "id": 3,
      "name": "Operador",
      "description": "Operador de campo"
    },
    {
      "id": 4,
      "name": "Consulta",
      "description": "Solo lectura"
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
      "operator_nit": "900123456-7",
      "operator_address": "Calle 100 #10-20",
      "operator_phone": "6012345678",
      "operator_email": "contacto@operadorabc.com",
      "logo_path": null,
      "entity_logo_path": "/uploads/logos/entity_logo_1.png",
      "operator_logo_path": "/uploads/logos/operator_logo_1.png",
      "is_active": 1,
      "created_at": "2026-01-20 14:30:00"
    }
  ]
}
```

---

### GET /tenants/{id}

Obtener un PAE específico.

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
    "name": "PAE Bogotá",
    "entity_name": "Secretaría de Educación Distrital",
    "operator_name": "Operador ABC S.A.S.",
    "operator_nit": "900123456-7",
    "operator_address": "Calle 100 #10-20",
    "operator_phone": "6012345678",
    "operator_email": "contacto@operadorabc.com",
    "entity_logo_path": "/uploads/logos/entity_logo_1.png",
    "operator_logo_path": "/uploads/logos/operator_logo_1.png",
    "is_active": 1
  }
}
```

---

### POST /tenants

Crear un nuevo PAE.

**Headers:**
```http
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Form Data:**
```
name: "PAE Medellín"
entity_name: "Secretaría de Educación Municipal"
operator_name: "Operador XYZ Ltda"
operator_nit: "800987654-3"
operator_address: "Carrera 50 #30-40"
operator_phone: "6047654321"
operator_email: "info@operadorxyz.com"
is_active: 1
entity_logo: [archivo]
operator_logo: [archivo]
```

**Campos Requeridos:**
- `name` (string)
- `entity_name` (string)
- `operator_name` (string)

**Campos Opcionales:**
- `operator_nit` (string)
- `operator_address` (string)
- `operator_phone` (string)
- `operator_email` (string, formato email)
- `is_active` (boolean, default: 1)
- `entity_logo` (file, image/jpeg|png|gif, max 2MB)
- `operator_logo` (file, image/jpeg|png|gif, max 2MB)

**Response (201 Created):**
```json
{
  "success": true,
  "message": "PAE creado exitosamente",
  "data": {
    "id": 3,
    "name": "PAE Medellín"
  }
}
```

---

### PUT /tenants/{id}

Actualizar un PAE existente.

**Headers:**
```http
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Form Data:** (todos opcionales)
```
name: "PAE Medellín Actualizado"
entity_name: "Secretaría de Educación Municipal"
operator_name: "Operador XYZ Ltda"
operator_nit: "800987654-3"
operator_address: "Carrera 50 #30-40"
operator_phone: "6047654321"
operator_email: "info@operadorxyz.com"
is_active: 1
entity_logo: [archivo]
operator_logo: [archivo]
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "PAE actualizado exitosamente"
}
```

---

### DELETE /tenants/{id}

Eliminar un PAE.

**Headers:**
```http
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "PAE eliminado exitosamente"
}
```

**Errores:**
- `404 Not Found` - PAE no encontrado
- `409 Conflict` - PAE tiene usuarios asociados

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
  "message": "Descripción del error",
  "error": "Detalles técnicos (solo en desarrollo)"
}
```

### Ejemplos de Errores Comunes

**401 - Token Inválido:**
```json
{
  "success": false,
  "message": "Token inválido o expirado"
}
```

**400 - Datos Faltantes:**
```json
{
  "success": false,
  "message": "Campos requeridos: username, password, email"
}
```

**409 - Conflicto:**
```json
{
  "success": false,
  "message": "El username 'admin' ya está en uso"
}
```

**404 - No Encontrado:**
```json
{
  "success": false,
  "message": "Usuario no encontrado"
}
```

---

## 🔒 Seguridad

### Headers Requeridos

```http
Authorization: Bearer {token}
Content-Type: application/json
```

### Validación de Token

El token JWT debe incluir:
- `user_id` - ID del usuario
- `username` - Nombre de usuario
- `role_id` - ID del rol
- `pae_id` - ID del PAE (null para Super Admin)
- `exp` - Timestamp de expiración

### Expiración de Token

- **Duración:** 24 horas (configurable)
- **Renovación:** Requiere nuevo login

### Rate Limiting

⚠️ **Pendiente de implementar**

---

## 📝 Notas Adicionales

### Paginación

⚠️ **Pendiente de implementar**

Formato propuesto:
```
GET /users?page=1&limit=10
```

### Filtros

Algunos endpoints soportan filtros via query parameters:

```
GET /users?pae_id=1
GET /users?role_id=2
GET /users?is_active=1
```

### Ordenamiento

⚠️ **Pendiente de implementar**

Formato propuesto:
```
GET /users?sort=created_at&order=desc
```

---

## 🧪 Testing

### Herramientas Recomendadas

- **Postman** - Testing manual de API
- **Insomnia** - Alternativa a Postman
- **cURL** - Testing desde línea de comandos

### Ejemplo con cURL

**Login:**
```bash
curl -X POST http://localhost/pae/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"admin"}'
```

**Listar Usuarios:**
```bash
curl -X GET http://localhost/pae/api/users \
  -H "Authorization: Bearer {token}"
```

---

## 📞 Soporte

**Documentación:** `/docs`  
**Desarrollador:** OVCSYSTEMS S.A.S.

---

**Última Actualización:** 31 de Enero de 2026
