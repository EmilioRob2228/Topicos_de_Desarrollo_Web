<?php
session_start();
require "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {
        $error = "Todos los campos son obligatorios.";
    } else {
        // Prepared statement, evita inyección SQL
        $stmt = $conn->prepare("SELECT id, nombre, password FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $usuario = $result->fetch_assoc();

            // Verifica el hash, nunca comparamos texto plano
            if (password_verify($password, $usuario["password"])) {
                $_SESSION["usuario_id"] = $usuario["id"];
                $_SESSION["usuario_nombre"] = $usuario["nombre"];
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "El usuario no existe.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Iniciar sesión</h1>
    <?php if ($error) echo "<p style='color:red'>$error</p>"; ?>
    <form method="POST">
        Email: <input type="email" name="email"><br><br>
        Contraseña: <input type="password" name="password"><br><br>
        <button type="submit">Entrar</button>
    </form>
    <p><a href="register.php">Crear cuenta</a></p>
</body>
</html>