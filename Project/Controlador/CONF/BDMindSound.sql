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
    Duracion VARCHAR(8),  -- Cambiado de TIME a VARCHAR(8)
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
    TipoSuscripcion ENUM('Básica', 'Normal', 'Premium') NOT NULL,
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

-- Obtener todos los libros
CREATE PROCEDURE SP_OBTENER_LIBROS()
BEGIN
    SELECT * FROM Libros;
END //

-- Obtener un libro por ID
CREATE PROCEDURE SP_OBTENER_LIBRO_POR_ID(IN p_IDLibro INT)
BEGIN
    SELECT * FROM Libros WHERE IDLibro = p_IDLibro;
END //

-- Agregar un nuevo libro
CREATE PROCEDURE SP_AGREGAR_LIBRO(
    IN p_Titulo VARCHAR(100),
    IN p_Autor VARCHAR(100),
    IN p_Narrador VARCHAR(100),
    IN p_Duracion VARCHAR(8),
    IN p_FechaPublicacion DATE,
    IN p_Descripcion TEXT,
    IN p_RutaAudio VARCHAR(255),
    IN p_RutaPortada VARCHAR(255),
    IN p_Precio DECIMAL(10, 2),
    IN p_EsGratuito BOOLEAN
)
BEGIN
    INSERT INTO Libros (Titulo, Autor, Narrador, Duracion, FechaPublicacion, Descripcion, RutaAudio, RutaPortada, Precio, EsGratuito)
    VALUES (p_Titulo, p_Autor, p_Narrador, p_Duracion, p_FechaPublicacion, p_Descripcion, p_RutaAudio, p_RutaPortada, p_Precio, p_EsGratuito);
END //

-- Actualizar un libro existente
CREATE PROCEDURE SP_ACTUALIZAR_LIBRO(
    IN p_IDLibro INT,
    IN p_Titulo VARCHAR(100),
    IN p_Autor VARCHAR(100),
    IN p_Narrador VARCHAR(100),
    IN p_Duracion VARCHAR(8),  -- Cambiado de TIME a VARCHAR(8)
    IN p_FechaPublicacion DATE,
    IN p_Descripcion TEXT,
    IN p_RutaAudio VARCHAR(255),
    IN p_RutaPortada VARCHAR(255),
    IN p_Precio DECIMAL(10, 2),
    IN p_EsGratuito BOOLEAN
)
BEGIN
    UPDATE Libros
    SET Titulo = p_Titulo,
        Autor = p_Autor,
        Narrador = p_Narrador,
        Duracion = p_Duracion,
        FechaPublicacion = p_FechaPublicacion,
        Descripcion = p_Descripcion,
        RutaAudio = p_RutaAudio,
        RutaPortada = p_RutaPortada,
        Precio = p_Precio,
        EsGratuito = p_EsGratuito
    WHERE IDLibro = p_IDLibro;
END //

-- Eliminar un libro
CREATE PROCEDURE SP_ELIMINAR_LIBRO(IN p_IDLibro INT)
BEGIN
    DELETE FROM Libros WHERE IDLibro = p_IDLibro;
END //

-- Obtener todos los géneros
CREATE PROCEDURE SP_OBTENER_GENEROS()
BEGIN
    SELECT * FROM Generos;
END //

-- Obtener un género por ID
CREATE PROCEDURE SP_OBTENER_GENERO_POR_ID(IN p_id INT)
BEGIN
    SELECT * FROM Generos WHERE IDGenero = p_id;
END //

-- Insertar un nuevo género
CREATE PROCEDURE SP_INSERTAR_GENERO(
    IN p_nombre VARCHAR(50), 
    IN p_descripcion TEXT
)
BEGIN
    INSERT INTO Generos (NombreGenero, Descripcion)
    VALUES (p_nombre, p_descripcion);
END //

-- Actualizar un género existente
CREATE PROCEDURE SP_ACTUALIZAR_GENERO(
    IN p_id INT,
    IN p_nombre VARCHAR(50),
    IN p_descripcion TEXT
)
BEGIN
    UPDATE Generos
    SET NombreGenero = p_nombre,
        Descripcion = p_descripcion
    WHERE IDGenero = p_id;
END //

-- Eliminar un género
CREATE PROCEDURE SP_ELIMINAR_GENERO(IN p_id INT)
BEGIN
    DELETE FROM Generos WHERE IDGenero = p_id;
END //

-- Agregar procedimientos almacenados para Suscripciones
DELIMITER //

CREATE PROCEDURE SP_OBTENER_SUSCRIPCIONES()
BEGIN
    SELECT s.*, u.Nombre, u.Apellido
    FROM Suscripciones s
    JOIN Usuarios u ON s.IDUsuario = u.IDUsuario;
END //

CREATE PROCEDURE SP_OBTENER_SUSCRIPCION_POR_ID(IN p_IDSuscripcion INT)
BEGIN
    SELECT s.*, u.Nombre, u.Apellido
    FROM Suscripciones s
    JOIN Usuarios u ON s.IDUsuario = u.IDUsuario
    WHERE s.IDSuscripcion = p_IDSuscripcion;
END //

CREATE PROCEDURE SP_AGREGAR_SUSCRIPCION(
    IN p_IDUsuario INT,
    IN p_TipoSuscripcion ENUM('Básica', 'Normal', 'Premium'),
    IN p_FechaInicio DATE,
    IN p_FechaFin DATE
)
BEGIN
    INSERT INTO Suscripciones (IDUsuario, TipoSuscripcion, FechaInicio, FechaFin)
    VALUES (p_IDUsuario, p_TipoSuscripcion, p_FechaInicio, p_FechaFin);
END //

CREATE PROCEDURE SP_ACTUALIZAR_SUSCRIPCION(
    IN p_IDSuscripcion INT,
    IN p_TipoSuscripcion ENUM('Básica', 'Normal', 'Premium'),
    IN p_FechaInicio DATE,
    IN p_FechaFin DATE,
    IN p_EstadoSuscripcion ENUM('Activa', 'Cancelada', 'Expirada')
)
BEGIN
    UPDATE Suscripciones
    SET TipoSuscripcion = p_TipoSuscripcion,
        FechaInicio = p_FechaInicio,
        FechaFin = p_FechaFin,
        EstadoSuscripcion = p_EstadoSuscripcion
    WHERE IDSuscripcion = p_IDSuscripcion;
END //

CREATE PROCEDURE SP_ELIMINAR_SUSCRIPCION(IN p_IDSuscripcion INT)
BEGIN
    DELETE FROM Suscripciones WHERE IDSuscripcion = p_IDSuscripcion;
END //

-- Agregar procedimientos almacenados para Pagos
DELIMITER //

CREATE PROCEDURE SP_OBTENER_PAGOS()
BEGIN
    SELECT p.*, u.Nombre, u.Apellido, s.TipoSuscripcion
    FROM Pagos p
    JOIN Usuarios u ON p.IDUsuario = u.IDUsuario
    JOIN Suscripciones s ON p.IDSuscripcion = s.IDSuscripcion;
END //

CREATE PROCEDURE SP_OBTENER_PAGO_POR_ID(IN p_IDPago INT)
BEGIN
    SELECT p.*, u.Nombre, u.Apellido, s.TipoSuscripcion
    FROM Pagos p
    JOIN Usuarios u ON p.IDUsuario = u.IDUsuario
    JOIN Suscripciones s ON p.IDSuscripcion = s.IDSuscripcion
    WHERE p.IDPago = p_IDPago;
END //

CREATE PROCEDURE SP_AGREGAR_PAGO(
    IN p_IDUsuario INT,
    IN p_IDSuscripcion INT,
    IN p_Monto DECIMAL(10, 2),
    IN p_MetodoPago VARCHAR(50),
    IN p_EstadoPago ENUM('Completado', 'Pendiente', 'Fallido')
)
BEGIN
    INSERT INTO Pagos (IDUsuario, IDSuscripcion, Monto, MetodoPago, EstadoPago)
    VALUES (p_IDUsuario, p_IDSuscripcion, p_Monto, p_MetodoPago, p_EstadoPago);
END //

CREATE PROCEDURE SP_ACTUALIZAR_PAGO(
    IN p_IDPago INT,
    IN p_Monto DECIMAL(10, 2),
    IN p_MetodoPago VARCHAR(50),
    IN p_EstadoPago ENUM('Completado', 'Pendiente', 'Fallido')
)
BEGIN
    UPDATE Pagos
    SET Monto = p_Monto,
        MetodoPago = p_MetodoPago,
        EstadoPago = p_EstadoPago
    WHERE IDPago = p_IDPago;
END //

CREATE PROCEDURE SP_ELIMINAR_PAGO(IN p_IDPago INT)
BEGIN
    DELETE FROM Pagos WHERE IDPago = p_IDPago;
END //

DELIMITER ;