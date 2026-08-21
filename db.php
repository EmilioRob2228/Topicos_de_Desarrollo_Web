<?php
$host = "localhost";
$db = "miapp_db";
$user = "miapp_user";
$pass = "123";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>