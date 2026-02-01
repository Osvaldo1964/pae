# PAE Control WebApp - Documentación General

## 📋 Información del Proyecto

**Nombre:** Sistema de Información para la Gestión del Programa de Alimentación Escolar (PAE)  
**Versión:** 1.3.0  
**Desarrollador por:** OVCSYSTEMS S.A.S.  
**Estado Actual:** Fase 1 Completada (Cimentación y Administración)

---

## 🏗️ Pilares del Sistema

### 1. Gestión Multitenancy
El sistema está diseñado para que cada Programa PAE (Entidad Territorial/Operador) trabaje en su propio "reino". 
- **Aislamiento Total:** Los usuarios, colegios y sedes creados por un programa son totalmente invisibles para los demás programas.
- **Seguridad por Token:** El `pae_id` está embebido en el JWT y se valida en cada consulta SQL.

### 2. Estandarización de Datos
Para garantizar la calidad de la información, el sistema aplica reglas automáticas de estilo:
- **NOMBRES:** Siempre se transforman a MAYÚSCULAS (Colegios, Rectores, Usuarios).
- **EMAILS:** Siempre se transforman a minúsculas.
- **DIRECCIONES:** Permiten formato libre.

### 3. Interfaz de Usuario Moderna
- **DataTables:** En todos los listados para búsqueda rápida y ordenamiento.
- **Modales Segregados:** Las tareas de configuración (como permisos) se manejan en ventanas emergentes especializadas para no perder el contexto de trabajo.

---

## 📂 Módulos Listos para Operar

- **Usuarios:** Gestión de acceso del personal.
- **Entorno:** Catálogo de Colegios, Sedes y Proveedores.
- **Roles:** Matriz de permisos granular (Leer, Crear, Editar, Borrar).
- **Configuración PAE:** (Solo Super Admin) Gestión de operadores y logos.

---

**Última Actualización:** 01 de Febrero de 2026
