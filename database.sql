-- Creación y poblamiento inicial de la base de datos “boletines”
CREATE DATABASE IF NOT EXISTS boletines CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE boletines;

/* ---------- Modalidades ---------- */
CREATE TABLE modalidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL
);

INSERT INTO modalidades (nombre) VALUES
('Modalidad Académica'),
('Modalidad Académica con Salida en Humanidades y Ciencias Sociales'),
('Modalidad Académica con Salida en Matemáticas y Tecnologías');

/* ---------- Grados ---------- */
CREATE TABLE grados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    modalidad_id INT NOT NULL,
    nombre VARCHAR(20) NOT NULL,
    CONSTRAINT fk_grado_modalidad FOREIGN KEY (modalidad_id) REFERENCES modalidades(id)
);

-- 1ro-3ro para la modalidad 1
INSERT INTO grados (modalidad_id, nombre) VALUES
(1,'1ro'),(1,'2do'),(1,'3ro');
-- 4to-6to para modalidades 2 y 3
INSERT INTO grados (modalidad_id, nombre) VALUES
(2,'4to'),(2,'5to'),(2,'6to'),
(3,'4to'),(3,'5to'),(3,'6to');

/* ---------- Secciones ---------- */
CREATE TABLE secciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    grado_id INT NOT NULL,
    nombre VARCHAR(5) NOT NULL,
    CONSTRAINT fk_seccion_grado FOREIGN KEY (grado_id) REFERENCES grados(id)
);

-- Secciones A-D por defecto
INSERT INTO secciones (grado_id, nombre)
SELECT g.id, s.letter
FROM grados g
JOIN (SELECT 'A' AS letter UNION ALL SELECT 'B' UNION ALL SELECT 'C' UNION ALL SELECT 'D') s;

/* ---------- Estudiantes ---------- */
CREATE TABLE estudiantes (
    sigerd_id VARCHAR(20) PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    modalidad_id INT NOT NULL,
    grado_id INT NOT NULL,
    seccion_id INT NOT NULL,
    CONSTRAINT fk_estudiante_modalidad FOREIGN KEY (modalidad_id) REFERENCES modalidades(id),
    CONSTRAINT fk_estudiante_grado FOREIGN KEY (grado_id) REFERENCES grados(id),
    CONSTRAINT fk_estudiante_seccion FOREIGN KEY (seccion_id) REFERENCES secciones(id)
);

/* ---------- Asignaturas ---------- */
CREATE TABLE asignaturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    modalidad_id INT NULL,
    CONSTRAINT fk_asignatura_modalidad FOREIGN KEY (modalidad_id) REFERENCES modalidades(id)
);

INSERT INTO asignaturas (nombre) VALUES
('Lengua Española'),
('Matemática'),
('Inglés'),
('Francés'),
('Ciencias Sociales'),
('Ciencias de la Naturaleza'),
('Educacion Artistica'),
('Educacion Fisica'),
('FIHyR'),
('Optativa 1'),
('Optativa 2'),
('Optativa 3'),
('Optativa 4'),
('Optativa 5'),
('Optativa 6'),
('Optativa 7');

/* ---------- Docentes ---------- */
CREATE TABLE docentes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    cedula VARCHAR(20) NOT NULL UNIQUE,
    telefono VARCHAR(20),
    asignatura_id INT NOT NULL,
    grado_id INT NULL,
    CONSTRAINT fk_docente_asignatura FOREIGN KEY (asignatura_id) REFERENCES asignaturas(id),
    CONSTRAINT fk_docente_grado FOREIGN KEY (grado_id) REFERENCES grados(id)
);

/* ---------- Competencias ---------- */
CREATE TABLE competencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL
);

INSERT INTO competencias (nombre) VALUES
('Comunicativa'),
('Pensamiento Logico, Creativo y Critico y Resolucion de Problemas'),
('Cientifica y Tecnologica y Ambiental y de la Salud'),
('Etica y Ciudadana y Desarrollo Personal y Espiritual');

/* ---------- Calificaciones ---------- */
CREATE TABLE calificaciones (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    estudiante_id VARCHAR(20) NOT NULL,
    asignatura_id INT NOT NULL,
    competencia_id INT NOT NULL,
    periodo TINYINT NOT NULL CHECK (periodo BETWEEN 1 AND 4),
    nota DECIMAL(5,2) NOT NULL CHECK (nota BETWEEN 0 AND 100),
    rp_nota DECIMAL(5,2) NULL CHECK (rp_nota BETWEEN 0 AND 100),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_calif_estudiante FOREIGN KEY (estudiante_id) REFERENCES estudiantes(sigerd_id),
    CONSTRAINT fk_calif_asignatura FOREIGN KEY (asignatura_id) REFERENCES asignaturas(id),
    CONSTRAINT fk_calif_competencia FOREIGN KEY (competencia_id) REFERENCES competencias(id),
    UNIQUE KEY uq_calif (estudiante_id, asignatura_id, competencia_id, periodo)
);

/* ---------- Asistencias ---------- */
CREATE TABLE asistencias (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    estudiante_id VARCHAR(20) NOT NULL,
    asignatura_id INT NOT NULL,
    periodo TINYINT NOT NULL CHECK (periodo BETWEEN 1 AND 4),
    porcentaje DECIMAL(5,2) NOT NULL CHECK (porcentaje BETWEEN 0 AND 100),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_asistencia_estudiante FOREIGN KEY (estudiante_id) REFERENCES estudiantes(sigerd_id),
    CONSTRAINT fk_asistencia_asignatura FOREIGN KEY (asignatura_id) REFERENCES asignaturas(id),
    UNIQUE KEY uq_asistencia (estudiante_id, asignatura_id, periodo)
);

/* ---------- Admins ---------- */
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    nombres VARCHAR(100),
    apellidos VARCHAR(100)
);
