<?php
session_start();
include 'db.php';

if (!isset($_SESSION['usuario']) || !in_array($_SESSION['rol'], ['usuario', 'admin'])) {
    header("Location: login.php");
    exit;
}

$distritoFiltro = $_GET['distrito'] ?? '';
$sql = "SELECT * FROM cuartos_publicados WHERE estado = 'aprobado'";
if (!empty($distritoFiltro)) {
    $sql .= " AND distrito = '" . $conn->real_escape_string($distritoFiltro) . "'";
}
$sql .= " ORDER BY id DESC";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cuartos disponibles - STAYNOVA</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            background: #f2f4f8;
        }
        .header {
            background-color: #0f4c5c;
            padding: 15px 30px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .header img {
            height: 45px;
        }
        .btn-volver {
            background-color: #ffce3d;
            color: #000;
            padding: 10px 16px;
            font-weight: bold;
            border-radius: 8px;
            text-decoration: none;
        }
        .filtro-distrito {
            padding: 20px;
            text-align: center;
            background-color: #e0f2f1;
            border-bottom: 1px solid #ccc;
        }
        .filtro-distrito select {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #0f4c5c;
            font-size: 16px;
            background-color: #fff;
            color: #0f4c5c;
        }
        .cuartos-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            padding: 30px;
        }
        .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s;
        }
        .card:hover {
            transform: scale(1.01);
        }
        .card .image-grid {
            display: flex;
            overflow-x: auto;
            gap: 8px;
        }
        .card .image-grid img {
            height: 160px;
            object-fit: cover;
            cursor: pointer;
            border-radius: 4px;
        }
        .card-content {
            padding: 15px;
        }
        .card-title {
            font-weight: bold;
            font-size: 1.1em;
            color: #0f4c5c;
            margin-bottom: 5px;
        }
        .info-basica {
            font-size: 13px;
            color: #333;
            margin-bottom: 5px;
        }
        .price {
            color: #00796b;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .ver-mas {
            color: #0f4c5c;
            cursor: pointer;
            font-weight: 600;
            margin-top: 8px;
            display: inline-block;
        }
        .detalle-extra {
            display: none;
            margin-top: 10px;
            font-size: 13px;
            line-height: 1.4;
        }
        .comentario-box {
            margin-top: 12px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .comentario-box textarea {
            width: 100%;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-top: 6px;
            font-family: 'Montserrat';
            resize: vertical;
        }
        .comentario-box button {
            margin-top: 8px;
            background-color: #0f4c5c;
            color: #fff;
            border: none;
            padding: 6px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }
        .comentario-box button:hover {
            background-color: #ffce3d;
            color: #000;
        }
        .rating input[type="radio"] {
            display: none;
        }
        .rating label span {
            font-size: 20px;
            cursor: pointer;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.8);
            align-items: center;
            justify-content: center;
        }
        .modal img {
            max-width: 90%;
            max-height: 80vh;
        }
        .modal .close {
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 30px;
            color: white;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="header">
    <img src="logo.png" alt="STAYNOVA">
    <a href="dashboard.php" class="btn-volver">← Volver al panel</a>
</div>

<?php if ($_SESSION['rol'] === 'admin'): ?>
    <div style="background:#ffce3d; color:#000; text-align:center; padding:8px; font-weight:bold;">
        Estás viendo la página como usuario (Administrador).
    </div>
<?php endif; ?>

<div class="filtro-distrito">
    <form method="GET">
        <label><strong>🔎 Filtrar por distrito:</strong></label>
        <select name="distrito" onchange="this.form.submit()">
            <option value="">Todos</option>
            <?php
            $distritos = [
                "Alto Larán", "Chincha Alta", "Chincha Baja", "El Carmen", "Grocio Prado",
                "Pueblo Nuevo", "San Juan de Yanac", "San Pedro de Huacarpana", "Sunampe", "Tambo de Mora"
            ];
            foreach ($distritos as $distrito) {
                $selected = ($distritoFiltro === $distrito) ? 'selected' : '';
                echo "<option value=\"$distrito\" $selected>$distrito</option>";
            }
            ?>
        </select>
    </form>
</div>

<div class="cuartos-container">
<?php while($cuarto = $resultado->fetch_assoc()): ?>
    <?php $imagenes = explode(",", $cuarto['imagen']); ?>
    <div class="card">
        <div class="image-grid">
            <?php foreach ($imagenes as $img): ?>
                <img src="<?= htmlspecialchars($img) ?>" onclick="verImagen(this.src)" alt="Imagen del cuarto">
            <?php endforeach; ?>
        </div>
        <div class="card-content">
            <div class="card-title"><?= htmlspecialchars($cuarto['titulo']) ?></div>
            <div class="info-basica">📍 <?= htmlspecialchars($cuarto['distrito']) ?></div>
            <div class="info-basica">🛏️ <?= htmlspecialchars($cuarto['tipo']) ?></div>
            <div class="price">S/ <?= number_format($cuarto['precio'], 2) ?></div>
            <span class="ver-mas" onclick="toggleDetalle(this)">Ver más ⯈</span>
            <div class="detalle-extra">
                <p><strong>Servicios:</strong> <?= htmlspecialchars($cuarto['servicios']) ?></p>
                <p><strong>Condiciones:</strong> <?= htmlspecialchars($cuarto['condiciones']) ?></p>
                <p><strong>Teléfono:</strong> <?= htmlspecialchars($cuarto['telefono']) ?></p>
                <p><strong>Descripción:</strong><br><?= nl2br(htmlspecialchars($cuarto['descripcion'])) ?></p>
                <?php if (!empty($cuarto['enlace_ubicacion'])): ?>
                    <p><a href="<?= htmlspecialchars($cuarto['enlace_ubicacion']) ?>" target="_blank" style="color:#00796b;font-weight:bold;text-decoration:none;">📍 Ver ubicación en Google Maps</a></p>
                <?php endif; ?>
                <?php
                    $id_cuarto = $cuarto['id'];
                    $comentarios = $conn->query("SELECT * FROM comentarios WHERE cuarto_id = $id_cuarto ORDER BY fecha DESC");
                ?>
                <?php if ($comentarios->num_rows > 0): ?>
                    <div style="margin-top:10px; font-size:13px;">
                        <strong>Comentarios:</strong>
                        <?php while ($c = $comentarios->fetch_assoc()): ?>
                            <div style="margin-top:5px; background:#f8f9fa; padding:6px 10px; border-radius:6px;">
                                <strong><?= htmlspecialchars($c['usuario']) ?></strong> (<?= $c['calificacion'] ?>★):<br>
                                <?= nl2br(htmlspecialchars($c['comentario'])) ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>
                <form action="guardar_comentario.php" method="POST" class="comentario-box">
                    <input type="hidden" name="cuarto_id" value="<?= $cuarto['id'] ?>">
                    <div class="rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <label>
                                <input type="radio" name="calificacion" value="<?= $i ?>" required>
                                <span style="color:#ffce3d;">★</span>
                            </label>
                        <?php endfor; ?>
                    </div>
                    <textarea name="comentario" placeholder="Escribe tu comentario..." required></textarea>
                    <button type="submit">Enviar comentario</button>
                </form>
            </div>
        </div>
    </div>
<?php endwhile; ?>
</div>

<div class="modal" id="modalImagen">
    <span class="close" onclick="cerrarModal()">×</span>
    <img id="imgGrande" src="" alt="Vista grande">
</div>

<script>
function verImagen(src) {
    document.getElementById("imgGrande").src = src;
    document.getElementById("modalImagen").style.display = "flex";
}
function cerrarModal() {
    document.getElementById("modalImagen").style.display = "none";
}
window.onclick = function(e) {
    if (e.target === document.getElementById("modalImagen")) {
        cerrarModal();
    }
}
function toggleDetalle(elem) {
    const extra = elem.nextElementSibling;
    if (extra.style.display === "block") {
        extra.style.display = "none";
        elem.innerText = "Ver más ⯈";
    } else {
        extra.style.display = "block";
        elem.innerText = "Ver menos ⯆";
    }
}
</script>

</body>
</html>