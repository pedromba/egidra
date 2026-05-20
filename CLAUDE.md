# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Descripción del proyecto

EGIDRA es un CMS personalizado en PHP puro para una empresa de servicios industriales. Tiene una web pública y un panel de administración con control de roles. No usa ningún framework PHP (ni Laravel, ni Symfony).

## Stack tecnológico

- **Backend**: PHP vanilla (sin framework), mysqli OO
- **Base de datos**: MySQL/MariaDB 8.0+, codificación `utf8mb4_unicode_ci` obligatoria
- **Frontend**: Bootstrap 5.3.2, CSS vanilla, JavaScript ES6+ vanilla (sin jQuery, sin React/Vue)
- **Email**: PHPMailer 6.10 (instalado en `libs/vendor/`, configurado en `config/mailer.php`)
- **Sin sistema de build**: no webpack, no vite, no npm en raíz
- **Sin suite de tests**: no PHPUnit, no Jest

## Arquitectura y estructura clave

```
config/         → Carga central: init.php detecta entorno y define constantes de ruta
include/        → Partials del frontend (header.php, footer.php)
admin/          → Panel de administración
  super/        → Dashboard de super admin (CRUD completo)
  editor/       → Dashboard de editor (estructura existe, no completamente implementado)
resources/      → CSS y JS organizados por página
img/            → Imágenes cargadas desde rutas relativas almacenadas en BD
libs/vendor/    → Dependencias Composer (PHPMailer)
```

## Sistema de rutas

`config/init.php` calcula `RUTA_BASE` automáticamente desde `$_SERVER`. Soporta subdirectorio (`/egidra/`) y subdominio (`admin.egidra.com`). Al tocar rutas o URLs, usa siempre las constantes `RUTA_WEB`, `RUTA_ADMIN`, `RUTA_IMG` — nunca rutas hardcodeadas.

## Credenciales y entorno

Las credenciales van directamente en los archivos de config (no hay `.env`):
- Base de datos: `config/conexion.php`
- SMTP/email: `config/mailer.php`

Al editar estos archivos en producción, cambiar la configuración de Gmail a la del servidor corporativo (hay comentarios indicando dónde).

La constante `ENTORNO` se define automáticamente: `'local'` si `HOST === 'localhost'`, `'produccion'` en otro caso.

## Roles de administración

- **Super**: acceso CRUD completo a todos los módulos + gestión de usuarios
- **Editor**: acceso limitado (la estructura existe en `admin/editor/` pero no está completamente implementado)

El guard de sesión de super admin está en `admin/super/include/auth.php`: verifica el rol y aplica timeout de 2 horas por inactividad.

## Patrones de seguridad — seguir siempre

- Usar **prepared statements con parámetros enlazados** (mysqli) en todas las consultas
- Escapar salidas HTML con `htmlspecialchars()` antes de renderizar datos de BD
- Autenticación con `password_hash()` / `password_verify()` (nunca MD5/SHA1)
- Las directivas `.htaccess` bloquean acceso directo a `/include/` y archivos sensibles

## Registro de actividad

Llamar `registrar_log($conexion, $usuario_id, $accion, $detalle)` manualmente después de cada operación de crear/editar/eliminar. No es automático.

## Imágenes

Las rutas se almacenan como relativas en BD y se renderizan con el prefijo `RUTA_IMG`. Ejemplo: si en BD está `proyectos/foto.jpg`, en el template se usa `RUTA_IMG . $fila['foto']`.

## Primera sesión de usuario admin

Los usuarios nuevos tienen `primera_sesion = 1` en BD. Al hacer login, se muestra un modal que fuerza el cambio de contraseña antes de poder acceder al panel.

## Convenciones de código

- PHP: funciones auxiliares globales definidas en `config/` (ej. `registrar_log()`, `crearMailer()`)
- JS: variables globales como `BASE_URL` se inyectan via PHP al cargar la página
- CSS: variables de tema en `:root` (`--primary-color: #ffc107`, `--dark-color: #1a1a1a`)
- Comentarios de sección en PHP: `// ─── Nombre de sección ───` (separadores ASCII)
