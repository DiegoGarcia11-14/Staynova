<?php
session_start();
include 'db.php';

// Validar sesión de usuario
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'usuario') {
    header("Location: login.php");
    exit();
}

// Obtener ID del usuario
$usuario = $_SESSION['usuario'];
$res = $conn->query("SELECT id FROM usuarios WHERE usuario = '$usuario'");
$row = $res->fetch_assoc();
$id_usuario = $row['id'];

// Consultar cuartos del usuario
$sql = "SELECT * FROM cuartos_publicados WHERE creado_por = $id_usuario ORDER BY id DESC";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Cuartos - STAYNOVA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef2f3;
            padding: 30px;
        }
        h2 {
            text-align: center;
            color: #0f4c5c;
        }
        .cuarto {
            background: white;
            padding: 20px;
            margin: 15px auto;
            border-radius: 10px;
            max-width: 800px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .cuarto h3 {
            color: #333;
            margin-bottom: 10px;
        }
        .cuarto p {
            margin: 5px 0;
        }
        .imagenes {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        .imagenes img {
            width: 150px;
            height: 100px;
            object-fit: cover;
            border-radius: 6px;
        }
        .estado {
            font-weight: bold;
            color: #007bff;
        }
    </style>
</head>
<body>
    <h2>📋 Mis Cuartos Publicados</h2>

    <?php if ($resultado->num_rows > 0): ?>
        <?php while ($cuarto = $resultado->fetch_assoc()): ?>
            <div class="cuarto">
                <h3><?= htmlspecialchars($cuarto['titulo']) ?></h3>
                <p><strong>Distrito:</strong> <?= htmlspecialchars($cuarto['distrito']) ?></p>
                <p><strong>Precio:</strong> S/ <?= number_format($cuarto['precio'], 2) ?></p>
                <p><strong>Tipo:</strong> <?= htmlspecialchars($cuarto['tipo']) ?></p>
                <p><strong>Servicios:</strong> <?= htmlspecialchars($cuarto['servicios']) ?></p>
                <p><strong>Condiciones:</strong> <?= htmlspecialchars($cuarto['condiciones']) ?></p>
                <p><strong>Teléfono:</strong> <?= htmlspecialchars($cuarto['telefono']) ?></p>
                <p class="estado"><strong>Estado:</strong> <?= ucfirst($cuarto['estado']) ?></p>

                <?php if (!empty($cuarto['imagen'])): ?>
                    <div class="imagenes">
                        <?php
                        $imagenes = explode(",", $cuarto['imagen']);
                        foreach ($imagenes as $img) {
                            echo "<img src='" . htmlspecialchars($img) . "' alt='Imagen del cuarto'>";
                        }
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center;">❌ Aún no has publicado ningún cuarto.</p>
    <?php endif; ?>
</body>
</html>