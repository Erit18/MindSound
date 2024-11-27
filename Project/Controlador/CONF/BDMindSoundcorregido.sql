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
    UNIQUE KEY uk_libro_genero (IDLibro, IDGenero),
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
    EstadoSuscripcion ENUM('Activa', 'Cancelada', 'Pendiente de cambio') DEFAULT 'Activa',
    PrecioMensual DECIMAL(10, 2) DEFAULT 0.00,
    FechaModificacion DATETIME NULL,
    FOREIGN KEY (IDUsuario) REFERENCES Usuarios(IDUsuario)
);


-- Tabla MetodosPago
CREATE TABLE MetodosPago (
    IDMetodoPago INT PRIMARY KEY AUTO_INCREMENT,
    NombreMetodo VARCHAR(50) NOT NULL UNIQUE
);

-- Tabla Pagos
CREATE TABLE Pagos (
    IDPago INT PRIMARY KEY AUTO_INCREMENT,
    IDUsuario INT,
    IDSuscripcion INT,
    Monto DECIMAL(10, 2) NOT NULL,
    FechaPago DATETIME DEFAULT CURRENT_TIMESTAMP,
    IDMetodoPago INT,
    EstadoPago ENUM('Completado', 'Pendiente', 'Fallido') DEFAULT 'Pendiente',
    Descripcion VARCHAR(255) NULL,
    FOREIGN KEY (IDUsuario) REFERENCES Usuarios(IDUsuario),
    FOREIGN KEY (IDSuscripcion) REFERENCES Suscripciones(IDSuscripcion),
    FOREIGN KEY (IDMetodoPago) REFERENCES MetodosPago(IDMetodoPago)
);

-- Tabla ProgresoLibros
CREATE TABLE ProgresoLibros (
    IDProgreso INT PRIMARY KEY AUTO_INCREMENT,
    IDUsuario INT,
    IDLibro INT,
    UltimaPosicion TIME NOT NULL,
    FechaUltimaEscucha DATETIME DEFAULT CURRENT_TIMESTAMP,
    TiempoTotalEscuchado TIME DEFAULT '00:00:00',
    UNIQUE KEY uk_usuario_libro (IDUsuario, IDLibro),
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
    SELECT l.*, 
           GROUP_CONCAT(g.NombreGenero) as Generos
    FROM Libros l
    LEFT JOIN LibroGenero lg ON l.IDLibro = lg.IDLibro
    LEFT JOIN Generos g ON lg.IDGenero = g.IDGenero
    GROUP BY l.IDLibro;
END //

-- Obtener un libro por ID
CREATE PROCEDURE SP_OBTENER_LIBRO_POR_ID(IN p_IDLibro INT)
BEGIN
    SELECT * FROM Libros WHERE IDLibro = p_IDLibro;
END //

-- Agregar un nuevo libro
CREATE PROCEDURE SP_AGREGAR_LIBRO(
    IN p_titulo VARCHAR(100),
    IN p_autor VARCHAR(100),
    IN p_narrador VARCHAR(100),
    IN p_duracion VARCHAR(20),
    IN p_fechaPublicacion DATE,
    IN p_descripcion TEXT,
    IN p_rutaAudio VARCHAR(255),
    IN p_rutaPortada VARCHAR(255),
    IN p_precio DECIMAL(10,2),
    IN p_esGratuito TINYINT,
    OUT p_idLibro INT
)
BEGIN
    INSERT INTO Libros(
        Titulo, 
        Autor, 
        Narrador, 
        Duracion, 
        FechaPublicacion, 
        Descripcion, 
        RutaAudio, 
        RutaPortada, 
        Precio, 
        EsGratuito
    ) VALUES (
        p_titulo,
        p_autor,
        p_narrador,
        p_duracion,
        p_fechaPublicacion,
        p_descripcion,
        p_rutaAudio,
        p_rutaPortada,
        p_precio,
        p_esGratuito
    );
    
    SET p_idLibro = LAST_INSERT_ID();
    SELECT p_idLibro as IDLibro;
END//

-- Actualizar un libro existente
CREATE PROCEDURE SP_ACTUALIZAR_LIBRO(
    IN p_IDLibro INT,
    IN p_Titulo VARCHAR(100),
    IN p_Autor VARCHAR(100),
    IN p_Narrador VARCHAR(100),
    IN p_Duracion TIME,
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
        RutaAudio = CASE WHEN p_RutaAudio = '' THEN RutaAudio ELSE p_RutaAudio END,
        RutaPortada = CASE WHEN p_RutaPortada = '' THEN RutaPortada ELSE p_RutaPortada END,
        Precio = p_Precio,
        EsGratuito = p_EsGratuito
    WHERE IDLibro = p_IDLibro;
END //

-- Eliminar un libro
CREATE PROCEDURE SP_ELIMINAR_LIBRO(IN p_IDLibro INT)
BEGIN
    -- Primero eliminar registros relacionados
    DELETE FROM LibroGenero WHERE IDLibro = p_IDLibro;
    DELETE FROM HistorialEscuchas WHERE IDLibro = p_IDLibro;
    DELETE FROM ProgresoLibros WHERE IDLibro = p_IDLibro;
    
    -- Finalmente eliminar el libro
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
    -- Primero eliminar relaciones
    DELETE FROM LibroGenero WHERE IDGenero = p_id;
    
    -- Luego eliminar el género
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
    INSERT INTO Suscripciones (IDUsuario, TipoSuscripcion, FechaInicio, FechaFin, EstadoSuscripcion)
    VALUES (p_IDUsuario, p_TipoSuscripcion, p_FechaInicio, p_FechaFin, 'Activa');
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
    -- Primero eliminar pagos relacionados
    DELETE FROM Pagos WHERE IDSuscripcion = p_IDSuscripcion;
    
    -- Luego eliminar la suscripción
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
    IN p_IDMetodoPago INT,
    IN p_EstadoPago ENUM('Completado', 'Pendiente', 'Fallido')
)
BEGIN
    INSERT INTO Pagos (IDUsuario, IDSuscripcion, Monto, FechaPago, IDMetodoPago, EstadoPago)
    VALUES (p_IDUsuario, p_IDSuscripcion, p_Monto, NOW(), p_IDMetodoPago, p_EstadoPago);
END //

CREATE PROCEDURE SP_ACTUALIZAR_PAGO(
    IN p_IDPago INT,
    IN p_Monto DECIMAL(10, 2),
    IN p_IDMetodoPago INT,
    IN p_EstadoPago ENUM('Completado', 'Pendiente', 'Fallido')
)
BEGIN
    UPDATE Pagos
    SET Monto = p_Monto,
        IDMetodoPago = p_IDMetodoPago,
        EstadoPago = p_EstadoPago
    WHERE IDPago = p_IDPago;
END //

CREATE PROCEDURE SP_ELIMINAR_PAGO(IN p_IDPago INT)
BEGIN
    DELETE FROM Pagos WHERE IDPago = p_IDPago;
END //

CREATE PROCEDURE SP_ACTUALIZAR_PROGRESO(
    IN p_IDUsuario INT,
    IN p_IDLibro INT,
    IN p_UltimaPosicion TIME,
    IN p_TiempoTotalEscuchado TIME
)
BEGIN
    IF p_UltimaPosicion < '00:00:00' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La posición no puede ser negativa';
    END IF;

    INSERT INTO ProgresoLibros (IDUsuario, IDLibro, UltimaPosicion, TiempoTotalEscuchado)
    VALUES (p_IDUsuario, p_IDLibro, p_UltimaPosicion, p_TiempoTotalEscuchado)
    ON DUPLICATE KEY UPDATE 
        UltimaPosicion = p_UltimaPosicion,
        TiempoTotalEscuchado = p_TiempoTotalEscuchado,
        FechaUltimaEscucha = CURRENT_TIMESTAMP;
END //


CREATE PROCEDURE SP_ASIGNAR_GENERO_LIBRO(
    IN p_IDLibro INT,
    IN p_IDGenero INT
)
BEGIN
    INSERT INTO LibroGenero (IDLibro, IDGenero)
    VALUES (p_IDLibro, p_IDGenero);
END //

CREATE PROCEDURE SP_OBTENER_GENEROS_LIBRO(
    IN p_IDLibro INT
)
BEGIN
    SELECT g.*
    FROM Generos g
    JOIN LibroGenero lg ON g.IDGenero = lg.IDGenero
    WHERE lg.IDLibro = p_IDLibro;
END //

DELIMITER ;


-- Luego, inserta los datos uno por uno
INSERT INTO `libros` VALUES 
(24, 'El hijo del Reich', 'Rafael Tarradas Bultó', 'Anónimo', '0:29', '2024-08-28', 
'El niño al que todos buscan. Una madre dispuesta a defenderlo. Un secreto inconfesable. 180.000 lectores ya tienen a Rafael Tarradas Bultó en su lista de imprescindibles.\r\n\r\nTodo Reich necesita a sus príncipes cerca.',
'audio/ringtones-super-mario-bros.mp3',
'img/Books/portada_el-hijo-del-reich_rafael-tarradas-bulto_202408021345[1].webp',
0.00, 1);

INSERT INTO `libros` VALUES 
(25, 'La sangre del padre', 'Alfonso Goizueta', 'Anónimo', '0:29', '2023-11-20',
'Conquistó el imperio más poderoso del mundo, pero la guerra más violenta la libró contra sí mismo.',
'audio/ringtones-super-mario-bros.mp3',
'img/Books/portada_la-sangre-del-padre_alfonso-goizueta_202310231206[1].webp',
0.00, 1);

INSERT INTO `libros` VALUES 
(26, 'Cuando la tormenta pase', 'Manel Loureiro', 'Anónimo', '0:29', '2024-07-31',
'Una remota isla. Una ofrenda sangrienta. Una tempestad que parece el preludio de una gran tragedia. Manel Loureiro da un giro definitivo al thriller con una novela impactante que no te puedes perder.',
'audio/ringtones-super-mario-bros.mp3',
'img/Books/portada_cuando-la-tormenta-pase_manel-loureiro_202406141234[1].webp',
0.00, 1);

INSERT INTO `libros` VALUES 
(27, 'El tiempo de las fieras', 'Víctor del Árbol', 'Anónimo', '0:29', '2024-08-24',
'El atropello de una joven será la punta de lanza de una trama colosal de crimen y poder en la que todas las piezas que la componen empezarán a tambalearse.',
'audio/ringtones-super-mario-bros.mp3',
'img/Books/portada_el-tiempo-de-las-fieras_victor-del-arbol_202408021337[1].webp',
0.00, 1);

INSERT INTO `libros` VALUES 
(28, 'La Supraconciencia existe', 'Dr. Manuel Sans Segarra y Juan Carlos Cebrián', 'Anónimo', '0:29', '2024-09-18',
'El libro definitivo sobre el fenómeno de las Experiencias Cercanas a la Muerte (ECM) y su poder para transformar nuestras vidas.',
'audio/ringtones-super-mario-bros.mp3',
'img/Books/402620_portada_la-supraconciencia-existe_dr-manuel-sans-segarra_202407191422[1].webp',
0.00, 1);

INSERT INTO `libros` VALUES 
(29, 'Respira', 'James Nestor', 'Anónimo', '0:29', '2021-01-21',
'«Si respiras, necesitas leer este libro.» Wallace Nichols\r\n\r\n«Un libro transformador que va a cambiar completamente la manera en la que pensamos en nuestro cuerpo y nuestra mente.» Joshua Foer',
'audio/ringtones-super-mario-bros.mp3',
'img/Books/portada_respira_james-nestor_202011161204[1].webp',
0.00, 1);

INSERT INTO `libros` VALUES 
(30, 'Tu cerebro quiere arte', 'Susan Magsamen e Ivy Ross', 'Anónimo', '0:29', '2024-11-20',
'Un fascinante recorrido a travs de la nueva ciencia de la neuroestética, que demuestra cómo el contacto con el arte transforma nuestro cerebro, mejora nuestra salud mental y física y nos permite construir comunidades más fuertes y unidas.',
'audio/ringtones-super-mario-bros.mp3',
'img/Books/portada_tu-cerebro-quiere-arte_susan-magsamen_202410141740[1].webp',
0.00, 1);

INSERT INTO `libros` VALUES 
(31, 'La primera aventura', 'Serafín Adame Martínez', 'Anónimo', '9:30', '1922-02-26',
'Juan Uceda sonrió complacido del adulador comentario, y se lanzó a la complicada tarea de anudar el lazo de la corbata. Pablillo, el estudiante más desaplicado y simpático entre cuantos simpáticos y desaplicados llenan los claustros de la Universidad madrileña, acabó de sorber su caté, y acudió presuroso a enfundar en el frac el cuerpo de su amigo.',
'audio/albalearning-LaPrimeraAventura_adame.mp3',
'img/Books/libro-adame[1].jpg',
0.00, 1);

-- Insertar los géneros básicos
INSERT INTO Generos (NombreGenero, Descripcion) VALUES 
('Biográfico', 'Libros basados en la vida real de personas'),
('Histórico', 'Libros basados en eventos históricos'),
('Horror', 'Libros del género de terror y horror'),
('Misterio', 'Libros de suspenso y misterio'),
('Novela', 'Libros de ficción narrativa'),
('Ciencia', 'Libros sobre temas científicos'),
('Deportes', 'Libros relacionados con deportes y actividad física');

