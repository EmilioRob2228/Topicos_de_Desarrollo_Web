<?php
session_start();
require "db.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

$error = "";
$id = $_GET["id"] ?? null;

if (!$id) {
    header("Location: dashboard.php");
    exit;
}

// Traer los datos actuales del usuario
$stmt = $conn->prepare("SELECT id, nombre, email FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"]);
    $email = trim($_POST["email"]);

    if (empty($nombre) || empty($email)) {
        $error = "Todos los campos son obligatorios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "El correo no es válido.";
    } else {
        $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nombre, $email, $id);
        $stmt->execute();
        header("Location: dashboard.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editar usuario</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Editar usuario</h1>
    <?php if ($error) echo "<p style='color:red'>$error</p>"; ?>
    <form method="POST">
        Nombre: <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>"><br><br>
        Email: <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>"><br><br>
        <button type="submit">Guardar cambios</button>
    </form>
    <p><a href="dashboard.php">Cancelar</a></p>
</body>
</html>