# EGIDRA — Guía de sesión de trabajo

> Última actualización: 2026-04-28  
> Proyecto: Panel de administración EGIDRA (`c:\laragon\www\egidra.com`)  
> Stack: PHP 8.1 · MySQLi · JavaScript (fetch) · Bootstrap 5.3.2

---

## ✅ Tareas completadas en esta sesión

### Autenticación y sesiones
- `config/conexion.php` — eliminado el `echo` de debug, corregido el catch (era `PDOException` con MySQLi), añadido `set_charset('utf8mb4')`
- `admin/api/login.php` — reescrito: `session_set_cookie_params` con 30 días si "Recordarme" está activo, `session_regenerate_id(true)` tras login, redirect por rol devuelto en JSON
- `admin/api/logout.php` — creado: destruye sesión, borra cookie, redirige al login
- `admin/index.php` — redirige automáticamente si ya hay sesión activa
- `admin/recursos/js/login.js` — reescrito: `fetch` real al API, manejo de errores, "Recordarme" guarda email en `localStorage`
- `admin/editor/include/auth.php` — implementado: comprueba `$_SESSION['rol'] === 'Editor'`, destruye sesión y redirige si falla
- `admin/super/include/auth.php` — implementado: comprueba `$_SESSION['rol'] === 'Super'`
- Headers de ambos módulos actualizados para mostrar `$_SESSION['nombre']` y `$_SESSION['email']`; logout apunta a `admin/api/logout.php`

### Base de datos
- `config/egidra.sql` — añadidos los INSERTs de los dos usuarios iniciales con contraseña `11223344` hasheada con `password_hash`
- Corregida columna `contrasena` → `contrasena_hash` en la query de login
- Corregidos los valores del ENUM: `'Super'` y `'Editor'` (con mayúscula inicial) en `auth.php` y `login.php`

### Dashboard dinámico (fetch + APIs)
**Super Admin** — APIs en `admin/super/api/dashboard/`:
- `kpis.php` — mensajes sin leer, mensajes hoy, proyectos, clientes, usuarios
- `mensajes_recientes.php` — últimos 4 mensajes
- `actividad_reciente.php` — últimos 5 logs con JOIN a usuarios

**Editor** — APIs en `admin/editor/api/dashboard/`:
- `kpis.php` — mensajes sin leer, mensajes hoy, proyectos, clientes, servicios activos
- `mensajes_recientes.php` — últimos 4 mensajes

JS en `recursos/js/dashboard/dashboard.js` (ambos módulos): carga KPIs, tabla de mensajes, feed de actividad. Incluye escape XSS, tiempo relativo y fallback `—` si falla el fetch.

### Reorganización de recursos (CSS y JS)
Todos los archivos CSS y JS movidos desde las carpetas de sección al nuevo esquema dentro de `recursos/`:

```
recursos/
├── css/
│   ├── base/          ← base.css, components.css, super.css
│   ├── dashboard/     ← dashboard.css
│   ├── mensajes/      ← mensajes.css
│   ├── proyectos/     ← proyectos.css
│   └── …(una carpeta por sección)
└── js/
    ├── app/           ← app.js
    ├── dashboard/     ← dashboard.js
    ├── mensajes/      ← mensajes.js
    └── …(una carpeta por sección)
```

Todos los `index.php` de ambos módulos actualizados con las nuevas rutas.

### Módulo Mensajes dinámico
**APIs** (en `api/mensajes/` de cada módulo):
- `listar.php` — todos los mensajes ordenados por fecha DESC
- `leer.php?id=X` — cuerpo completo + `UPDATE leido=1, fecha_lectura=NOW()`
- `eliminar.php` (POST `id`) — `DELETE FROM contacto WHERE id = ?`

**HTML** (`mensajes/index.php`): eliminado el array PHP estático; shell vacío con IDs para que JS lo rellene. Añadido `require_once '../include/auth.php'`.

**JS** (`recursos/js/mensajes/mensajes.js`): `cargarLista()`, `cargarDetalle(id)`, `eliminarMensaje()`. Escape XSS en todo dato del servidor. Punto amarillo de "no leído" desaparece al abrir.

---

## 🔲 Tareas pendientes — divididas en fases

> **Criterio de fases**: cada fase debe poder completarse en una sesión de trabajo sin dejar módulos a medias. Terminar una fase deja el proyecto en estado funcional.

---

### Fase 2 — Auth en todas las páginas + módulos de contenido principal

**Prioridad alta** — sin esto los módulos internos son accesibles sin login.

#### 2A — Añadir `auth.php` a todas las páginas de sección
Cada `index.php` dentro de cada sección debe tener como primera línea:
```php
require_once '../include/auth.php';
```
Secciones afectadas en **super**: `mensajes` ✅, `proyectos`, `clientes`, `servicios`, `usuarios`, `valores`, `equipo`, `socios`, `certificaciones`, `seguridad`, `empresa`, `logs`  
Secciones afectadas en **editor**: `mensajes` ✅, `proyectos`, `clientes`, `servicios`, `valores`, `equipo`, `socios`, `certificaciones`, `seguridad`

#### 2B — Módulo Proyectos (CRUD completo)
APIs en `api/proyectos/`:
- `listar.php` — SELECT con JOIN a clientes y categorías, filtro por categoría y destacado
- `obtener.php?id=X` — un proyecto completo
- `guardar.php` (POST) — INSERT o UPDATE según si hay `id`
- `eliminar.php` (POST `id`) — DELETE

Tabla HTML dinámica. Modal de crear/editar con: cliente, categoría, título, descripción, ubicación, año, imagen, destacado (toggle).

#### 2C — Módulo Clientes (CRUD completo)
APIs en `api/clientes/`:
- `listar.php`, `obtener.php`, `guardar.php`, `eliminar.php`

Grid de tarjetas dinámico. Modal: nombre, iniciales, sector, logo, descripción, activo.

#### 2D — Módulo Servicios (CRUD completo)
APIs en `api/servicios/`:
- `listar.php` — con JOIN a categorías
- `categorias/listar.php` — para el select del modal
- `guardar.php`, `eliminar.php`

---

### Fase 3 — Módulos exclusivos de Super Admin

#### 3A — Módulo Usuarios (CRUD completo)
APIs en `api/usuarios/`:
- `listar.php` — SELECT sin exponer `contrasena_hash`
- `guardar.php` — INSERT con `password_hash()` / UPDATE (cambio de contraseña opcional)
- `eliminar.php` — no permitir eliminar el propio usuario

Tabla dinámica. Modal: nombre, email, rol (Super/Editor), estado, contraseña (solo al crear o si se rellena al editar).

#### 3B — Módulo Empresa (formulario guardar)
APIs en `api/empresa/`:
- `obtener.php` — SELECT id=1 (singleton)
- `guardar.php` (POST) — UPDATE (siempre el registro id=1)
- `subir-logo.php` — upload de imagen (validar tipo y tamaño)

Formulario carga datos al montar la página y guarda con fetch.

#### 3C — Módulo Logs (tabla real)
APIs en `api/logs/`:
- `listar.php` — SELECT con JOIN a usuarios, paginación y filtro por acción

Tabla dinámica con filtros por tipo de acción (LOGIN, CREAR, EDITAR, ELIMINAR, SISTEMA).

---

### Fase 4 — Módulos de contenido secundario

Patrón idéntico para cada uno: `listar`, `guardar`, `eliminar`.

#### 4A — Seguridad HSE
- `api/seguridad/reglas/listar.php`, `guardar.php`, `eliminar.php`
- `api/seguridad/estadisticas/listar.php`, `guardar.php`, `eliminar.php`

#### 4B — Valores corporativos
- `api/valores/listar.php`, `guardar.php`, `eliminar.php`
- Orden arrastrable (drag & drop o botones arriba/abajo con campo `orden`)

#### 4C — Equipo
- `api/equipo/listar.php`, `guardar.php`, `eliminar.php`
- Subida de foto de perfil

#### 4D — Socios
- `api/socios/listar.php`, `guardar.php`, `eliminar.php`

#### 4E — Certificaciones
- `api/certificaciones/listar.php`, `guardar.php`, `eliminar.php`
- Badge de estado: Vigente / Vencida calculado en PHP por `fecha_vencimiento`

---

### Fase 5 — Integraciones y funcionalidades transversales

#### 5A — Formulario de contacto público → BD
El formulario en `contacto/index.php` actualmente no guarda nada.  
Crear `contacto/api/enviar.php` (POST): INSERT en `contacto`, respuesta JSON, validación server-side.

#### 5B — Responder mensajes (email)
Botón "Responder" en mensajes abre un modal con textarea.  
API `api/mensajes/responder.php`: usa `mail()` o librería PHPMailer para enviar el reply al remitente y marca `respondido=1`.

#### 5C — Log de actividad automático
Crear función helper `registrarLog($accion, $descripcion, $tabla, $id)` en un archivo include compartido.  
Llamarla en todos los `guardar.php` y `eliminar.php` de las APIs con `$_SESSION['user_id']` y la IP del cliente.

#### 5D — Subida de archivos (imágenes)
Centralizar la lógica en `config/upload.php`: validar tipo MIME, limitar tamaño, generar nombre único, guardar en `img/uploads/`.  
Usarlo en: empresa (logo), equipo (foto), socios (logo), proyectos (imagen).

#### 5E — Paginación en listas largas
Añadir `?pagina=N&limite=20` en los `listar.php` de proyectos, clientes y logs.  
JS renderiza controles de paginación bajo la tabla.

---

## 📁 Estructura de archivos clave

```
admin/
├── index.php                  ← Login (redirige si hay sesión)
├── api/
│   ├── login.php              ← POST: valida credenciales, inicia sesión
│   └── logout.php             ← GET: destruye sesión y redirige
├── super/
│   ├── index.php              ← Dashboard Super (auth + KPIs dinámicos)
│   ├── include/
│   │   ├── auth.php           ← Valida rol 'Super', redirige si falla
│   │   ├── header.php         ← Topbar con datos de sesión
│   │   └── aside.php          ← Sidebar
│   ├── api/
│   │   ├── dashboard/         ← kpis.php, mensajes_recientes.php, actividad_reciente.php
│   │   └── mensajes/          ← listar.php, leer.php, eliminar.php
│   ├── mensajes/index.php     ← Módulo mensajes (dinámico ✅)
│   ├── proyectos/index.php    ← Pendiente fase 2B
│   └── …
└── editor/
    ├── index.php              ← Dashboard Editor (auth + KPIs dinámicos)
    ├── include/auth.php       ← Valida rol 'Editor'
    ├── api/
    │   ├── dashboard/
    │   └── mensajes/
    └── mensajes/index.php     ← Módulo mensajes (dinámico ✅)

config/
├── conexion.php               ← MySQLi, utf8mb4, sin echo de debug
├── rutas.php                  ← Constantes de URL y rutas (local/producción)
└── egidra.sql                 ← Esquema + INSERTs de usuarios iniciales
```

---

## ⚠️ Notas importantes para la próxima sesión

- **Usuarios en BD**: los dos usuarios iniciales se insertan con el SQL de `config/egidra.sql`. Contraseña: `11223344`.
- **Roles**: el ENUM usa `'Super'` y `'Editor'` **con mayúscula inicial** — respetar en todo código nuevo.
- **Columna contraseña**: se llama `contrasena_hash` en la tabla `usuarios`, no `contrasena`.
- **Rutas API**: desde una página de sección (ej. `super/mensajes/`), las APIs están en `../api/mensajes/`. Desde el dashboard (`super/`), están en `api/dashboard/`.
- **Estado boolean**: todas las respuestas JSON usan `estado: true/false` como primera clave. Los booleanos de MySQL (`leido`, `activo`, etc.) se castean con `(bool)` en PHP antes de `json_encode`.
- **Escape XSS**: en todos los JS nuevos usar la función `esc()` antes de insertar datos del servidor en `innerHTML`.
- **Patrón de cada módulo nuevo**: crear primero las APIs, luego vaciar el HTML estático dejando solo el shell con IDs, luego escribir el JS de fetch.


Stack: PHP 8.1 · MySQLi · JavaScript (fetch) · Bootstrap 5.3.2

Proyecto: Panel de administración en c:\laragon\www\egidra.com

Completado
Auth completo: login con "Recordarme" (30 días), logout, session_regenerate_id, redirección por rol
Dashboard dinámico con APIs de KPIs, mensajes recientes y actividad (ambos módulos: Super y Editor)
Módulo Mensajes completamente funcional con CRUD vía fetch + escape XSS
Reorganización de recursos CSS/JS en carpetas por sección bajo recursos/
BD: esquema SQL con usuarios iniciales (contraseña 11223344), columna contrasena_hash
Pendiente por fases
Fase	Contenido
2A	Añadir auth.php a todas las páginas de sección que aún no lo tienen
2B–2D	CRUD de Proyectos, Clientes, Servicios
3A–3C	Usuarios, Empresa, Logs (solo Super Admin)
4A–4E	Seguridad HSE, Valores, Equipo, Socios, Certificaciones
5A–5E	Formulario contacto público, responder mensajes, logs automáticos, subida de archivos, paginación
Convenciones críticas a respetar
Roles: 'Super' y 'Editor' con mayúscula inicial
Columna contraseña: contrasena_hash
Respuestas JSON: siempre empiezan con estado: true/false
JS: siempre usar esc() para datos del servidor en innerHTML
Patrón de módulo: primero APIs → luego shell HTML → luego JS fetch
¿Por dónde empezamos? La Fase 2A (proteger con auth.php todas las páginas) sería lo más urgente desde el punto de vista de seguridad.