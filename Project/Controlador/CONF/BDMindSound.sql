CREATE DATABASE bdmindsound;

USE bdmindsound;

-- Tabla Usuarios
CREATE TABLE Usuarios (
    IDUsuario INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(50) NOT NULL,
    Apellido VARCHAR(50) NOT NULL,
    CorreoElectronico VARCHAR(100) UNIQUE NOT NULL,
    Contraseña VARCHAR(255) NOT NULL,
    FechaNacimiento DATE,
    Genero ENUM('Masculino', 'Femenino', 'Otro'),
    FechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP,
    Rol ENUM('Usuario', 'Administrador') DEFAULT 'Usuario',
    EstadoSuscripcion ENUM('Activa', 'Inactiva', 'Pendiente') DEFAULT 'Inactiva'
);

-- Tabla Libros
CREATE TABLE Libros (
    IDLibro INT PRIMARY KEY AUTO_INCREMENT,
    Titulo VARCHAR(100) NOT NULL,
    Autor VARCHAR(100) NOT NULL,
    Narrador VARCHAR(100),
    Duracion TIME,
    FechaPublicacion DATE,
    Descripcion TEXT,
    RutaAudio VARCHAR(255),
    RutaPortada VARCHAR(255),
    Precio DECIMAL(10, 2),
    EsGratuito BOOLEAN DEFAULT FALSE
);

-- Tabla HistorialEscuchas
CREATE TABLE HistorialEscuchas (
    IDHistorial INT PRIMARY KEY AUTO_INCREMENT,
    IDUsuario INT,
    IDLibro INT,
    FechaEscuchado DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (IDUsuario) REFERENCES Usuarios(IDUsuario),
    FOREIGN KEY (IDLibro) REFERENCES Libros(IDLibro)
);

-- Tabla Roles
CREATE TABLE Roles (
    IDRol INT PRIMARY KEY AUTO_INCREMENT,
    IDUsuario INT,
    Descripcion VARCHAR(50) NOT NULL,
    FOREIGN KEY (IDUsuario) REFERENCES Usuarios(IDUsuario),
    UNIQUE KEY (IDUsuario, Descripcion)
);

-- Tabla Generos
CREATE TABLE Generos (
    IDGenero INT PRIMARY KEY AUTO_INCREMENT,
    NombreGenero VARCHAR(50) NOT NULL,
    Descripcion TEXT
);

-- Tabla LibroGenero
CREATE TABLE LibroGenero (
    IDLibro INT,
    IDGenero INT,
    PRIMARY KEY (IDLibro, IDGenero),
    FOREIGN KEY (IDLibro) REFERENCES Libros(IDLibro),
    FOREIGN KEY (IDGenero) REFERENCES Generos(IDGenero)
);

-- Tabla Suscripciones
CREATE TABLE Suscripciones (
    IDSuscripcion INT PRIMARY KEY AUTO_INCREMENT,
    IDUsuario INT,
    TipoSuscripcion ENUM('Mensual', 'Anual') NOT NULL,
    FechaInicio DATE NOT NULL,
    FechaFin DATE NOT NULL,
    EstadoSuscripcion ENUM('Activa', 'Cancelada', 'Expirada') DEFAULT 'Activa',
    FOREIGN KEY (IDUsuario) REFERENCES Usuarios(IDUsuario)
);

-- Tabla Pagos
CREATE TABLE Pagos (
    IDPago INT PRIMARY KEY AUTO_INCREMENT,
    IDUsuario INT,
    IDSuscripcion INT,
    Monto DECIMAL(10, 2) NOT NULL,
    FechaPago DATETIME DEFAULT CURRENT_TIMESTAMP,
    MetodoPago VARCHAR(50),
    EstadoPago ENUM('Completado', 'Pendiente', 'Fallido') DEFAULT 'Pendiente',
    FOREIGN KEY (IDUsuario) REFERENCES Usuarios(IDUsuario),
    FOREIGN KEY (IDSuscripcion) REFERENCES Suscripciones(IDSuscripcion)
);

-- Tabla METODODEPAGO
CREATE TABLE METODODEPAGO (
    IDMetodoPago INT PRIMARY KEY AUTO_INCREMENT,
    IDPago INT,
    nombre_metodo_pago VARCHAR(50) NOT NULL,
    FOREIGN KEY (IDPago) REFERENCES Pagos(IDPago)
);

-- Tabla ProgresoLibros
CREATE TABLE ProgresoLibros (
    IDProgreso INT PRIMARY KEY AUTO_INCREMENT,
    IDUsuario INT,
    IDLibro INT,
    TiempoEscuchado DECIMAL(5,2) NOT NULL,
    UltimaPosicion TIME,
    FechaUltimaEscucha DATE,
    FOREIGN KEY (IDUsuario) REFERENCES Usuarios(IDUsuario),
    FOREIGN KEY (IDLibro) REFERENCES Libros(IDLibro)
);

-- Procedimientos almacenados

DELIMITER //

CREATE PROCEDURE SP_OBTENER_USUARIOS(IN bus VARCHAR(255))
BEGIN
    SELECT IDUsuario, Nombre, Apellido, CorreoElectronico, FechaNacimiento, Genero, FechaRegistro, Rol, EstadoSuscripcion
    FROM Usuarios
    WHERE IDUsuario LIKE CONCAT('%', bus, '%')
       OR Nombre LIKE CONCAT('%', bus, '%')
       OR Apellido LIKE CONCAT('%', bus, '%')
       OR CorreoElectronico LIKE CONCAT('%', bus, '%')
       OR FechaNacimiento LIKE CONCAT('%', bus, '%')
       OR Genero LIKE CONCAT('%', bus, '%')
       OR FechaRegistro LIKE CONCAT('%', bus, '%')
       OR Rol LIKE CONCAT('%', bus, '%')
       OR EstadoSuscripcion LIKE CONCAT('%', bus, '%');
END //

CREATE PROCEDURE SP_INSERTAR_USUARIO(
    IN p_Nombre VARCHAR(50),
    IN p_Apellido VARCHAR(50),
    IN p_CorreoElectronico VARCHAR(100),
    IN p_Contraseña VARCHAR(255),
    IN p_FechaNacimiento DATE,
    IN p_Genero ENUM('Masculino', 'Femenino', 'Otro')
)
BEGIN
    INSERT INTO Usuarios (Nombre, Apellido, CorreoElectronico, Contraseña, FechaNacimiento, Genero)
    VALUES (p_Nombre, p_Apellido, p_CorreoElectronico, p_Contraseña, p_FechaNacimiento, p_Genero);
END //

CREATE PROCEDURE SP_OBTENER_USUARIO_POR_ID(
    IN p_IDUsuario INT
)
BEGIN
    SELECT * FROM Usuarios WHERE IDUsuario = p_IDUsuario;
END //

CREATE PROCEDURE SP_ACTUALIZAR_USUARIO(
    IN p_IDUsuario INT,
    IN p_Nombre VARCHAR(50),
    IN p_Apellido VARCHAR(50),
    IN p_CorreoElectronico VARCHAR(100),
    IN p_FechaNacimiento DATE,
    IN p_Genero ENUM('Masculino', 'Femenino', 'Otro'),
    IN p_Rol ENUM('Usuario', 'Administrador')
)
BEGIN
    UPDATE Usuarios
    SET Nombre = p_Nombre,
        Apellido = p_Apellido,
        CorreoElectronico = p_CorreoElectronico,
        FechaNacimiento = p_FechaNacimiento,
        Genero = p_Genero,
        Rol = p_Rol
    WHERE IDUsuario = p_IDUsuario;
END //

CREATE PROCEDURE SP_ELIMINAR_USUARIO(
    IN p_IDUsuario INT
)
BEGIN
    -- Primero, eliminar registros relacionados
    DELETE FROM HistorialEscuchas WHERE IDUsuario = p_IDUsuario;
    DELETE FROM Roles WHERE IDUsuario = p_IDUsuario;
    DELETE FROM Suscripciones WHERE IDUsuario = p_IDUsuario;
    DELETE FROM Pagos WHERE IDUsuario = p_IDUsuario;
    DELETE FROM ProgresoLibros WHERE IDUsuario = p_IDUsuario;
    
    -- Finalmente, eliminar el usuario
    DELETE FROM Usuarios WHERE IDUsuario = p_IDUsuario;
END //

CREATE PROCEDURE SP_VERIFICAR_CREDENCIALES(
    IN p_CorreoElectronico VARCHAR(100)
)
BEGIN
    SELECT IDUsuario, Nombre, Apellido, CorreoElectronico, Contraseña, Rol, EstadoSuscripcion
    FROM Usuarios
    WHERE CorreoElectronico = p_CorreoElectronico;
END //

CREATE PROCEDURE SP_AGREGAR_USUARIO(
    IN p_Nombre VARCHAR(50),
    IN p_Apellido VARCHAR(50),
    IN p_CorreoElectronico VARCHAR(100),
    IN p_Contraseña VARCHAR(255),
    IN p_FechaNacimiento DATE,
    IN p_Genero ENUM('Masculino', 'Femenino', 'Otro'),
    IN p_Rol ENUM('Usuario', 'Administrador')
)
BEGIN
    INSERT INTO Usuarios (Nombre, Apellido, CorreoElectronico, Contraseña, FechaNacimiento, Genero, Rol)
    VALUES (p_Nombre, p_Apellido, p_CorreoElectronico, p_Contraseña, p_FechaNacimiento, p_Genero, p_Rol);
END //

DELIMITER ;
