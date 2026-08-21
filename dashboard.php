<?php
session_start();
require "db.php";

// Si no hay sesión activa, regresa al login
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

$result = $conn->query("SELECT id, nombre, email, fecha_registro FROM usuarios");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION["usuario_nombre"]); ?></h1>
    <p><a href="logout.php">Cerrar sesión</a></p>

    <h2>Usuarios registrados</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Fecha registro</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row["id"]; ?></td>
            <td><?php echo htmlspecialchars($row["nombre"]); ?></td>
            <td><?php echo htmlspecialchars($row["email"]); ?></td>
            <td><?php echo $row["fecha_registro"]; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $row['id']; ?>">Editar</a> |
                <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <p><a href="register.php">Agregar nuevo usuario</a></p>
</body>
</html>