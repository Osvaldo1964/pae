# PAE Control WebApp - Documentación

**Sistema de Gestión Integral para Programas de Alimentación Escolar**

Versión: 1.7.0  
Última actualización: 10 de Febrero 2026

---

## 📚 Índice de Documentación

### 📖 Documentación General

| Documento | Descripción |
|-----------|-------------|
| [ESTADO_DESARROLLO.md](ESTADO_DESARROLLO.md) | Estado actual del desarrollo, módulos completados y roadmap |
| [ESTADO_SISTEMA.md](ESTADO_SISTEMA.md) | Resumen ejecutivo de módulos y funcionalidades |
| [ARQUITECTURA.md](ARQUITECTURA.md) | Arquitectura técnica del sistema (MVC, API REST, JWT) |
| [INSTALACION.md](INSTALACION.md) | Guía de instalación y configuración |
| [API_REFERENCE.md](API_REFERENCE.md) | Referencia completa de endpoints de la API |

### 🔧 Documentación de Módulos

| Módulo | Documento | Estado |
|--------|-----------|--------|
| **Almacén** | [MODULO_ALMACEN.md](MODULO_ALMACEN.md) | ✅ Completo |
| **Consumos** | [MODULO_CONSUMOS.md](MODULO_CONSUMOS.md) | ✅ Completo |
| **Cocina** | [MODULO_COCINA.md](MODULO_COCINA.md) | ✅ Completo |
| **Gestión PAE** | [MODULO_GESTION_PAE.md](MODULO_GESTION_PAE.md) | ✅ Completo |
| **Mi Equipo** | [MODULO_MI_EQUIPO.md](MODULO_MI_EQUIPO.md) | ✅ Completo |
| **Permisos** | [MODULO_PERMISOS.md](MODULO_PERMISOS.md) | ✅ Completo |

### 🎯 Documentación Específica

| Tema | Documento |
|------|-----------|
| Seguridad y Usuarios | [SEGURIDAD_USUARIOS.md](SEGURIDAD_USUARIOS.md) |
| Estructura de Menús | [ESTRUCTURA_MENU.md](ESTRUCTURA_MENU.md) |
| Plan de Entregas | [PLAN_ENTREGAS_RESOLUCION_003.md](PLAN_ENTREGAS_RESOLUCION_003.md) |
| Plugins Instalados | [PLUGINS_INSTALADOS.md](PLUGINS_INSTALADOS.md) |

---

## 🚀 Inicio Rápido

### Para Desarrolladores

1. **Instalación:**
   ```bash
   # Ver guía completa en INSTALACION.md
   git clone [repo]
   cd pae
   composer install
   npm install
   ```

2. **Configuración:**
   - Configurar base de datos en `api/config/Database.php`
   - Ejecutar migraciones SQL en orden
   - Configurar JWT secret

3. **Desarrollo:**
   - Backend: `api/controllers/`
   - Frontend: `app/assets/js/views/`
   - Ver [ARQUITECTURA.md](ARQUITECTURA.md) para detalles

### Para Usuarios

1. **Acceso al Sistema:**
   - URL: `https://tu-dominio.com/pae/`
   - Login con credenciales proporcionadas

2. **Módulos Principales:**
   - **Beneficiarios:** Gestión de estudiantes
   - **Cocina:** Ítems, recetas, minutas
   - **Almacén:** Inventario y movimientos
   - **Consumos:** Registro de entregas
   - **Compras:** Órdenes de compra

3. **Documentación de Usuario:**
   - Ver manuales específicos por módulo

---

## 📊 Características Principales

### ✅ Gestión de Beneficiarios
- Registro completo de estudiantes
- Cumplimiento Resolución 0003/2026
- Impresión de carnets con QR
- Listas de asistencia

### ✅ Módulo de Cocina
- **Ítems:** Catálogo maestro con información nutricional
- **Recetas:** Motor de cálculo automático
- **Minutas:** Generador de ciclos de 20 días
- **Tipos de Ración:** Gestión dinámica

### ✅ Almacén Profesional
- **Stock en Tiempo Real:** Alertas de existencias
- **Kardex Digital:** Historial completo de movimientos
- **Valoración:** Promedio ponderado contable
- **Costos por Ciclo:** Análisis de variación de precios
- **Auditoría:** Planillas de conteo y ajustes

### ✅ Sistema de Entregas
- **App Móvil PWA:** Escaneo de QR
- **Registro Manual:** Interfaz web
- **Anti-Fraude:** Prevención de duplicados
- **Reportes:** Planillas oficiales con firmas

### ✅ Compras y Proyecciones
- **Órdenes de Compra:** Gestión completa
- **Proyecciones:** Cálculo automático por ciclo
- **Remisiones:** Registro de entregas
- **Integración:** Conversión a entradas de almacén

---

## 🔐 Seguridad

- **JWT Authentication:** Tokens con expiración
- **Multitenancy:** Aislamiento estricto por PAE
- **Prepared Statements:** Prevención SQL injection
- **Validaciones:** Frontend y backend
- **Auditoría:** Logs de todas las operaciones

Ver [SEGURIDAD_USUARIOS.md](SEGURIDAD_USUARIOS.md) para detalles.

---

## 🛠️ Stack Tecnológico

### Backend
- **Lenguaje:** PHP 7.4+
- **Base de Datos:** MySQL 8.0+
- **Arquitectura:** MVC + API REST
- **Autenticación:** JWT

### Frontend
- **Framework:** Vanilla JavaScript (ES6+)
- **UI:** Bootstrap 5
- **Alertas:** SweetAlert2
- **Tablas:** DataTables
- **QR:** html5-qrcode

### Infraestructura
- **Servidor Web:** Apache 2.4+
- **PWA:** Service Workers para app móvil
- **PDF:** jsPDF para reportes

---

## 📈 Métricas del Sistema

| Métrica | Valor |
|---------|-------|
| Módulos Completados | 15/16 (94%) |
| Endpoints API | 80+ |
| Tablas de BD | 45+ |
| Líneas de Código Backend | ~25,000 |
| Líneas de Código Frontend | ~18,000 |
| Cobertura Normativa | 100% (Res. 0003/2026) |

---

## 🗺️ Roadmap

### ✅ Fase 1: Infraestructura (Completado)
- Sistema base MVC
- Autenticación JWT
- Multitenancy

### ✅ Fase 2: Administración (Completado)
- Usuarios y roles
- Colegios y sedes
- Beneficiarios

### ✅ Fase 3: Cocina (Completado)
- Ítems y recetas
- Minutas y ciclos
- Tipos de ración

### ✅ Fase 4: Operación (Completado)
- Almacén profesional
- Sistema de entregas
- Compras y proyecciones

### 🔄 Fase 5: Reportes (En Progreso)
- Dashboard ejecutivo
- Reportes gerenciales
- Análisis de costos

### 📅 Fase 6: Integraciones (Futuro)
- SIMAT
- Sistemas contables
- API pública

---

## 🤝 Soporte

### Documentación Técnica
- **Backend:** Ver controladores en `api/controllers/`
- **Frontend:** Ver vistas en `app/assets/js/views/`
- **Base de Datos:** Ver esquemas en `sql/`

### Contacto
- **Desarrollador:** [Información de contacto]
- **Repositorio:** [URL del repositorio]
- **Issues:** [URL de issues]

---

## 📝 Notas de Versión

### v1.7.0 (09 Feb 2026)
- ✅ Sistema de costos por ciclo (promedio ponderado)
- ✅ Trazabilidad de variación de precios
- ✅ Documentación completa actualizada

### v1.6.6 (08 Feb 2026)
- ✅ Almacén profesional con Kardex
- ✅ Herramientas de auditoría
- ✅ Búsqueda en tiempo real

### v1.6.0 (31 Ene 2026)
- ✅ Módulo de consumos con QR
- ✅ App móvil PWA
- ✅ Reportes de asistencia

Ver [ESTADO_DESARROLLO.md](ESTADO_DESARROLLO.md) para historial completo.

---

## 📄 Licencia

[Especificar licencia del proyecto]

---

## 🙏 Agradecimientos

Desarrollado para mejorar la gestión de Programas de Alimentación Escolar en Colombia, cumpliendo con la Resolución 0003 de 2026 del Ministerio de Educación Nacional.
