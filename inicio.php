<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido a STAYNOVA</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #5ec2c0, #0f4c5c);
            color: #fff;
        }

        .inicio-container {
            text-align: center;
            padding: 80px 20px;
        }

        .logo {
            width: 180px;
            margin-bottom: 30px;
            filter: drop-shadow(2px 2px 5px #000);
        }

        h1 {
            font-size: 2.8em;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .descripcion {
            font-size: 1.3em;
            margin: 0 auto 40px;
            max-width: 800px;
            line-height: 1.6;
            text-align: justify;
            text-align-last: center;
        }

        .botones {
            margin-top: 30px;
        }

        .botones a {
            display: inline-block;
            margin: 10px;
            padding: 14px 28px;
            font-size: 16px;
            font-weight: bold;
            background-color: #ffffff;
            color: #0f4c5c;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .botones a:hover {
            background-color: #ffce3d;
            color: #000;
        }
    </style>
</head>
<body>

<div class="inicio-container">
    <img src="logo.png" alt="Logo STAYNOVA" class="logo">

    <h1>Bienvenido a <span style="color:#ffce3d;">STAYNOVA</span></h1>

    <p class="descripcion">
        <strong>¿Qué es STAYNOVA?</strong><br><br>
        STAYNOVA es una plataforma digital que conecta a pequeños arrendadores con personas que buscan hospedaje cómodo, seguro y económico. 
        Facilita la promoción de habitaciones y la búsqueda personalizada de cuartos según ubicación, precio y servicios. Además, permite a los usuarios 
        explorar opciones, ver fotos y opiniones, registrarse, iniciar sesión y publicar anuncios revisados por un administrador. 
        <br><br>Promociona tu cuarto o encuentra el lugar ideal para tu próxima estadía.
    </p>

    <div class="botones">
        <a href="registro.php">📝 Registrarse</a>
        <a href="login.php">🔐 Iniciar Sesión</a>
    </div>
</div>

</body>
</html>