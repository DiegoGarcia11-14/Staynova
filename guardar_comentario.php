<?php
session_start();
include 'db.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'usuario') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cuarto_id = intval($_POST['cuarto_id']);
    $comentario = trim($_POST['comentario']);
    $calificacion = intval($_POST['calificacion']);
    $usuario = $_SESSION['usuario'];

    if ($comentario !== "" && $calificacion >= 1 && $calificacion <= 5) {
        $stmt = $conn->prepare("INSERT INTO comentarios (cuarto_id, usuario, comentario, calificacion, fecha) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("issi", $cuarto_id, $usuario, $comentario, $calificacion);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: index.php");
    exit();
} else {
    echo "Acceso no permitido.";
}