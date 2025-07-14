<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario - STAYNOVA</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #0f4c5c, #5ec2c0);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .registro-container {
            background-color: #ffffff;
            color: #333;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 500px;
            box-sizing: border-box;
        }

        .registro-container img {
            display: block;
            margin: 0 auto 20px;
            width: 120px;
        }

        h2 {
            text-align: center;
            color: #0f4c5c;
            margin-bottom: 20px;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin: 8px 0 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            box-sizing: border-box;
        }

        button {
            background-color: #0f4c5c;
            color: #fff;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            width: 100%;
            cursor: pointer;
            transition: 0.3s ease;
        }

        button:hover {
            background-color: #ffce3d;
            color: #000;
        }

        .enlace-login {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .enlace-login a {
            color: #0f4c5c;
            font-weight: bold;
            text-decoration: none;
        }

        .enlace-login a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="registro-container">
    <img src="logo.png" alt="Logo STAYNOVA">
    <h2>Crear cuenta en STAYNOVA</h2>

    <form action="procesar_registro.php" method="POST">
        <input type="text" name="nombres" placeholder="Nombres completos" required>
        <input type="text" name="dni" placeholder="DNI (8 dígitos)" pattern="\d{8}" maxlength="8" required title="Debe contener exactamente 8 dígitos numéricos">
        <input type="text" name="usuario" placeholder="Nombre de usuario" required>
        <input type="email" name="correo" placeholder="Correo electrónico" required>
        <input type="tel" name="telefono" placeholder="Teléfono de contacto" required>
        <input type="text" name="direccion" placeholder="Dirección" required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>
        <input type="password" name="confirmar_contrasena" placeholder="Confirmar contraseña" required>

        <button type="submit">Registrarse</button>
    </form>

    <div class="enlace-login">
        ¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>
    </div>
</div>

</body>
</html>