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
    iniciales   VARCHAR(5),
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
-- DATOS INICIALES — Empresa, Servicios, Clientes, Proyectos
-- ================================================================

-- Tabla empresa (singleton id=1)
INSERT INTO empresa (id, nombre, slogan, descripcion, mision, vision, anio_fundacion, email, telefono, ciudad, pais) VALUES
(
    1,
    'EGIDRA',
    'Expertos en Soluciones Industriales',
    'EGIDRA es una empresa líder en servicios industriales especializados, comprometida con los más altos estándares de calidad y seguridad en cada proyecto que ejecutamos.',
    'Proporcionar servicios industriales especializados de máxima calidad, garantizando la seguridad de nuestro equipo y la satisfacción total de nuestros clientes en cada proyecto.',
    'Ser la empresa de referencia en servicios industriales para el sector Oil & Gas, destacando por nuestra excelencia operativa y compromiso con la innovación continua.',
    2003,
    'acc.ops@egidra.com',
    '+240 666 123 456',
    'Malabo',
    'Guinea Ecuatorial'
);

-- Valores corporativos
INSERT INTO valores (titulo, descripcion, icono, orden, activo) VALUES
('Seguridad', 'La seguridad de nuestro equipo y de quienes nos rodean está por encima de cualquier objetivo. Cero accidentes es nuestro único compromiso.', 'fa-shield-halved', 1, TRUE),
('Calidad', 'Ejecutamos cada proyecto con los más altos estándares de excelencia operativa y precisión técnica.', 'fa-medal', 2, TRUE),
('Compromiso', 'Nos comprometemos totalmente con nuestros clientes, socios y equipo para lograr resultados sobresalientes.', 'fa-handshake', 3, TRUE),
('Innovación', 'Invertimos continuamente en nuevas tecnologías, métodos y capacitación para mantener nuestra ventaja competitiva.', 'fa-lightbulb', 4, TRUE),
('Trabajo en Equipo', 'Nuestro éxito se fundamenta en la colaboración, confianza y coordinación entre todos nuestros equipos.', 'fa-users', 5, TRUE);

-- Categorías de Servicios
INSERT INTO categorias_servicios (nombre, slug, descripcion_breve, icono, orden, activo) VALUES
('Buceo & Subsea', 'buceo-subsea', 'Operaciones submarinas de alta precisión en condiciones offshore exigentes.', 'fa-diving-mask', 1, TRUE),
('Acceso por Cuerda', 'acceso-cuerda', 'Trabajos en altura con técnica IRATA en superficies verticales e instalaciones críticas.', 'fa-rope', 2, TRUE),
('Logística', 'logistica', 'Gestión integral de operaciones: suministro, transporte y movilización de personal.', 'fa-truck-loading', 3, TRUE),
('Estudios Técnicos', 'estudios-tecnicos', 'Ingeniería especializada: análisis de integridad, inspección y diagnóstico técnico.', 'fa-clipboard-check', 4, TRUE);

-- Servicios específicos
INSERT INTO servicios (categoria_id, titulo, slug, descripcion_breve, icono, es_destacado, orden, activo) VALUES
(1, 'Inspección Submarina', 'inspeccion-submarina', 'Inspección visual y técnica de estructuras, tuberías y sistemas PLEM/PLET mediante buzos certificados.', 'fa-search', TRUE, 1, TRUE),
(1, 'Mantenimiento y Reparación Subsea', 'mantenimiento-subsea', 'Intervenciones correctivas en infraestructura offshore: limpieza, soldadura hiperbárica y reemplazos.', 'fa-wrench', TRUE, 2, TRUE),
(1, 'Instalación Subsea', 'instalacion-subsea', 'Posicionamiento e instalación de estructuras submarinas y trabajos de fondeo.', 'fa-anchor', FALSE, 3, TRUE),
(2, 'Inspección en Altura', 'inspeccion-altura', 'Inspección y evaluación de estructuras metálicas, chimeneas y torres de procesamiento.', 'fa-binoculars', TRUE, 1, TRUE),
(2, 'Pintura y Revestimiento', 'pintura-revestimiento', 'Aplicación de sistemas de protección anticorrosiva en superficies de difícil acceso.', 'fa-paint-roller', TRUE, 2, TRUE),
(2, 'Mantenimiento Estructural', 'mantenimiento-estructural', 'Reparaciones, refuerzos y soldadura en altura en plataformas y estructuras offshore.', 'fa-tools', FALSE, 3, TRUE),
(3, 'Suministro Offshore', 'suministro-offshore', 'Gestión de supply vessels y coordinación de cargas para plataformas en alta mar.', 'fa-ship', TRUE, 1, TRUE),
(3, 'Almacenamiento y Distribución', 'almacenamiento-distribucion', 'Gestión de almacenes técnicos y control de inventario de equipos especializados.', 'fa-boxes', FALSE, 2, TRUE),
(3, 'Movilización de Personal', 'movilizacion-personal', 'Coordinación de traslados terrestres, marítimos y aéreos a instalaciones offshore.', 'fa-helicopter', FALSE, 3, TRUE),
(4, 'Análisis de Integridad', 'analisis-integridad', 'Evaluación del estado de estructuras mediante ensayos no destructivos y análisis de fatiga.', 'fa-wave-square', TRUE, 1, TRUE),
(4, 'Estudios de Factibilidad', 'estudios-factibilidad', 'Ingeniería preliminar y viabilidad técnica de nuevas operaciones y proyectos.', 'fa-drafting-compass', FALSE, 2, TRUE);

-- Certificaciones
INSERT INTO certificaciones (nombre, organismo_emisor, descripcion, url_verificacion, anio_obtencion, fecha_vencimiento, estado, orden) VALUES
('ISO 9001:2015 - Gestión de Calidad', 'Bureau Veritas', 'Certificación de sistema de gestión de calidad conforme a estándares internacionales.', 'https://bureauveritas.com', 2015, '2026-12-31', TRUE, 1),
('ISO 14001:2015 - Gestión Ambiental', 'Bureau Veritas', 'Certificación de compromiso ambiental y sostenibilidad operativa.', 'https://bureauveritas.com', 2015, '2026-12-31', TRUE, 2),
('ISO 45001:2018 - Seguridad y Salud Laboral', 'Bureau Veritas', 'Certificación de sistema de gestión HSE conforme a normativa internacional.', 'https://bureauveritas.com', 2018, '2026-12-31', TRUE, 3),
('IMCA D 014 - Diving Contractor', 'IMCA', 'Certificación como contratista marino especializado en operaciones de buceo industrial.', 'https://imca-int.com', 2015, '2026-12-31', TRUE, 4),
('IRATA Levels I-III - Rope Access', 'IRATA', 'Certificación de técnicos especializados en acceso industrial con cuerdas en los tres niveles.', 'https://irata.org', 2015, '2026-12-31', TRUE, 5),
('DNV GL - Clasificación y Riesgos', 'DNV GL', 'Aprobación como contratista de servicios técnicos para integridad estructural.', 'https://dnv.com', 2016, '2026-12-31', TRUE, 6);

-- Clientes
INSERT INTO clientes (nombre, iniciales, sector, descripcion, activo) VALUES
('Marathon Petroleum', 'MP', 'Oil & Gas', 'Multinacional petrolera con operaciones en proyectos offshore africanos.', TRUE),
('Chevron Corporation', 'CHV', 'Oil & Gas', 'Empresa energética global con presencia en Golfo de Guinea.', TRUE),
('Trident Energy', 'TRE', 'Oil & Gas', 'Operador independiente especializado en exploración y producción.', TRUE),
('Repsol Exploración', 'REP', 'Oil & Gas', 'Compañía energética española con activos en África Occidental.', TRUE),
('Cepsa Offshore', 'CEP', 'Oil & Gas', 'Operator con operaciones en plataformas africanas.', TRUE),
('BP Exploration', 'BP', 'Oil & Gas', 'Corporación energética global con proyectos en región.', TRUE);

-- Proyectos destacados
INSERT INTO proyectos (cliente_id, categoria_id, titulo, descripcion_tecnica, ubicacion, ano_finalizacion, es_destacado, activo) VALUES
(1, 1, 'Inspección Sistema PLEM Marathon', 'Inspección visual y de espesores en sistema PLEM offshore, incluyendo inspección de ánodos y estructuras de soporte mediante buzos certificados.', 'Pta. Europa', 2023, TRUE, TRUE),
(2, 2, 'Mantenimiento Estructura Plataforma Chevron', 'Trabajos de inspección, pintura anticorrosiva y reparación de juntas en superestructura offshore mediante técnicas IRATA nivel III.', 'Región Insular', 2023, TRUE, TRUE),
(3, 1, 'Estudio Integridad PLET Subsea', 'Análisis técnico de tuberías submarinas, válvulas de cierre y sistemas de conexión con evaluación de ciclos de fatiga.', 'Bloque Central', 2023, FALSE, TRUE),
(4, 3, 'Suministro Integral Operaciones Repsol', 'Coordinación de supply vessels, gestión de almacenes técnicos y distribución de equipos a múltiples puntos de operación offshore.', 'Margen Continental', 2023, FALSE, TRUE),
(5, 2, 'Limpieza y Pintura Estructuras Cepsa', 'Trabajos de rope access para limpieza abrasiva y aplicación de revestimiento en piernas de plataforma fija.', 'Zona Sur', 2023, FALSE, TRUE),
(6, 4, 'Análisis de Riesgos Infraestructura BP', 'Estudio de viabilidad, análisis de esfuerzos y dictamen técnico para ampliación de capacidad de procesamiento.', 'Campo Principal', 2023, FALSE, TRUE);

-- Equipo (tipos/categorías)
INSERT INTO equipo (nombre, cargo, bio, orden, activo) VALUES
('Dirección Ejecutiva', 'Liderazgo Estratégico', 'Gestión estratégica y liderazgo operativo con más de 20 años de experiencia en el sector offshore.', 1, TRUE),
('Equipo de Buzos', 'Buzos Industriales Certificados', 'Profesionales certificados en buceo comercial IMCA D 014 y operaciones subsea en aguas profundas.', 2, TRUE),
('Técnicos IRATA', 'Especialistas Rope Access', 'Especialistas certificados en los tres niveles IRATA para acceso con cuerdas y trabajos en altura extrema.', 3, TRUE),
('Ingeniería Técnica', 'Ingenieros Especializados', 'Equipo de ingenieros especializados en análisis de integridad, estudios técnicos y diagnóstico.', 4, TRUE);

-- Reglas de Oro HSE
INSERT INTO reglas_oro (numero_orden, titulo, descripcion, icono, activo) VALUES
(1, 'Trabajo en Altura', 'Todo trabajo por encima de 1,8 m requiere arnés homologado, punto de anclaje certificado y permiso de trabajo en altura.', 'fa-person-falling-burst', TRUE),
(2, 'Espacios Confinados', 'El acceso a espacios confinados exige análisis de atmósfera, vigía externo y equipos de rescate preparados.', 'fa-door-closed', TRUE),
(3, 'Aislamiento de Energía', 'Procedimiento LOTO obligatorio antes de intervenir equipos. Solo puede retirarlo quien lo instaló.', 'fa-bolt', TRUE),
(4, 'Trabajo en Caliente', 'Soldadura, corte y esmerilado requieren zona despejada, extintor próximo y vigía de incendios.', 'fa-fire', TRUE),
(5, 'Sustancias Peligrosas', 'Toda manipulación de químicos requiere ficha SDS, EPP específico y kit de derrames disponible.', 'fa-biohazard', TRUE),
(6, 'EPE Obligatorio', 'Casco, gafas, guantes y calzado de seguridad obligatorios en toda zona de trabajo.', 'fa-hard-hat', TRUE),
(7, 'Conducción Segura', 'Cinturón obligatorio, velocidad según zona, prohibición de móvil al volante, descanso de 8h antes de conducir.', 'fa-car', TRUE),
(8, 'Control de Excavaciones', 'Verificación de servicios enterrados obligatoria. Entibación en zanjas mayores de 1,2 m con balizamiento perimetral.', 'fa-shovel', TRUE),
(9, 'Integridad Mecánica', 'Inspección pre-uso obligatoria de equipos. Las deficiencias quedan fuera de servicio hasta reparación.', 'fa-gears', TRUE);

-- Estadísticas HSE
INSERT INTO estadisticas_hse (valor, etiqueta, icono, orden) VALUES
('0', 'Accidentes en últimos 5 años', 'fa-shield-halved', 1),
('+2M', 'Horas-Hombre sin incidentes registrables', 'fa-clock', 2),
('6', 'Certificaciones internacionales activas', 'fa-certificate', 3),
('100%', 'Personal formado en HSE antes de operar', 'fa-graduation-cap', 4);

-- USUARIOS INICIALES  (contraseña: 11223344)
INSERT INTO usuarios (nombre, email, contrasena_hash, rol, estado) VALUES
(
    'Administrador',
    'admin@egidra.com',
    '$2y$10$Z/IW2FD9EIimpqcTWa9aY.8njRleTjUz7oGvoWFwOv9/GH5xtbYeu',
    'Super',
    'activo'
);


