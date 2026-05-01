-- ================================================================
--  EGIDRA — Esquema de base de datos (producción)
--  Codificación: utf8mb4 | Motor: InnoDB
-- ================================================================
DROP DATABASE IF EXISTS egidra;


CREATE DATABASE IF NOT EXISTS egidra
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
    
USE egidra;

-- ================================================================
-- 1. INFORMACIÓN CORPORATIVA
-- ================================================================

CREATE TABLE empresa (
    id                  TINYINT UNSIGNED    NOT NULL AUTO_INCREMENT,
    nombre              VARCHAR(150)        NOT NULL DEFAULT 'EGIDRA',
    slogan              VARCHAR(255)        DEFAULT NULL,
    descripcion         TEXT                DEFAULT NULL,
    mision              TEXT                DEFAULT NULL,
    vision              TEXT                DEFAULT NULL,
    anio_fundacion      YEAR                DEFAULT NULL,
    logo                VARCHAR(255)        DEFAULT NULL,
    logo_blanco         VARCHAR(255)        DEFAULT NULL,
    email               VARCHAR(100)        DEFAULT NULL,
    telefono            VARCHAR(30)         DEFAULT NULL,
    direccion           TEXT                DEFAULT NULL,
    ciudad              VARCHAR(100)        DEFAULT 'Malabo',
    pais                VARCHAR(100)        DEFAULT 'Guinea Ecuatorial',
    linkedin            VARCHAR(255)        DEFAULT NULL,
    facebook            VARCHAR(255)        DEFAULT NULL,
    instagram           VARCHAR(255)        DEFAULT NULL,
    fecha_actualizacion TIMESTAMP           NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE valores (
    id          INT              NOT NULL AUTO_INCREMENT,
    titulo      VARCHAR(100)     NOT NULL,
    descripcion TEXT             DEFAULT NULL,
    icono       VARCHAR(80)      DEFAULT NULL,
    orden       TINYINT UNSIGNED DEFAULT 0,
    activo      TINYINT(1)       DEFAULT 1,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- 2. EQUIPO, SOCIOS Y CERTIFICACIONES
-- ================================================================

CREATE TABLE equipo (
    id       INT              NOT NULL AUTO_INCREMENT,
    nombre   VARCHAR(150)     NOT NULL,
    iniciales VARCHAR(10)     DEFAULT NULL,
    cargo    VARCHAR(150)     DEFAULT NULL,
    bio      TEXT             DEFAULT NULL,
    foto     VARCHAR(255)     DEFAULT NULL,
    linkedin VARCHAR(255)     DEFAULT NULL,
    orden    TINYINT UNSIGNED DEFAULT 0,
    activo   TINYINT(1)       DEFAULT 1,
    PRIMARY KEY (id),
    KEY idx_equipo_orden (orden)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE socios (
    id          INT              NOT NULL AUTO_INCREMENT,
    nombre      VARCHAR(150)     NOT NULL,
    descripcion TEXT             DEFAULT NULL,
    logo        VARCHAR(255)     DEFAULT NULL,
    url_web     VARCHAR(255)     DEFAULT NULL,
    orden       TINYINT UNSIGNED DEFAULT 0,
    activo      TINYINT(1)       DEFAULT 1,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE certificaciones (
    id                INT              NOT NULL AUTO_INCREMENT,
    nombre            VARCHAR(200)     NOT NULL,
    organismo_emisor  VARCHAR(150)     DEFAULT NULL,
    descripcion       TEXT             DEFAULT NULL,
    logo              VARCHAR(255)     DEFAULT NULL,
    url_verificacion  VARCHAR(255)     DEFAULT NULL,
    anio_obtencion    YEAR             DEFAULT NULL,
    fecha_vencimiento DATE             DEFAULT NULL,
    estado            TINYINT(1)       DEFAULT 1,
    orden             TINYINT UNSIGNED DEFAULT 0,
    PRIMARY KEY (id),
    KEY idx_certificaciones_activo (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- 3. GESTIÓN DE ACCESOS Y SEGURIDAD
-- ================================================================

CREATE TABLE usuarios (
    id_usuario      INT          NOT NULL AUTO_INCREMENT,
    nombre          VARCHAR(100) NOT NULL,
    email           VARCHAR(100) NOT NULL,
    contrasena_hash VARCHAR(255) NOT NULL,
    rol             ENUM('Super','Editor')      DEFAULT 'Editor',
    estado          ENUM('activo','inactivo')   DEFAULT 'activo',
    ultimo_acceso   DATETIME     DEFAULT NULL,
    fecha_creacion  TIMESTAMP    NULL DEFAULT CURRENT_TIMESTAMP,
    primera_sesion  TINYINT(1)   NOT NULL DEFAULT 0,
    PRIMARY KEY (id_usuario),
    UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- 4. CATÁLOGO DE SERVICIOS
-- ================================================================

CREATE TABLE categorias_servicios (
    id                INT              NOT NULL AUTO_INCREMENT,
    nombre            VARCHAR(100)     NOT NULL,
    slug              VARCHAR(100)     NOT NULL,
    descripcion_breve TEXT             DEFAULT NULL,
    icono             VARCHAR(80)      DEFAULT NULL,
    orden             TINYINT UNSIGNED DEFAULT 0,
    activo            TINYINT(1)       DEFAULT 1,
    PRIMARY KEY (id),
    UNIQUE KEY slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE servicios (
    id                   INT              NOT NULL AUTO_INCREMENT,
    categoria_id         INT              DEFAULT NULL,
    titulo               VARCHAR(150)     NOT NULL,
    slug                 VARCHAR(150)     NOT NULL,
    descripcion_breve    TEXT             DEFAULT NULL,
    contenido_detallado  LONGTEXT         DEFAULT NULL,
    icono                VARCHAR(80)      DEFAULT NULL,
    url_imagen_principal VARCHAR(255)     DEFAULT NULL,
    es_destacado         TINYINT(1)       DEFAULT 0,
    orden                TINYINT UNSIGNED DEFAULT 0,
    activo               TINYINT(1)       DEFAULT 1,
    PRIMARY KEY (id),
    UNIQUE KEY slug (slug),
    KEY idx_servicios_categoria (categoria_id),
    CONSTRAINT servicios_ibfk_1 FOREIGN KEY (categoria_id) REFERENCES categorias_servicios (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- 5. CLIENTES Y PROYECTOS
-- ================================================================

CREATE TABLE clientes (
    id_cliente  INT          NOT NULL AUTO_INCREMENT,
    nombre      VARCHAR(100) NOT NULL,
    iniciales   CHAR(3)      DEFAULT NULL,
    sector      VARCHAR(100) DEFAULT NULL,
    logo        VARCHAR(255) DEFAULT NULL,
    descripcion TEXT         DEFAULT NULL,
    activo      TINYINT(1)   DEFAULT 1,
    PRIMARY KEY (id_cliente)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE proyectos (
    id_proyecto         INT          NOT NULL AUTO_INCREMENT,
    cliente_id          INT          DEFAULT NULL,
    categoria_id        INT          DEFAULT NULL,
    titulo              VARCHAR(255) NOT NULL,
    descripcion_tecnica TEXT         DEFAULT NULL,
    ubicacion           VARCHAR(150) DEFAULT NULL,
    ano_finalizacion    YEAR         DEFAULT NULL,
    imagen              VARCHAR(255) DEFAULT NULL,
    es_destacado        TINYINT(1)   DEFAULT 0,
    activo              TINYINT(1)   DEFAULT 1,
    PRIMARY KEY (id_proyecto),
    KEY idx_proyectos_cliente    (cliente_id),
    KEY idx_proyectos_categoria  (categoria_id),
    KEY idx_proyectos_destacado  (es_destacado),
    CONSTRAINT proyectos_ibfk_1 FOREIGN KEY (cliente_id)   REFERENCES clientes (id_cliente)          ON DELETE SET NULL,
    CONSTRAINT proyectos_ibfk_2 FOREIGN KEY (categoria_id) REFERENCES categorias_servicios (id)      ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- 6. SEGURIDAD HSE
-- ================================================================

CREATE TABLE reglas_oro (
    id_regla     INT              NOT NULL AUTO_INCREMENT,
    numero_orden TINYINT UNSIGNED NOT NULL,
    titulo       VARCHAR(100)     NOT NULL,
    descripcion  TEXT             DEFAULT NULL,
    icono        VARCHAR(80)      DEFAULT NULL,
    activo       TINYINT(1)       DEFAULT 1,
    PRIMARY KEY (id_regla)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE estadisticas_hse (
    id       TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    valor    VARCHAR(20)      NOT NULL,
    etiqueta VARCHAR(100)     NOT NULL,
    icono    VARCHAR(80)      DEFAULT NULL,
    orden    TINYINT UNSIGNED DEFAULT 0,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- 7. FORMULARIO DE CONTACTO
-- ================================================================

CREATE TABLE contacto (
    id            INT          NOT NULL AUTO_INCREMENT,
    nombre        VARCHAR(100) NOT NULL,
    email         VARCHAR(100) NOT NULL,
    asunto        VARCHAR(200) DEFAULT NULL,
    mensaje       TEXT         NOT NULL,
    leido         TINYINT(1)   DEFAULT 0,
    respondido    TINYINT(1)   DEFAULT 0,
    fecha_lectura DATETIME     DEFAULT NULL,
    fecha_envio   TIMESTAMP    NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_contacto_leido (leido)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- 8. AUDITORÍA Y TRAZABILIDAD
-- ================================================================

CREATE TABLE logs_actividad (
    id                 INT          NOT NULL AUTO_INCREMENT,
    usuario_id         INT          DEFAULT NULL,
    accion             VARCHAR(100) NOT NULL,
    descripcion_cambio TEXT         DEFAULT NULL,
    tabla_afectada     VARCHAR(50)  DEFAULT NULL,
    registro_id        INT          DEFAULT NULL,
    direccion_ip       VARCHAR(45)  DEFAULT NULL,
    fecha_hora         TIMESTAMP    NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_logs_usuario (usuario_id),
    KEY idx_logs_fecha   (fecha_hora),
    CONSTRAINT logs_actividad_ibfk_1 FOREIGN KEY (usuario_id) REFERENCES usuarios (id_usuario) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- DATOS INICIALES
-- ================================================================

INSERT INTO empresa (id, nombre, slogan, descripcion, mision, vision, anio_fundacion, email, telefono, ciudad, pais) VALUES
(1, 'EGIDRA', 'Expertos en Soluciones Industriales',
 'EGIDRA es una empresa líder en servicios industriales especializados, comprometida con los más altos estándares de calidad y seguridad en cada proyecto que ejecutamos.',
 'Proporcionar servicios industriales especializados de máxima calidad, garantizando la seguridad de nuestro equipo y la satisfacción total de nuestros clientes en cada proyecto.',
 'Ser la empresa de referencia en servicios industriales para el sector Oil & Gas, destacando por nuestra excelencia operativa y compromiso con la innovación continua.',
 2003, 'acc.ops@egidra.com', '+240 222 080 324', 'Malabo', 'Guinea Ecuatorial');

INSERT INTO valores (titulo, descripcion, icono, orden, activo) VALUES
('Seguridad',       'La seguridad de nuestro equipo y de quienes nos rodean está por encima de cualquier objetivo. Cero accidentes es nuestro único compromiso.', 'fa-shield-halved', 1, 1),
('Calidad',         'Ejecutamos cada proyecto con los más altos estándares de excelencia operativa y precisión técnica.',                                          'fa-medal',         5, 1),
('Compromiso',      'Nos comprometemos totalmente con nuestros clientes, socios y equipo para lograr resultados sobresalientes.',                                  'fa-handshake',     3, 1),
('Innovación',      'Invertimos continuamente en nuevas tecnologías, métodos y capacitación para mantener nuestra ventaja competitiva.',                           'fa-lightbulb',     4, 1),
('Trabajo en Equipo','Nuestro éxito se fundamenta en la colaboración, confianza y coordinación entre todos nuestros equipos.',                                     'fa-users',         2, 1);

INSERT INTO categorias_servicios (nombre, slug, descripcion_breve, icono, orden, activo) VALUES
('Buceo & Subsea',    'buceo-subsea',    'Operaciones submarinas de alta precisión en condiciones offshore exigentes.',                              'fa-solid fa-glass-water-droplet', 1, 1),
('Acceso por Cuerda', 'acceso-por-cuerda','Trabajos en altura con técnica IRATA en superficies verticales e instalaciones críticas.',               'fa-solid fa-lasso',               2, 1),
('Logística',         'logistica',       'Gestión integral de operaciones: suministro, transporte y movilización de personal.',                      'fa-truck-loading',                3, 1),
('Estudios Técnicos', 'estudios-tecnicos','Ingeniería especializada: análisis de integridad, inspección y diagnóstico técnico.',                     'fa-clipboard-check',              4, 1);

INSERT INTO servicios (categoria_id, titulo, slug, descripcion_breve, icono, es_destacado, orden, activo) VALUES
(1, 'Inspección Submarina',          'inspecci-n-submarina',       'Inspección visual y técnica de estructuras, tuberías y sistemas PLEM/PLET mediante buzos certificados.',       'fa-solid fa-glass-water-droplet', 1, 1, 1),
(1, 'Mantenimiento y Reparación Subsea','mantenimiento-subsea',    'Intervenciones correctivas en infraestructura offshore: limpieza, soldadura hiperbárica y reemplazos.',        'fa-wrench',                       1, 2, 1),
(1, 'Instalación Subsea',            'instalacion-subsea',         'Posicionamiento e instalación de estructuras submarinas y trabajos de fondeo.',                                'fa-anchor',                       0, 3, 1),
(2, 'Inspección en Altura',          'inspeccion-altura',          'Inspección y evaluación de estructuras metálicas, chimeneas y torres de procesamiento.',                       'fa-binoculars',                   1, 1, 1),
(2, 'Pintura y Revestimiento',       'pintura-revestimiento',      'Aplicación de sistemas de protección anticorrosiva en superficies de difícil acceso.',                         'fa-paint-roller',                 1, 2, 1),
(2, 'Mantenimiento Estructural',     'mantenimiento-estructural',  'Reparaciones, refuerzos y soldadura en altura en plataformas y estructuras offshore.',                         'fa-tools',                        0, 3, 1),
(3, 'Suministro Offshore',           'suministro-offshore',        'Gestión de supply vessels y coordinación de cargas para plataformas en alta mar.',                             'fa-ship',                         1, 1, 1),
(3, 'Almacenamiento y Distribución', 'almacenamiento-distribucion','Gestión de almacenes técnicos y control de inventario de equipos especializados.',                             'fa-boxes',                        0, 2, 1),
(3, 'Movilización de Personal',      'movilizacion-personal',      'Coordinación de traslados terrestres, marítimos y aéreos a instalaciones offshore.',                           'fa-helicopter',                   0, 3, 1),
(4, 'Análisis de Integridad',        'analisis-integridad',        'Evaluación del estado de estructuras mediante ensayos no destructivos y análisis de fatiga.',                  'fa-wave-square',                  1, 1, 1),
(4, 'Estudios de Factibilidad',      'estudios-factibilidad',      'Ingeniería preliminar y viabilidad técnica de nuevas operaciones y proyectos.',                                'fa-drafting-compass',             0, 2, 1);

INSERT INTO certificaciones (nombre, organismo_emisor, descripcion, url_verificacion, anio_obtencion, fecha_vencimiento, estado, orden) VALUES
('ISO 9001:2017 - Gestión de Calidad',       'Bureau Veritas', 'Certificación de sistema de gestión de calidad conforme a estándares internacionales.', 'https://bureauveritas.com', 2015, '2026-12-31', 1, 1),
('ISO 14001:2015 - Gestión Ambiental',       'Bureau Veritas', 'Certificación de compromiso ambiental y sostenibilidad operativa.',                     'https://bureauveritas.com', 2015, '2026-12-31', 1, 2),
('ISO 45001:2018 - Seguridad y Salud Laboral','Bureau Veritas','Certificación de sistema de gestión HSE conforme a normativa internacional.',           'https://bureauveritas.com', 2018, '2026-12-31', 1, 3),
('IMCA D 014 - Diving Contractor',           'IMCA',          'Certificación como contratista marino especializado en operaciones de buceo industrial.', 'https://imca-int.com',     2015, '2026-12-31', 1, 4),
('IRATA Levels I-III - Rope Access',         'IRATA',         'Certificación de técnicos especializados en acceso industrial con cuerdas en los tres niveles.','https://irata.org', 2015, '2026-12-31', 1, 5),
('DNV GL - Clasificación y Riesgos',         'DNV GL',        'Aprobación como contratista de servicios técnicos para integridad estructural.',         'https://dnv.com',          2016, '2026-12-31', 1, 6);

INSERT INTO clientes (nombre, iniciales, sector, descripcion, activo) VALUES
('Marathon Petroleum', 'MP',  'Oil & Gas', 'Multinacional petrolera con operaciones en proyectos offshore africanos.',         1),
('Chevron Corporation','CHV', 'Oil & Gas', 'Empresa energética global con presencia en Golfo de Guinea.',                     1),
('Trident Energy',     'TRE', 'Oil & Gas', 'Operador independiente especializado en exploración y producción.',                1),
('Repsol Exploración', 'REP', 'Oil & Gas', 'Compañía energética española con activos en África Occidental.',                  1),
('Cepsa Offshore',     'CEP', 'Oil & Gas', 'Operator con operaciones en plataformas africanas.',                              1);

INSERT INTO proyectos (cliente_id, categoria_id, titulo, descripcion_tecnica, ubicacion, ano_finalizacion, es_destacado, activo) VALUES
(1, 1, 'Inspección Sistema PLEM Marathon',        'Inspección visual y de espesores en sistema PLEM offshore, incluyendo inspección de ánodos y estructuras de soporte mediante buzos certificados.', 'Pta. Europa',       2012, 1, 1),
(2, 2, 'Mantenimiento Estructura Plataforma Chevron','Trabajos de inspección, pintura anticorrosiva y reparación de juntas en superestructura offshore mediante técnicas IRATA nivel III.',           'Región Insular',    2023, 1, 1),
(3, 1, 'Estudio Integridad PLET Subsea',          'Análisis técnico de tuberías submarinas, válvulas de cierre y sistemas de conexión con evaluación de ciclos de fatiga.',                         'Bloque Central',    2023, 0, 1),
(4, 3, 'Suministro Integral Operaciones Repsol',  'Coordinación de supply vessels, gestión de almacenes técnicos y distribución de equipos a múltiples puntos de operación offshore.',              'Margen Continental',2023, 1, 1),
(5, 2, 'Limpieza y Pintura Estructuras Cepsa',    'Trabajos de rope access para limpieza abrasiva y aplicación de revestimiento en piernas de plataforma fija.',                                    'Zona Sur',          2023, 1, 1),
(5, 4, 'Análisis de Riesgos Infraestructura BP',  'Estudio de viabilidad, análisis de esfuerzos y dictamen técnico para ampliación de capacidad de procesamiento.',                                 'Campo Principal',   2023, 0, 1);

INSERT INTO reglas_oro (numero_orden, titulo, descripcion, icono, activo) VALUES
(1, 'Trabajo en Altura',      'Todo trabajo por encima de 1,8 m requiere arnés homologado, punto de anclaje certificado y permiso de trabajo en altura.',         'fa-person-falling-burst', 1),
(2, 'Espacios Confinados',    'El acceso a espacios confinados exige análisis de atmósfera, vigía externo y equipos de rescate preparados.',                       'fa-door-closed',          1),
(3, 'Aislamiento de Energía', 'Procedimiento LOTO obligatorio antes de intervenir equipos. Solo puede retirarlo quien lo instaló.',                                'fa-bolt',                 1),
(4, 'Trabajo en Caliente',    'Soldadura, corte y esmerilado requieren zona despejada, extintor próximo y vigía de incendios.',                                    'fa-fire',                 1),
(5, 'Sustancias Peligrosas',  'Toda manipulación de químicos requiere ficha SDS, EPP específico y kit de derrames disponible.',                                    'fa-biohazard',            1),
(6, 'EPE Obligatorio',        'Casco, gafas, guantes y calzado de seguridad obligatorios en toda zona de trabajo.',                                                'fa-hard-hat',             1),
(7, 'Conducción Segura',      'Cinturón obligatorio, velocidad según zona, prohibición de móvil al volante, descanso de 8h antes de conducir.',                    'fa-car',                  1),
(8, 'Control de Excavaciones','Verificación de servicios enterrados obligatoria. Entibación en zanjas mayores de 1,2 m con balizamiento perimetral.',              'fa-regular fa-truck',     1),
(9, 'Integridad Mecánica',    'Inspección pre-uso obligatoria de equipos. Las deficiencias quedan fuera de servicio hasta reparación.',                            'fa-gears',                1);

INSERT INTO estadisticas_hse (valor, etiqueta, icono, orden) VALUES
('0',    'Accidentes en últimos 5 años',              'fa-shield-halved',  1),
('+2M',  'Horas-Hombre sin incidentes registrables',  'fa-clock',          2),
('6',    'Certificaciones internacionales activas',    'fa-certificate',    3),
('100%', 'Personal formado en HSE antes de operar',   'fa-graduation-cap', 4);

-- Contraseña inicial: 11223344
INSERT INTO usuarios (nombre, email, contrasena_hash, rol, estado, primera_sesion) VALUES
('Administrador', 'admin@egidra.com', '$2y$10$Z/IW2FD9EIimpqcTWa9aY.8njRleTjUz7oGvoWFwOv9/GH5xtbYeu', 'Super', 'activo', 0);
