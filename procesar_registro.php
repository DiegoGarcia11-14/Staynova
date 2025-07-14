<?php
function mostrarMensaje($titulo, $mensaje) {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title><?php echo $titulo; ?></title>
        <meta http-equiv="refresh" content="4;url=login.php">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">
        <style>
            body {
                margin: 0;
                font-family: 'Montserrat', sans-serif;
                background: linear-gradient(135deg, #5ec2c0, #0f4c5c);
                color: #fff;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                text-align: center;
            }
            .mensaje {
                background: rgba(255, 255, 255, 0.1);
                padding: 40px;
                border-radius: 12px;
                box-shadow: 0 0 10px rgba(0,0,0,0.3);
            }
            .mensaje img {
                width: 120px;
                margin-bottom: 20px;
            }
            .mensaje h2 {
                font-size: 2em;
                margin-bottom: 10px;
                color: #ffce3d;
            }
            .mensaje p {
                font-size: 1.1em;
            }
        </style>
    </head>
    <body>
        <div class="mensaje">
            <img src="logo.png" alt="Logo STAYNOVA">
            <h2><?php echo $titulo; ?></h2>
            <p><?php echo $mensaje; ?></p>
        </div>
    </body>
    </html>
    <?php
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombres = $_POST['nombres'];
    $dni     = $_POST['dni'];
    $usuario = $_POST['usuario'];
    $correo  = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $contrasena = $_POST['contrasena'];
    $confirmar  = $_POST['confirmar_contrasena'];

    if ($contrasena !== $confirmar) {
        mostrarMensaje("Error de Contraseña", "Las contraseñas no coinciden.");
    }

    if (!preg_match('/^\d{8}$/', $dni)) {
        mostrarMensaje("Error en DNI", "El DNI debe tener exactamente 8 dígitos.");
    }

    include 'db.php';

    // Validar duplicados
    $check = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ? OR correo = ? OR dni = ?");
    $check->bind_param("sss", $usuario, $correo, $dni);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        mostrarMensaje("Usuario ya existe", "El nombre de usuario, correo o DNI ya está registrado.");
    }

    $check->close();

    // Registrar nuevo usuario
    $stmt = $conn->prepare("INSERT INTO usuarios (nombres, dni, usuario, correo, telefono, direccion, contrasena) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $hashed = password_hash($contrasena, PASSWORD_DEFAULT);
    $stmt->bind_param("sssssss", $nombres, $dni, $usuario, $correo, $telefono, $direccion, $hashed);

    if ($stmt->execute()) {
        mostrarMensaje("¡Registro exitoso!", "Serás redirigido en unos segundos...");
    } else {
        mostrarMensaje("Error al registrar", $conn->error);
    }

    $stmt->close();
    $conn->close();
} else {
    mostrarMensaje("Acceso denegado", "No tienes permiso para acceder directamente a esta página.");
}
?>