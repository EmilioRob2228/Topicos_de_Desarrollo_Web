<?php
require "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($nombre) || empty($email) || empty($password)) {
        $error = "Todos los campos son obligatorios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "El correo no es válido.";
    } elseif (strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $email, $hash);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit;
        } else {
            $error = "Error: el correo ya existe o hubo un problema.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Registro</h1>
    <?php if ($error) echo "<p style='color:red'>$error</p>"; ?>
    <form method="POST">
        Nombre: <input type="text" name="nombre"><br><br>
        Email: <input type="email" name="email"><br><br>
        Contraseña: <input type="password" name="password"><br><br>
        <button type="submit">Registrarse</button>
    </form>
    <p><a href="login.php">Ya tengo cuenta</a></p>
</body>
</html>