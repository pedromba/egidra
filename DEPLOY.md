# Plan de Despliegue — EGIDRA en VPS

**Servidor:** Canal+Bioko-Server  
**IP:** 37.187.226.223  
**Puerto SSH:** 2222  
**Usuario SSH:** sarah  
**Alias SSH:** `egidra-vps`  
**SO servidor:** Ubuntu 24.04  
**Repositorio:** https://github.com/InoRooney/egidra

---

## Estado actual

> Última actualización: Tarea #1 completada — entorno verificado. Configurando acceso SSH por clave para continuar.

---

## Tareas

### ✅ Completadas
- **#1 — Verificar entorno del VPS**
  - PHP: NO instalado
  - Apache: NO instalado
  - MySQL: NO instalado
  - SO: Ubuntu 24.04
  - SSH alias configurado: `egidra-vps` (puerto 2222)
- **Configurar autenticación SSH por clave** ✓
- **#2 — Instalar stack LAMP**
  - Apache 2.4.58 ✓
  - PHP 8.3.6 ✓
  - MySQL 8.0.45 ✓
- **#3 — Crear BD y usuario MySQL**
  - BD `egidra` con `utf8mb4_unicode_ci` ✓
  - Usuario `egidra_user` con privilegios ✓

### 🔄 En curso
- **#4 — Desplegar código desde GitHub**

### ⏳ Pendientes (en orden)

| # | Tarea | Bloqueada por |
|---|-------|---------------|
| 2 | Instalar stack LAMP (Apache, PHP 8.3, MySQL) | #1 |
| 3 | Crear BD y usuario MySQL en producción | #2 |
| 4 | Desplegar código desde GitHub | #2 |
| 5 | Configurar credenciales de BD (config/conexion.php) | #3, #4 |
| 6 | Importar esquema SQL (config/egidra_copia.sql) | #5 |
| 7 | Configurar Virtual Host de Apache | #4 |
| 8 | Configurar permisos de archivos y directorios | #4 |
| 9 | Configurar SMTP para producción (config/mailer.php) | #4 |
| 10 | Verificar seguridad y cabeceras HTTP | #7, #8 |
| 11 | Verificar despliegue completo (web + admin + email) | #6, #9, #10 |

---

## Notas técnicas

### Stack a instalar (Tarea #2)
```bash
sudo apt update && sudo apt install -y \
  apache2 \
  php8.3 php8.3-cli php8.3-mysql php8.3-mbstring php8.3-curl php8.3-xml \
  mysql-server \
  libapache2-mod-php8.3
```

### Base de datos (Tarea #3)
- Nombre BD: `egidra`
- Usuario sugerido: `egidra_user`
- Contraseña: definir en este paso
- Archivo schema: `config/egidra_copia.sql`

### Virtual Host Apache (Tarea #7)
- DocumentRoot: `/var/www/html/egidra`
- Requiere: `mod_rewrite` habilitado, `AllowOverride All`

### Permisos (Tarea #8)
```bash
sudo chown -R www-data:www-data /var/www/html/egidra
sudo find /var/www/html/egidra -type d -exec chmod 755 {} \;
sudo find /var/www/html/egidra -type f -exec chmod 644 {} \;
sudo chmod -R 775 /var/www/html/egidra/img
```

---

## Cómo retomar este plan

Si se pierde el contexto de la sesión, abrir este archivo DEPLOY.md.  
Contiene el estado exacto del despliegue y los comandos necesarios para cada tarea.
