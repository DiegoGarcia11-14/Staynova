<?php
session_start();
include 'db.php';

// Validación de sesión de usuario
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'usuario') {
    header("Location: login.php");
    exit();
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $distrito = $_POST['distrito'];
    $precio = $_POST['precio'];
    $descripcion = $_POST['descripcion'];
    $tipo = $_POST['tipo'];
    $servicios = isset($_POST['servicios']) ? implode(", ", $_POST['servicios']) : "";
    $condiciones = $_POST['condiciones'];
    $telefono = $_POST['telefono'];
    $enlace_ubicacion = $_POST['enlace_ubicacion'];

    // Subida de imágenes
    $imagenes_subidas = [];
    if (isset($_FILES['imagenes'])) {
        $carpeta = "uploads/";
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        foreach ($_FILES['imagenes']['tmp_name'] as $index => $tmpName) {
            if ($_FILES['imagenes']['error'][$index] === 0) {
                $nombreImagen = basename($_FILES['imagenes']['name'][$index]);
                $ruta = $carpeta . time() . "_" . $nombreImagen;
                if (move_uploaded_file($tmpName, $ruta)) {
                    $imagenes_subidas[] = $ruta;
                }
            }
        }
    }

    if (count($imagenes_subidas) < 3) {
        $mensaje = "❌ Debes subir al menos 3 imágenes del cuarto.";
    } else {
        $imagenes = implode(",", $imagenes_subidas);
        $usuario = $_SESSION['usuario'];

        // Obtener ID del usuario
        $stmtUser = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ?");
        $stmtUser->bind_param("s", $usuario);
        $stmtUser->execute();
        $res = $stmtUser->get_result();
        $row = $res->fetch_assoc();
        $creado_por = $row['id'];
        $stmtUser->close();

        // Inserción segura
        $stmt = $conn->prepare("INSERT INTO cuartos_publicados 
            (titulo, descripcion, imagen, distrito, precio, tipo, servicios, condiciones, telefono, enlace_ubicacion, estado, creado_por)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pendiente', ?)");
        $stmt->bind_param("ssssssssssi", $titulo, $descripcion, $imagenes, $distrito, $precio, $tipo, $servicios, $condiciones, $telefono, $enlace_ubicacion, $creado_por);

        if ($stmt->execute()) {
            $mensaje = "✅ Tu cuarto fue enviado correctamente para evaluación.";
        } else {
            $mensaje = "❌ Error al enviar el cuarto: " . $conn->error;
        }

        $stmt->close();
    }
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Promocionar Cuarto - STAYNOVA</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: #0f4c5c;
            color: #fff;
            padding: 30px;
            display: flex;
            justify-content: center;
        }

        .form-container {
            background: #1c1f26;
            padding: 30px;
            border-radius: 12px;
            width: 100%;
            max-width: 650px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.5);
        }

        .form-container img {
            display: block;
            margin: 0 auto 20px;
            width: 120px;
        }

        h2 {
            text-align: center;
            color: #ffce3d;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 8px;
            border: none;
            font-size: 14px;
        }

        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .checkbox-group label {
            font-weight: normal;
        }

        .botones {
            margin-top: 25px;
            display: flex;
            gap: 10px;
        }

        .botones button, .botones a {
            flex: 1;
            padding: 12px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            font-size: 15px;
        }

        .botones .enviar {
            background-color: #28a745;
            color: white;
        }

        .botones .volver {
            background-color: #6c757d;
            color: white;
        }

        .mensaje {
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
            color: #ffd700;
        }
    </style>
</head>
<body>
<div class="form-container">
    <img src="logo.png" alt="STAYNOVA Logo">
    <h2>📢 Envía tu cuarto para evaluación</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Título del anuncio:</label>
        <input type="text" name="titulo" required>

        <label>Distrito:</label>
        <select name="distrito" required>
            <option value="">-- Selecciona --</option>
            <option value="Chincha Alta">Chincha Alta</option>
            <option value="Chincha Baja">Chincha Baja</option>
            <option value="Grocio Prado">Grocio Prado</option>
            <option value="Sunampe">Sunampe</option>
            <option value="Pueblo Nuevo">Pueblo Nuevo</option>
            <option value="Alto Laran">Alto Laran</option>
            <option value="San Juan de Yanac">San Juan de Yanac</option>
        </select>

        <label>Precio (S/):</label>
        <input type="number" name="precio" step="0.01" required>

        <label>Descripción:</label>
        <textarea name="descripcion" rows="3"placeholder="Ej. El lugar es amplio" required></textarea>

        <label>Tipo:</label>
        <select name="tipo" required>
            <option value="">-- Selecciona --</option>
            <option value="Individual">Individual</option>
            <option value="Compartido">Compartido</option>
        </select>

        <label>Servicios incluidos:</label>
        <div class="checkbox-group">
            <label><input type="checkbox" name="servicios[]" value="Agua"> Agua</label>
            <label><input type="checkbox" name="servicios[]" value="Luz"> Luz</label>
            <label><input type="checkbox" name="servicios[]" value="WiFi"> WiFi</label>
            <label><input type="checkbox" name="servicios[]" value="Cable"> Cable</label>
            <label><input type="checkbox" name="servicios[]" value="Baño propio"> Baño propio</label>
        </div>

        <label>Reglamento:</label>
        <textarea name="condiciones" rows="2" placeholder="Ej. No se permite mascotas" required></textarea>

        <label>Teléfono de contacto:</label>
        <input type="text" name="telefono" required>

        <label>Enlace de ubicación en Google Maps:</label>
        <input type="url" name="enlace_ubicacion" placeholder="https://maps.google.com/..." required>

        <label>Sube al menos 3 imágenes del cuarto:</label>
        <input type="file" name="imagenes[]" accept="image/*" multiple required>

        <div class="botones">
            <button class="enviar" type="submit">📤 Enviar para evaluación</button>
            <a class="volver" href="dashboard.php">⬅️ Volver al inicio</a>
        </div>
    </form>

    <?php if ($mensaje != "") echo "<div class='mensaje'>$mensaje</div>"; ?>
</div>
</body>
</html>
