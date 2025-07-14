<?php
session_start();

// Opcional: guardar en log quién cerró sesión
if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
    $rol = $_SESSION['rol'];
    // file_put_contents("log.txt", "$usuario ($rol) cerró sesión el " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
}

session_unset();
session_destroy();

header("Location: login.php");
exit();
?>