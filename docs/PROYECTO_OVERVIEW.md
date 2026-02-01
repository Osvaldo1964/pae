# PAE Control WebApp - Documentación General

## 📋 Información del Proyecto

**Nombre:** Sistema de Información para la Gestión del Programa de Alimentación Escolar (PAE)  
**Versión:** 1.0  
**Desarrollado por:** OVCSYSTEMS S.A.S.  
**Fecha de Inicio:** Enero 2026  
**Estado Actual:** En Desarrollo Activo

---

## 🎯 Objetivo del Proyecto

Desarrollar una aplicación web integral para administrar la operación del Programa de Alimentación Escolar (PAE), permitiendo:

- **Gestión Multitenancy**: Soporte para múltiples entidades territoriales y operadores
- **Control de Usuarios**: Sistema de autenticación y autorización basado en roles
- **Administración de Beneficiarios**: Registro y seguimiento de estudiantes beneficiarios
- **Gestión de Inventarios**: Control de insumos y cocina
- **Minutas y Explosión de Insumos**: Planificación de menús y cálculo de requerimientos
- **Reportes y Dashboards**: Visualización de datos y análisis de operación

---

## 🏗️ Arquitectura del Sistema

### Stack Tecnológico

**Backend:**
- PHP 8.x (Nativo)
- Arquitectura MVC ligera
- RESTful API
- JSON Web Tokens (JWT) para autenticación

**Frontend:**
- Single Page Application (SPA)
- JavaScript Vanilla ES6+
- Bootstrap 5 (Local)
- SweetAlert2 para notificaciones
- DataTables para tablas interactivas

**Base de Datos:**
- MySQL / MariaDB
- Diseño normalizado con soporte multitenancy

**Servidor:**
- Apache (XAMPP)
- Configuración local para desarrollo

---

## 📂 Estructura del Proyecto

```
/pae
├── /api                    # Backend API
│   ├── /config            # Configuración de base de datos
│   ├── /controllers       # Controladores REST
│   ├── /models            # Modelos de datos
│   ├── /middleware        # Middleware de autenticación
│   ├── /utils             # Utilidades (JWT, validaciones)
│   └── index.php          # Enrutador API
│
├── /app                    # Frontend SPA
│   ├── /assets            # Recursos del frontend
│   │   ├── /css          # Estilos personalizados
│   │   ├── /img          # Imágenes y logos
│   │   └── /js           # JavaScript
│   │       ├── /core     # Núcleo de la aplicación
│   │       └── /views    # Vistas/Módulos
│   └── index.php          # Shell de la SPA
│
├── /docs                   # Documentación del proyecto
├── /landing               # Página de aterrizaje pública
├── /sql                   # Scripts de base de datos
├── /uploads               # Archivos subidos por usuarios
├── .gitignore             # Configuración Git
├── README.md              # Documentación principal
└── index.php              # Enrutador principal

```

---

## 🔐 Seguridad

### Autenticación y Autorización

1. **JWT (JSON Web Tokens)**
   - Token generado al iniciar sesión
   - Validación en cada petición API
   - Expiración configurable
   - Clave secreta en `api/utils/JWT.php` (⚠️ CAMBIAR en producción)

2. **Control de Acceso Basado en Roles (RBAC)**
   - Roles: Super Admin, Admin, Operador, Consulta
   - Permisos granulares por módulo
   - Validación en backend y frontend

3. **Multitenancy**
   - Aislamiento de datos por entidad territorial (PAE)
   - Usuarios asignados a un PAE específico
   - Super Admin puede gestionar múltiples PAEs

### Buenas Prácticas Implementadas

- ✅ Validación de entrada en backend
- ✅ Sanitización de datos
- ✅ Prepared Statements (PDO) para prevenir SQL Injection
- ✅ Headers CORS configurados
- ✅ Manejo de errores centralizado
- ⚠️ HTTPS recomendado para producción

---

## 🗄️ Base de Datos

### Esquema Principal

**Tablas Core:**
- `users` - Usuarios del sistema
- `roles` - Roles de usuario
- `pae_programs` - Programas PAE (entidades territoriales)

**Relaciones:**
- Un usuario pertenece a un PAE
- Un usuario tiene un rol
- Un PAE puede tener múltiples usuarios

### Scripts SQL

1. `01_auth_schema.sql` - Esquema de autenticación y usuarios
2. `02_multitenant.sql` - Configuración multitenancy
3. `03_pae_details.sql` - Detalles de programas PAE

---

## 🚀 Instalación y Configuración

### Requisitos Previos

- XAMPP (Apache + MySQL + PHP 8.x)
- Navegador web moderno
- Git (opcional)

### Pasos de Instalación

1. **Clonar/Copiar el proyecto**
   ```bash
   # Copiar a C:/xampp/htdocs/pae
   ```

2. **Configurar Base de Datos**
   - Crear base de datos `db-pae`
   - Ejecutar scripts SQL en orden:
     ```sql
     -- 1. Autenticación
     source sql/01_auth_schema.sql;
     
     -- 2. Multitenancy
     source sql/02_multitenant.sql;
     
     -- 3. Detalles PAE
     source sql/03_pae_details.sql;
     ```

3. **Configurar Conexión**
   - Editar `api/config/Database.php`
   - Ajustar credenciales si es necesario (por defecto: root sin contraseña)

4. **Iniciar Servidor**
   - Iniciar Apache y MySQL desde XAMPP
   - Acceder a `http://localhost/pae/app/`

5. **Credenciales Iniciales**
   - Usuario: `admin`
   - Contraseña: `admin`

---

## 📱 Módulos del Sistema

### ✅ Módulos Completados

1. **Autenticación**
   - Login/Logout
   - Validación JWT
   - Recuperación de contraseña (pendiente implementación de email)

2. **Gestión de Usuarios**
   - CRUD completo
   - Asignación de roles
   - Campos adicionales (dirección, teléfono)
   - Interfaz mejorada con modal

3. **Gestión de Roles**
   - Listado de roles
   - Visualización de permisos (en desarrollo)

4. **Gestión de PAE (Entidades)**
   - CRUD completo
   - Información del operador
   - Logos de entidad y operador
   - Validación de datos

### 🚧 Módulos en Desarrollo

5. **Dashboard**
   - Estadísticas generales
   - Gráficos de operación
   - Indicadores clave

6. **Beneficiarios**
   - Registro de estudiantes
   - Asignación a sedes
   - Historial de beneficios

7. **Inventarios**
   - Control de insumos
   - Entradas y salidas
   - Kardex

8. **Minutas**
   - Planificación de menús
   - Explosión de insumos
   - Ciclos de menú

### 📋 Módulos Planificados

9. **Reportes**
   - Reporte de beneficiarios
   - Reporte de consumo
   - Reporte de inventarios
   - Exportación a PDF/Excel

10. **Configuración**
    - Parámetros del sistema
    - Categorías de alimentos
    - Tipos de comida

---

## 🎨 Diseño y UX

### Principios de Diseño

- **Interfaz Limpia**: Diseño minimalista y profesional
- **Responsive**: Adaptable a diferentes dispositivos
- **Accesibilidad**: Contraste adecuado y navegación clara
- **Feedback Visual**: Notificaciones y confirmaciones claras

### Componentes UI

- **Modales**: Para formularios de creación/edición
- **DataTables**: Para listados con búsqueda y paginación
- **SweetAlert2**: Para alertas y confirmaciones
- **Bootstrap 5**: Para layout y componentes base

---

## 📊 Estado del Proyecto

Ver archivo: `ESTADO_DESARROLLO.md` para detalles de avances y pendientes.

---

## 🤝 Contribución

### Flujo de Trabajo

1. Crear rama para nueva funcionalidad
2. Desarrollar y probar localmente
3. Commit con mensajes descriptivos
4. Merge a rama principal

### Convenciones de Código

**PHP:**
- PSR-12 para estilo de código
- Nombres descriptivos en inglés
- Comentarios en español

**JavaScript:**
- ES6+ features
- Modularización por vistas
- Comentarios JSDoc

**SQL:**
- Nombres de tablas en minúsculas con guiones bajos
- Campos descriptivos
- Índices en campos de búsqueda frecuente

---

## 📞 Contacto y Soporte

**Desarrollador:** OVCSYSTEMS S.A.S.  
**Email:** [Agregar email de contacto]  
**Documentación:** `/docs`

---

## 📝 Notas Importantes

⚠️ **Antes de Producción:**
- [ ] Cambiar clave secreta JWT
- [ ] Configurar HTTPS
- [ ] Cambiar credenciales de admin
- [ ] Configurar backups automáticos
- [ ] Revisar permisos de archivos
- [ ] Configurar logs de errores
- [ ] Optimizar consultas SQL
- [ ] Implementar rate limiting

---

**Última Actualización:** 31 de Enero de 2026
