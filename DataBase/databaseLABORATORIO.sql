CREATE DATABASE IF NOT EXISTS VitaLab
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE VitaLab;

/*tabla alumno y tabla estado (solvente, muerto no se)*/

/*CREATE TABLE carrera (
    id_carrera INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripción VARCHAR(255),
    activo TINYINT(1) NOT NULL DEFAULT 1,
    CONSTRAINT pk_carrera PRIMARY KEY (id_carrera)
);*/

/*muestras, medicion y pesaje, analisis, preparacion, control termico, etc*/
CREATE TABLE categoria_equipo (
    id_categoria  INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255),
    CONSTRAINT pk_categoria_equipo PRIMARY KEY (id_categoria)
);

/*existente, rentado, agotado, dañado, mantenimiento*/
CREATE TABLE estado_equipo (
    id_estado_equipo INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    CONSTRAINT pk_estado_equipo PRIMARY KEY (id_estado_equipo)
);

CREATE TABLE equipo (
    id_equipo INT NOT NULL AUTO_INCREMENT,
    id_categoria INT NOT NULL,
    id_estado_equipo INT NOT NULL,
    codigo VARCHAR(50) NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    numero_serie VARCHAR(100),
    fecha_adquisicion DATE,
    imagen_url VARCHAR(500),
    activo TINYINT NOT NULL DEFAULT 1,
    CONSTRAINT pk_equipo PRIMARY KEY (id_equipo),
    CONSTRAINT uq_equipo_codigo UNIQUE (codigo),
    CONSTRAINT uq_equipo_serie UNIQUE (numero_serie),
    CONSTRAINT fk_equipo_categoria FOREIGN KEY (id_categoria) REFERENCES categoria_equipo(id_categoria),
    CONSTRAINT fk_equipo_estado FOREIGN KEY (id_estado_equipo)REFERENCES estado_equipo(id_estado_equipo)
);

CREATE TABLE perfil (
    id_perfil INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(50)  NOT NULL,
    descripcion VARCHAR(255),
    activo TINYINT NOT NULL DEFAULT 1,
    CONSTRAINT pk_perfil PRIMARY KEY (id_perfil)
);

CREATE TABLE usuario (
    id_usuario INT NOT NULL AUTO_INCREMENT,
    id_perfil INT NOT NULL,
    carne_usuario VARCHAR(20),
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    correo VARCHAR(150) NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    activo TINYINT NOT NULL DEFAULT 1,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_usuario PRIMARY KEY (id_usuario),
    CONSTRAINT fk_usuario_perfil FOREIGN KEY (id_perfil) REFERENCES perfil(id_perfil)
);

/*proceso, completo, incompleto, no pago*/
CREATE TABLE estado_prestamo (
    id_estado_prestamo INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    CONSTRAINT pk_estado_prestamo PRIMARY KEY (id_estado_prestamo)
);

CREATE TABLE prestamo (
    id_prestamo INT NOT NULL AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    id_estado_prestamo INT NOT NULL,
    fecha_prestamo DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_devolucion_esperada DATE NOT NULL,
    CONSTRAINT pk_prestamo PRIMARY KEY (id_prestamo),
    CONSTRAINT fk_prestamo_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    CONSTRAINT fk_prestamo_estado FOREIGN KEY (id_estado_prestamo) REFERENCES estado_prestamo(id_estado_prestamo)
);

CREATE TABLE detalle_prestamo (
    id_detalle INT NOT NULL AUTO_INCREMENT,
    id_prestamo INT NOT NULL,
    id_equipo INT NOT NULL,
    CONSTRAINT pk_detalle_prestamo PRIMARY KEY (id_detalle),
    CONSTRAINT uq_detalle_prestamo_equipo UNIQUE (id_prestamo, id_equipo),
    CONSTRAINT fk_detprestamo_prestamo FOREIGN KEY (id_prestamo) REFERENCES prestamo(id_prestamo),
    CONSTRAINT fk_detprestamo_equipo FOREIGN KEY (id_equipo) REFERENCES equipo(id_equipo)
);

/*dañado, intacto, incompleto, completo*/
CREATE TABLE estado_condicion_devolucion (
    id_estado_condicion INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    CONSTRAINT pk_estado_condicion PRIMARY KEY (id_estado_condicion)
);

/*agregar id alumno, quien hizo la devolucion*/
CREATE TABLE devolucion (
    id_devolucion INT NOT NULL AUTO_INCREMENT,
    id_prestamo INT NOT NULL,
    id_usuario INT NOT NULL,
    fecha_devolucion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    observaciones TEXT,
    CONSTRAINT pk_devolucion PRIMARY KEY (id_devolucion),
    CONSTRAINT fk_devolucion_prestamo FOREIGN KEY (id_prestamo) REFERENCES prestamo(id_prestamo),
    CONSTRAINT fk_devolucion_encargado FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

CREATE TABLE detalle_devolucion (
    id_detalle_devolucion INT NOT NULL AUTO_INCREMENT,
    id_devolucion INT NOT NULL,
    id_detalle_prestamo INT NOT NULL,
    id_estado_condicion INT NOT NULL,
    observaciones TEXT,
    CONSTRAINT pk_detalle_devolucion PRIMARY KEY (id_detalle_devolucion),
    CONSTRAINT uq_detdev_detprestamo UNIQUE (id_devolucion, id_detalle_prestamo),
    CONSTRAINT fk_detdev_devolucion FOREIGN KEY (id_devolucion) REFERENCES devolucion(id_devolucion),
    CONSTRAINT fk_detdev_detalle_prestamo FOREIGN KEY (id_detalle_prestamo) REFERENCES detalle_prestamo(id_detalle),
    CONSTRAINT fk_detdev_condicion FOREIGN KEY (id_estado_condicion) REFERENCES estado_condicion_devolucion(id_estado_condicion)
);

CREATE TABLE permiso (
    id_permiso INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255),
    CONSTRAINT pk_permiso PRIMARY KEY (id_permiso)
);

CREATE TABLE perfil_permiso ( 
    id_perfil INT NOT NULL,
    id_permiso INT NOT NULL,
    CONSTRAINT pk_perfil_permiso PRIMARY KEY  (id_perfil, id_permiso),
    CONSTRAINT fk_perfilpermiso_perfil FOREIGN KEY (id_perfil) REFERENCES perfil(id_perfil),
    CONSTRAINT fk_perfilpermiso_permiso FOREIGN KEY (id_permiso) REFERENCES permiso(id_permiso)
);

CREATE TABLE sesion (
    id_sesion INT NOT NULL AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    /*llave de acceso*/
    token VARCHAR(255) NOT NULL,
    fecha_inicio DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_expiracion DATETIME NOT NULL,
    ip_acceso VARCHAR(45),
    activo TINYINT NOT NULL DEFAULT 1,
    CONSTRAINT pk_sesion PRIMARY KEY (id_sesion),
    CONSTRAINT uq_sesion_token UNIQUE (token),
    CONSTRAINT fk_sesion_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

CREATE TABLE restablecimiento_contrasena (
    id_restablecimiento INT NOT NULL AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    fecha_solicitud DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_expiracion DATETIME NOT NULL,
    usado TINYINT NOT NULL DEFAULT 0,
    CONSTRAINT pk_restablecimiento PRIMARY KEY (id_restablecimiento),
    CONSTRAINT uq_restablecimiento_token UNIQUE (token),
    CONSTRAINT fk_restablecimiento_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

CREATE TABLE configuracion_mora (
    id_configuracion_mora INT NOT NULL AUTO_INCREMENT,
    valor_mora_por_dia DECIMAL(10,2) NOT NULL,
    descripcion VARCHAR(255),
    fecha_inicio_vigencia DATE NOT NULL,
    fecha_fin_vigencia DATE,
    activo TINYINT NOT NULL DEFAULT 1,
    CONSTRAINT pk_configurarmora PRIMARY KEY (id_configuracion_mora)
);

CREATE TABLE mora (
    id_mora INT NOT NULL AUTO_INCREMENT,
    id_prestamo INT NOT NULL,
    id_configuracion_mora INT NOT NULL,
    dias_retraso INT NOT NULL,
    total_mora DECIMAL(10,2) NOT NULL,
    fecha_calculo DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    pagado TINYINT NOT NULL DEFAULT 0,
    fecha_pago DATETIME,
    CONSTRAINT pk_mora PRIMARY KEY (id_mora),
    CONSTRAINT uq_mora_prestamo UNIQUE (id_prestamo),
    CONSTRAINT fk_mora_prestamo FOREIGN KEY (id_prestamo) REFERENCES prestamo(id_prestamo),
    CONSTRAINT fk_mora_config FOREIGN KEY (id_configuracion_mora) REFERENCES configuracion_mora(id_configuracion_mora)
);

CREATE TABLE bitacora (
    id_bitacora BIGINT NOT NULL AUTO_INCREMENT,
    id_usuario INT,
    accion VARCHAR(50)  NOT NULL,
    tabla_afectada VARCHAR(100) NOT NULL,
    id_registro_afectado INT,
    descripción TEXT,
    fecha_hora DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ip VARCHAR(45),
    CONSTRAINT pk_bitacora PRIMARY KEY (id_bitacora),
    CONSTRAINT fk_bitacora_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);