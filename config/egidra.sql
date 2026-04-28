-- ================================================================
--  EGIDRA — Esquema de base de datos  v2.0
--  Codificación: utf8mb4 | Motor: InnoDB
-- ================================================================
DROP DATABASE IF EXISTS egidra;

CREATE DATABASE IF NOT EXISTS egidra
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
USE egidra;

-- ================================================================
-- 1. INFORMACIÓN CORPORATIVA
--    empresa · valores · misión · visión
-- ================================================================

-- Tabla singleton (siempre id = 1).
-- Centraliza todo lo que el admin edita desde "Configuración":
-- logo, datos de contacto, misión, visión y RRSS.
CREATE TABLE empresa (
    id              TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(150)  NOT NULL DEFAULT 'EGIDRA',
    slogan          VARCHAR(255),
    descripcion     TEXT,                       -- párrafo "Sobre Nosotros"
    mision          TEXT,
    vision          TEXT,
    anio_fundacion  YEAR,
    logo            VARCHAR(255),               -- logo color (ruta relativa)
    logo_blanco     VARCHAR(255),               -- versión blanca para fondo oscuro
    email           VARCHAR(100),
    telefono        VARCHAR(30),
    direccion       TEXT,
    ciudad          VARCHAR(100)  DEFAULT 'Malabo',
    pais            VARCHAR(100)  DEFAULT 'Guinea Ecuatorial',
    linkedin        VARCHAR(255),
    facebook        VARCHAR(255),
    instagram       VARCHAR(255),
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                        ON UPDATE CURRENT_TIMESTAMP
);

-- Valores corporativos: cada fila es una tarjeta en la sección "Valores".
CREATE TABLE valores (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    titulo      VARCHAR(100) NOT NULL,
    descripcion TEXT,
    icono       VARCHAR(80),                    -- clase FA (ej: 'fa-shield-halved')
    orden       TINYINT UNSIGNED DEFAULT 0,
    activo      BOOLEAN DEFAULT TRUE
);

-- ================================================================
-- 2. EQUIPO, SOCIOS Y CERTIFICACIONES
-- ================================================================

-- Miembros del equipo que aparecen en "Sobre Nosotros".
CREATE TABLE equipo (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(150) NOT NULL,
    cargo       VARCHAR(150),
    bio         TEXT,
    foto        VARCHAR(255),
    linkedin    VARCHAR(255),
    orden       TINYINT UNSIGNED DEFAULT 0,
    activo      BOOLEAN DEFAULT TRUE
);

-- Empresas o entidades con las que EGIDRA colabora como socias.
CREATE TABLE socios (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(150) NOT NULL,
    descripcion TEXT,
    logo        VARCHAR(255),
    url_web     VARCHAR(255),
    orden       TINYINT UNSIGNED DEFAULT 0,
    activo      BOOLEAN DEFAULT TRUE
);

-- Certificaciones internacionales (IMCA, IRATA, ISO, etc.).
CREATE TABLE certificaciones (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    nombre              VARCHAR(200) NOT NULL,
    organismo_emisor    VARCHAR(150),           -- IMCA, IRATA, Bureau Veritas…
    descripcion         TEXT,
    logo                VARCHAR(255),
    url_verificacion    VARCHAR(255),
    anio_obtencion      YEAR,
    fecha_vencimiento   DATE,                   -- NULL si es indefinida
    estado              BOOLEAN DEFAULT TRUE,
    orden               TINYINT UNSIGNED DEFAULT 0
);

-- ================================================================
-- 3. GESTIÓN DE ACCESOS Y SEGURIDAD
-- ================================================================

CREATE TABLE usuarios (
    id_usuario      INT AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(100) NOT NULL,
    email           VARCHAR(100) UNIQUE NOT NULL,
    contrasena_hash VARCHAR(255) NOT NULL,
    rol             ENUM('Super','Editor') DEFAULT 'Editor',
    estado          ENUM('activo','inactivo') DEFAULT 'activo',
    ultimo_acceso   DATETIME,
    fecha_creacion  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ================================================================
-- 4. CATÁLOGO DE SERVICIOS
-- ================================================================

-- Categorías (Buceo & Subsea, Rope Access, Logística, Estudios Técnicos).
CREATE TABLE categorias_servicios (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    nombre           VARCHAR(100) NOT NULL,
    slug             VARCHAR(100) UNIQUE NOT NULL,  -- para URLs limpias
    descripcion_breve TEXT,
    icono            VARCHAR(80),
    orden            TINYINT UNSIGNED DEFAULT 0,
    activo           BOOLEAN DEFAULT TRUE
);

-- Servicios específicos dentro de cada categoría.
CREATE TABLE servicios (
    id                      INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id            INT,
    titulo                  VARCHAR(150) NOT NULL,
    slug                    VARCHAR(150) UNIQUE NOT NULL,
    descripcion_breve       TEXT,                   -- para tarjetas y previsualizaciones
    contenido_detallado     LONGTEXT,               -- texto completo de la página dedicada
    icono                   VARCHAR(80),
    url_imagen_principal    VARCHAR(255),
    es_destacado            BOOLEAN DEFAULT FALSE,
    orden                   TINYINT UNSIGNED DEFAULT 0,
    activo                  BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (categoria_id) REFERENCES categorias_servicios(id) ON DELETE SET NULL
);

-- ================================================================
-- 5. CLIENTES Y PROYECTOS (TRACK RECORD)
-- ================================================================

-- Empresas del sector Oil & Gas que han contratado a EGIDRA.
CREATE TABLE clientes (
    id_cliente  INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100) NOT NULL,
    iniciales   CHAR(3),                        -- abreviatura para el avatar (ej: 'MA')
    sector      VARCHAR(100),                   -- Oil & Gas, Energía, Naval…
    logo        VARCHAR(255),
    descripcion TEXT,
    activo      BOOLEAN DEFAULT TRUE
);

-- Trabajos realizados. Vinculado a cliente y categoría de servicio.
CREATE TABLE proyectos (
    id_proyecto         INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id          INT,
    categoria_id        INT,                    -- Buceo / Cuerda / Logística / Estudios
    titulo              VARCHAR(255) NOT NULL,
    descripcion_tecnica TEXT,
    ubicacion           VARCHAR(150),
    ano_finalizacion    YEAR,
    imagen              VARCHAR(255),
    es_destacado        BOOLEAN DEFAULT FALSE,  -- aparece en la homepage si TRUE
    activo              BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (cliente_id)   REFERENCES clientes(id_cliente)             ON DELETE SET NULL,
    FOREIGN KEY (categoria_id) REFERENCES categorias_servicios(id)         ON DELETE SET NULL
);

-- ================================================================
-- 6. SEGURIDAD HSE
-- ================================================================

-- Las 9 Reglas de Oro (gestionadas desde el CMS).
CREATE TABLE reglas_oro (
    id_regla        INT AUTO_INCREMENT PRIMARY KEY,
    numero_orden    TINYINT UNSIGNED NOT NULL,
    titulo          VARCHAR(100) NOT NULL,
    descripcion     TEXT,
    icono           VARCHAR(80),
    activo          BOOLEAN DEFAULT TRUE
);

-- Cifras HSE que se muestran dinámicamente en la sección de seguridad.
-- Ejemplos: '500+' / 'Trabajos sin incidente grave'.
CREATE TABLE estadisticas_hse (
    id       TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    valor    VARCHAR(20)  NOT NULL,             -- '500+', '0', '100%'
    etiqueta VARCHAR(100) NOT NULL,
    icono    VARCHAR(80),
    orden    TINYINT UNSIGNED DEFAULT 0
);

-- ================================================================
-- 7. MENSAJES (FORMULARIO DE CONTACTO)
-- ================================================================

CREATE TABLE contacto (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(100) NOT NULL,
    email           VARCHAR(100) NOT NULL,
    asunto          VARCHAR(200),
    mensaje         TEXT NOT NULL,
    leido           BOOLEAN DEFAULT FALSE,
    respondido      BOOLEAN DEFAULT FALSE,
    fecha_lectura   DATETIME,                   -- se rellena al marcar como leído
    fecha_envio     TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ================================================================
-- 8. AUDITORÍA Y TRAZABILIDAD
-- ================================================================

CREATE TABLE logs_actividad (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id          INT,
    accion              VARCHAR(100) NOT NULL,  -- 'LOGIN','CREAR','EDITAR','ELIMINAR','SISTEMA'
    descripcion_cambio  TEXT,
    tabla_afectada      VARCHAR(50),
    registro_id         INT,                    -- ID del registro afectado (para trazabilidad)
    direccion_ip        VARCHAR(45),
    fecha_hora          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id_usuario) ON DELETE SET NULL
);

-- ================================================================
-- ÍNDICES DE RENDIMIENTO
-- ================================================================

CREATE INDEX idx_servicios_categoria    ON servicios(categoria_id);
CREATE INDEX idx_proyectos_cliente      ON proyectos(cliente_id);
CREATE INDEX idx_proyectos_categoria    ON proyectos(categoria_id);
CREATE INDEX idx_proyectos_destacado    ON proyectos(es_destacado);
CREATE INDEX idx_logs_usuario           ON logs_actividad(usuario_id);
CREATE INDEX idx_logs_fecha             ON logs_actividad(fecha_hora);
CREATE INDEX idx_contacto_leido         ON contacto(leido);
CREATE INDEX idx_equipo_orden           ON equipo(orden);
CREATE INDEX idx_certificaciones_activo ON certificaciones(estado);


-- ================================================================
-- USUARIOS INICIALES  (contraseña: 11223344)
-- ================================================================

INSERT INTO usuarios (nombre, email, contrasena_hash, rol, estado) VALUES
(
    'Administrador',
    'admin@egidra.com',
    '$2y$10$Z/IW2FD9EIimpqcTWa9aY.8njRleTjUz7oGvoWFwOv9/GH5xtbYeu',
    'Super',
    'activo'
),
(
    'Editor',
    'editor@egidra.com',
    '$2y$10$pkokNC41.SqXpy3Sl17/N.LvJLg5Q.GczuYsMQbFm9LoLYp67rDXK',
    'Editor',
    'activo'
);