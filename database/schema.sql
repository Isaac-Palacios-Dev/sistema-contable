-- Esquema del Sistema Contable
-- Importar con: mysql -u root -p < database/schema.sql
-- (o desde phpMyAdmin / HeidiSQL de Laragon)

CREATE DATABASE IF NOT EXISTS sistema_contable
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE sistema_contable;

-- ---------------------------------------------------------------------------
-- Usuarios del sistema
-- ---------------------------------------------------------------------------
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(150) NOT NULL UNIQUE,
    clave VARCHAR(255) NOT NULL,
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------------
-- Catálogo de cuentas contables
-- ---------------------------------------------------------------------------
CREATE TABLE cuentas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(150) NOT NULL,
    tipo ENUM('activo', 'pasivo', 'capital', 'ingreso', 'gasto') NOT NULL,
    naturaleza ENUM('deudora', 'acreedora') NOT NULL,
    activa TINYINT(1) NOT NULL DEFAULT 1,
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------------
-- Asientos contables (cabecera) y sus líneas (detalle, partida doble)
-- ---------------------------------------------------------------------------
CREATE TABLE asientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero INT NOT NULL,
    fecha DATE NOT NULL,
    concepto VARCHAR(255) NOT NULL,
    usuario_id INT NOT NULL,
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_asientos_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB;

CREATE TABLE asiento_detalles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    asiento_id INT NOT NULL,
    cuenta_id INT NOT NULL,
    debe DECIMAL(12, 2) NOT NULL DEFAULT 0,
    haber DECIMAL(12, 2) NOT NULL DEFAULT 0,
    CONSTRAINT fk_detalle_asiento FOREIGN KEY (asiento_id) REFERENCES asientos(id) ON DELETE CASCADE,
    CONSTRAINT fk_detalle_cuenta FOREIGN KEY (cuenta_id) REFERENCES cuentas(id)
) ENGINE=InnoDB;

CREATE INDEX idx_detalle_cuenta ON asiento_detalles(cuenta_id);
CREATE INDEX idx_asientos_fecha ON asientos(fecha);

-- ---------------------------------------------------------------------------
-- Datos iniciales
-- ---------------------------------------------------------------------------

-- Usuario admin: correo admin@contable.local / clave: Admin123*
-- (hash bcrypt generado con password_hash de PHP)
INSERT INTO usuarios (nombre, correo, clave) VALUES
    ('Administrador', 'admin@contable.local', '$2y$10$kQBmFL0YF0ddcupiNl0yy.kLs4lMIDWk6DXH0E7kcOA/8conW1t86');

-- Catálogo de cuentas típico de una PyME
INSERT INTO cuentas (codigo, nombre, tipo, naturaleza) VALUES
    ('1101', 'Caja', 'activo', 'deudora'),
    ('1102', 'Bancos', 'activo', 'deudora'),
    ('1103', 'Cuentas por Cobrar', 'activo', 'deudora'),
    ('1104', 'Inventario', 'activo', 'deudora'),
    ('1201', 'Mobiliario y Equipo', 'activo', 'deudora'),
    ('2101', 'Cuentas por Pagar', 'pasivo', 'acreedora'),
    ('2102', 'Préstamos por Pagar', 'pasivo', 'acreedora'),
    ('3101', 'Capital Social', 'capital', 'acreedora'),
    ('3102', 'Utilidades Retenidas', 'capital', 'acreedora'),
    ('4101', 'Ventas', 'ingreso', 'acreedora'),
    ('4102', 'Ingresos por Servicios', 'ingreso', 'acreedora'),
    ('5101', 'Sueldos y Salarios', 'gasto', 'deudora'),
    ('5102', 'Alquiler', 'gasto', 'deudora'),
    ('5103', 'Servicios Básicos', 'gasto', 'deudora'),
    ('5104', 'Depreciación', 'gasto', 'deudora');
