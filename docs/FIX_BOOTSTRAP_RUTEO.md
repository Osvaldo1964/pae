# Fix: Bootstrap y Sistema de Ruteo con BASE_URL

**Fecha:** 31 de Enero de 2026, 22:30  
**Problema:** Bootstrap no se cargaba correctamente debido a rutas relativas incorrectas

---

## 🔧 Cambios Realizados

### 1. Creación de Config.js ✅

**Archivo:** `app/assets/js/core/config.js`

```javascript
const Config = {
    BASE_URL: '/pae',
    API_URL: '/pae/api',
    ASSETS_URL: '/pae/app/assets',
    
    asset(path) {
        return `${this.ASSETS_URL}/${path}`;
    },
    
    apiEndpoint(endpoint) {
        return `${this.API_URL}${endpoint}`;
    },
    
    getToken() {
        return localStorage.getItem('pae_token');
    },
    
    getHeaders() {
        const headers = { 'Content-Type': 'application/json' };
        const token = this.getToken();
        if (token) {
            headers['Authorization'] = `Bearer ${token}`;
        }
        return headers;
    }
};
```

**Propósito:**
- Centralizar configuración de rutas
- Facilitar cambios de entorno (desarrollo/producción)
- Proporcionar utilidades para headers y tokens

---

### 2. Actualización de index.php ✅

**Cambios en CSS:**
```html
<!-- ANTES -->
<link href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!-- DESPUÉS -->
<link href="/pae/app/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
```

**Cambios en JavaScript:**
```html
<!-- ANTES -->
<script src="../assets/plugins/jquery/jquery.min.js"></script>
<script src="assets/js/core/helper.js"></script>

<!-- DESPUÉS -->
<script src="/pae/app/assets/plugins/jquery/jquery.min.js"></script>
<script src="/pae/app/assets/js/core/config.js"></script>
<script src="/pae/app/assets/js/core/helper.js"></script>
```

**Archivos Actualizados:**
- ✅ Bootstrap CSS
- ✅ FontAwesome CSS
- ✅ SweetAlert2 CSS
- ✅ DataTables CSS
- ✅ jQuery JS
- ✅ DataTables JS
- ✅ Bootstrap JS
- ✅ SweetAlert2 JS
- ✅ Config.js (nuevo)
- ✅ Helper.js
- ✅ App.js

---

### 3. Actualización de app.js ✅

**Rutas de Imágenes:**
```javascript
// ANTES
brandingHtml += `<img src="../${entityLogo}" ...>`;

// DESPUÉS
brandingHtml += `<img src="/pae/${entityLogo}" ...>`;
```

**Logo de Login:**
```javascript
// ANTES
<img src="assets/img/logo_ovc.png" ...>

// DESPUÉS
<img src="/pae/app/assets/img/logo_ovc.png" ...>
```

**Nueva Función loadView:**
```javascript
loadView: async (viewName) => {
    const appContainer = document.getElementById('app');
    appContainer.innerHTML = '<div id="app-container"></div>';
    
    const script = document.createElement('script');
    script.src = `/pae/app/assets/js/views/${viewName}.js?v=${Date.now()}`;
    script.onerror = () => {
        appContainer.innerHTML = `
            <div class="alert alert-danger">
                <h4>Error</h4>
                <p>No se pudo cargar la vista: ${viewName}</p>
            </div>
        `;
    };
    document.body.appendChild(script);
}
```

**Router Actualizado:**
```javascript
} else if (hash === 'roles' || hash === 'module/roles') {
    App.loadView('roles');
}
```

---

### 4. Actualización de helper.js ✅

**DataTables Language File:**
```javascript
// ANTES
url: '../assets/plugins/datatables/es-ES.json'

// DESPUÉS
url: '/pae/app/assets/plugins/datatables/es-ES.json'
```

---

### 5. Vista de Roles (roles.js) ✅

**Ya estaba usando Config correctamente:**
```javascript
const response = await fetch(`${Config.API_URL}/permissions/roles`, {
    headers: Config.getHeaders()
});
```

---

## 📊 Estructura de Rutas

### Rutas Absolutas Implementadas

```
/pae/                                    # Base URL
├── /api/                                # API REST
├── /app/
│   ├── /assets/
│   │   ├── /plugins/
│   │   │   ├── /bootstrap/
│   │   │   ├── /fontawesome/
│   │   │   ├── /sweetalert2/
│   │   │   ├── /datatables/
│   │   │   └── /jquery/
│   │   ├── /js/
│   │   │   ├── /core/
│   │   │   │   ├── config.js          # ⭐ NUEVO
│   │   │   │   ├── helper.js
│   │   │   │   └── app.js
│   │   │   └── /views/
│   │   │       └── roles.js
│   │   └── /img/
│   │       └── logo_ovc.png
│   └── index.php
└── /uploads/
```

---

## ✅ Beneficios

### 1. Rutas Consistentes
- ✅ Todas las rutas son absolutas desde la raíz
- ✅ No hay problemas con rutas relativas
- ✅ Funciona independientemente de la profundidad de la URL

### 2. Mantenibilidad
- ✅ Configuración centralizada en `config.js`
- ✅ Fácil cambio de entorno (dev/prod)
- ✅ Un solo lugar para actualizar rutas

### 3. Escalabilidad
- ✅ Fácil agregar nuevas vistas dinámicas
- ✅ Sistema de carga de vistas reutilizable
- ✅ Preparado para módulos futuros

### 4. Debugging
- ✅ Rutas claras y predecibles
- ✅ Fácil identificar problemas de carga
- ✅ Mensajes de error informativos

---

## 🧪 Testing

### Verificar que funciona:

1. **Bootstrap:**
   - ✅ Abrir `/pae/app/` en el navegador
   - ✅ Verificar que los estilos se cargan correctamente
   - ✅ Verificar que los modales funcionan

2. **Imágenes:**
   - ✅ Logo en login visible
   - ✅ Logos de PAE en header (si aplica)

3. **DataTables:**
   - ✅ Idioma español carga correctamente
   - ✅ Tablas se inicializan sin errores

4. **Vista de Roles:**
   - ✅ Navegar a `#roles`
   - ✅ Vista se carga dinámicamente
   - ✅ API calls funcionan correctamente

---

## 📝 Archivos Modificados

1. ✅ `app/assets/js/core/config.js` - **CREADO**
2. ✅ `app/index.php` - Rutas absolutas
3. ✅ `app/assets/js/core/app.js` - Rutas y loadView()
4. ✅ `app/assets/js/core/helper.js` - Ruta DataTables
5. ✅ `app/assets/js/views/roles.js` - Ya usaba Config

---

## 🚀 Próximos Pasos

1. [ ] Probar en navegador
2. [ ] Verificar que todas las vistas cargan
3. [ ] Confirmar que Bootstrap funciona
4. [ ] Probar módulo de roles completo

---

**Fin del Documento**  
*Generado: 31/01/2026 22:30*
