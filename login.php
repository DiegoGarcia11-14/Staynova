<?php
session_start();
if (isset($_SESSION['usuario']) && isset($_SESSION['rol'])) {
    if ($_SESSION['rol'] === 'admin') {
        header("Location: admin.panel.php");
    } else {
        header("Location: dashboard.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso a STAYNOVA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: radial-gradient(circle at top left, #0f4c5c, #1b262c);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-box {
            background: #ffffff10;
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            border: 2px solid #ffffff22;
            box-shadow: 0 8px 25px rgba(0,0,0,0.25);
        }
        .login-box img {
            width: 80px;
            margin-bottom: 20px;
        }
        .login-box h2 {
            margin-bottom: 25px;
            color: #ffce3d;
        }
        .login-box input[type="text"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: none;
            font-size: 15px;
            background: #fff;
            color: #333;
        }
        .toggle-password {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #0f4c5c;
        }
        .password-container {
            position: relative;
        }
        .login-box button {
            background-color: #ffce3d;
            color: #0f4c5c;
            font-weight: bold;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 8px;
            margin-top: 15px;
            cursor: pointer;
            font-size: 16px;
        }
        .login-box button:hover {
            background-color: #ffc107;
        }
        .login-box .links {
            margin-top: 20px;
            font-size: 14px;
        }
        .login-box .links a {
            color: #fff;
            text-decoration: underline;
        }
        .login-box .links a:hover {
            color: #ffce3d;
        }
        .error {
            background: #ff4d4d;
            padding: 10px;
            color: white;
            border-radius: 8px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <img src="logo.png" alt="STAYNOVA">
    <h2>Iniciar sesión</h2>

    <form method="POST" action="procesar_login.php">
        <input type="text" name="usuario" placeholder="Usuario o correo" required>
        <div class="password-container">
            <input type="password" name="contrasena" id="contrasena" placeholder="Contraseña" required>
            <span class="toggle-password" onclick="togglePassword()">👁️</span>
        </div>
        <button type="submit">Ingresar</button>
    </form>

    <?php if (isset($_GET['error'])): ?>
        <div class="error">
            <?php
                if ($_GET['error'] == 1) {
                    echo "Usuario o contraseña incorrectos.";
                } elseif ($_GET['error'] == "rol") {
                    echo "Tu cuenta no tiene un rol asignado. Contacta al administrador.";
                }
            ?>
        </div>
    <?php endif; ?>

    <div class="links">
        <p><a href="recuperar.php">¿Olvidaste tu contraseña?</a></p>
        <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById("contrasena");
        const toggleIcon = document.querySelector(".toggle-password");
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.textContent = "🙈";
        } else {
            passwordInput.type = "password";
            toggleIcon.textContent = "👁️";
        }
    }
</script>

</body>
</html>