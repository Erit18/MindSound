<?php
require_once 'conexion.php';

// Obtener libros (necesitarás crear un SP para esto)
$stmt = $conn->prepare("SELECT * FROM Libros");
$stmt->execute();
$libros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Gestión de Libros</h2>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Autor</th>
            <th>Narrador</th>
            <th>Duración</th>
            <th>Precio</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($libros as $libro): ?>
        <tr>
            <td><?php echo $libro['IDLibro']; ?></td>
            <td><?php echo $libro['Titulo']; ?></td>
            <td><?php echo $libro['Autor']; ?></td>
            <td><?php echo $libro['Narrador']; ?></td>
            <td><?php echo $libro['Duracion']; ?></td>
            <td><?php echo $libro['Precio']; ?></td>
            <td>
                <button class="btn btn-sm btn-primary">Editar</button>
                <button class="btn btn-sm btn-danger">Eliminar</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h3>Agregar Nuevo Libro</h3>
<form action="procesar_libro.php" method="POST">
    <div class="form-group">
        <label for="titulo">Título:</label>
        <input type="text" class="form-control" id="titulo" name="titulo" required>
    </div>
    <div class="form-group">
        <label for="autor">Autor:</label>
        <input type="text" class="form-control" id="autor" name="autor" required>
    </div>
    <div class="form-group">
        <label for="narrador">Narrador:</label>
        <input type="text" class="form-control" id="narrador" name="narrador">
    </div>
    <div class="form-group">
        <label for="duracion">Duración:</label>
        <input type="time" class="form-control" id="duracion" name="duracion">
    </div>
    <div class="form-group">
        <label for="precio">Precio:</label>
        <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
    </div>
    <button type="submit" class="btn btn-primary">Agregar Libro</button>
</form>
