<?php
session_start();
include 'db.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Aprobación o rechazo con prepared statements
if (isset($_GET['aprobar'])) {
    $id = intval($_GET['aprobar']);
    $stmt = $conn->prepare("UPDATE cuartos_publicados SET estado='aprobado' WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}
if (isset($_GET['rechazar'])) {
    $id = intval($_GET['rechazar']);
    $stmt = $conn->prepare("DELETE FROM cuartos_publicados WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $stmt = $conn->prepare("DELETE FROM cuartos_publicados WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

$res = $conn->query("SELECT cp.*, u.nombres AS nombre_usuario FROM cuartos_publicados cp JOIN usuarios u ON cp.creado_por = u.id ORDER BY cp.estado DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrador | STAYNOVA</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #5ec2c0, #0f4c5c);
            color: #fff;
        }
        header {
            background: #0f4c5c;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header img {
            height: 50px;
        }
        header .btns a {
            background-color: #ffce3d;
            padding: 10px 16px;
            border-radius: 8px;
            color: #000;
            font-weight: bold;
            text-decoration: none;
            margin-left: 10px;
        }
        h2 {
            text-align: center;
            margin-top: 30px;
            font-size: 2em;
            color: #ffce3d;
        }
        .cuarto {
            background: #fff;
            color: #0f4c5c;
            margin: 20px auto;
            padding: 25px;
            border-radius: 12px;
            width: 90%;
            max-width: 800px;
            box-shadow: 0 8px 18px rgba(0,0,0,0.2);
        }
        .cuarto h3 {
            margin-top: 0;
            color: #0f4c5c;
        }
        .acciones a {
            padding: 10px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            margin-right: 10px;
        }
        .aprobar { background-color: #28a745; color: white; }
        .rechazar { background-color: #dc3545; color: white; }
        .eliminar { background-color: #343a40; color: white; }
        .imagenes img {
            width: 80px;
            margin: 5px;
            border-radius: 6px;
            cursor: pointer;
        }
        .imagenes img:hover {
            transform: scale(1.1);
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            padding-top: 80px;
            left: 0; top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.8);
        }
        .modal-content {
            margin: auto;
            display: block;
            max-width: 90%;
        }
        .close {
            position: absolute;
            top: 20px; right: 40px;
            color: white;
            font-size: 35px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>

<header>
    <img src="logo.png" alt="STAYNOVA Logo">
    <div class="btns">
        <a href="index.php" target="_blank">Ver como usuario</a>
        <a href="logout.php">Cerrar sesión</a>
    </div>
</header>

<h2>🛡️ Panel de Administración</h2>

<?php while ($row = $res->fetch_assoc()): ?>
<div class="cuarto">
    <h3>🏠 <?= htmlspecialchars($row['titulo']) ?> - <strong>S/<?= $row['precio'] ?></strong></h3>
    <p><strong>📍 Distrito:</strong> <?= htmlspecialchars($row['distrito']) ?></p>
    <p><strong>🛏️ Tipo:</strong> <?= htmlspecialchars($row['tipo']) ?></p>
    <p><strong>💡 Servicios:</strong> <?= htmlspecialchars($row['servicios']) ?></p>
    <p><strong>📜 Condiciones:</strong> <?= htmlspecialchars($row['condiciones']) ?></p>
    <p><strong>📞 Teléfono:</strong> <?= htmlspecialchars($row['telefono']) ?></p>
    <p><strong>📝 Descripción:</strong><br><?= nl2br(htmlspecialchars($row['descripcion'])) ?></p>
    <?php if (!empty($row['enlace_ubicacion'])): ?>
        <p><strong>🌐 Ubicación:</strong> <a href="<?= htmlspecialchars($row['enlace_ubicacion']) ?>" target="_blank">Ver en Google Maps</a></p>
    <?php endif; ?>
    <p><strong>👤 Publicado por:</strong> <?= htmlspecialchars($row['nombre_usuario']) ?></p>
    <p><strong>🖼️ Imágenes:</strong></p>
    <div class="imagenes">
        <?php foreach (explode(',', $row['imagen']) as $img): ?>
            <img src="<?= htmlspecialchars($img) ?>" alt="img" onclick="verImagen(this.src)">
        <?php endforeach; ?>
    </div>
    <div class="acciones">
        <?php if ($row['estado'] === 'pendiente'): ?>
            <a class="aprobar" href="?aprobar=<?= $row['id'] ?>">✅ Aprobar</a>
            <a class="rechazar" href="?rechazar=<?= $row['id'] ?>">❌ Rechazar</a>
        <?php endif; ?>
        <a class="eliminar" href="?eliminar=<?= $row['id'] ?>" onclick="return confirm('¿Estás seguro de eliminar esta publicación?')">🗑️ Eliminar</a>
    </div>
</div>
<?php endwhile; ?>

<!-- Modal para ampliar imagen -->
<div id="modalImg" class="modal">
    <span class="close" onclick="cerrarModal()">&times;</span>
    <img class="modal-content" id="imgGrande">
</div>

<script>
function verImagen(src) {
    document.getElementById("modalImg").style.display = "block";
    document.getElementById("imgGrande").src = src;
}
function cerrarModal() {
    document.getElementById("modalImg").style.display = "none";
}
window.onclick = function(e) {
    if (e.target === document.getElementById("modalImg")) cerrarModal();
}
</script>

</body>
</html>
