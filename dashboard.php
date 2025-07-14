<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'usuario') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Usuario - STAYNOVA</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #0f4c5c, #5ec2c0);
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .header {
            background-color: #0f4c5c;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
        }
        .header img {
            height: 50px;
        }
        .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }
        h1 {
            text-align: center;
            margin-top: 40px;
            font-size: 2.2em;
        }
        .contenido {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px;
            gap: 30px;
        }
        .card {
            background: white;
            color: #0f4c5c;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 8px 18px rgba(0,0,0,0.2);
            transition: transform 0.2s ease;
        }
        .card:hover {
            transform: scale(1.02);
        }
        .card h2 {
            margin-bottom: 10px;
        }
        .card p {
            margin-bottom: 20px;
        }
        .card a {
            background-color: #0f4c5c;
            color: white;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
        }
        .card a:hover {
            background-color: #ffce3d;
            color: #000;
        }
    </style>
</head>
<body>

<div class="header">
    <img src="logo.png" alt="STAYNOVA Logo">
    <a href="logout.php" class="logout-btn">🔓 Cerrar sesión</a>
</div>

<h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?> 👋</h1>

<div class="contenido">
    <div class="card">
        <h2>📢 Promociona tu Cuarto</h2>
        <p>Envía tu anuncio para que el administrador lo revise antes de publicarse.</p>
        <a href="formulario_promocion.php">Promocionar Cuarto</a>
    </div>

    <div class="card">
        <h2>🔍 Ver Cuartos Disponibles</h2>
        <p>Explora todos los cuartos que han sido aprobados por el sistema.</p>
        <a href="index.php">Ver Cuartos</a>
    </div>
</div>

</body>
</html>