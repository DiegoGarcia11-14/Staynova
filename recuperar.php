<?php
session_start();
include 'db.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $nueva_contrasena = $_POST['nueva_contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    if ($nueva_contrasena !== $confirmar_contrasena) {
        $mensaje = "Las contraseñas no coinciden.";
    } else {
        $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
        $resultado = $conn->query($sql);

        if ($resultado->num_rows > 0) {
            $hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
            $update = "UPDATE usuarios SET contrasena = '$hash' WHERE usuario = '$usuario'";
            if ($conn->query($update)) {
                $mensaje = "Contraseña actualizada correctamente.";
            } else {
                $mensaje = "Error al actualizar la contraseña.";
            }
        } else {
            $mensaje = "Usuario no encontrado.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña - STAYNOVA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #5ec2c0, #0f4c5c);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
        }

        .form-container {
            background: #fff;
            color: #333;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 14px;
            color: #0f4c5c;
        }

        button {
            background-color: #0f4c5c;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #ffce3d;
            color: black;
        }

        .mensaje {
            margin-top: 15px;
            font-weight: bold;
            color: red;
        }

        .volver-btn {
            margin-top: 15px;
            background-color: #ccc;
            color: #000;
        }

        .volver-btn:hover {
            background-color: #999;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Recuperar Contraseña</h2>

        <form method="POST" action="">
            <input type="text" name="usuario" placeholder="Nombre de usuario" required>

            <div class="password-container">
                <input type="password" name="nueva_contrasena" id="nueva_contrasena" placeholder="Nueva contraseña" required>
                <span class="toggle-password" onclick="togglePassword('nueva_contrasena', this)">👁️</span>
            </div>

            <div class="password-container">
                <input type="password" name="confirmar_contrasena" id="confirmar_contrasena" placeholder="Confirmar contraseña" required>
                <span class="toggle-password" onclick="togglePassword('confirmar_contrasena', this)">👁️</span>
            </div>

            <button type="submit">Actualizar contraseña</button>
        </form>

        <form action="login.php" method="get">
            <button class="volver-btn" type="submit">← Volver al Login</button>
        </form>

        <?php if ($mensaje != "") echo "<p class='mensaje'>$mensaje</p>"; ?>
    </div>

    <script>
        function togglePassword(id, icon) {
            const input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
                icon.textContent = "🙈";
            } else {
                input.type = "password";
                icon.textContent = "👁️";
            }
        }
    </script>
</body>
</html>
