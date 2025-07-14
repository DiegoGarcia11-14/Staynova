<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "staynova";

// Crear conexión
$conn = new mysqli($host, $user, $pass, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    echo '
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Error de Conexión</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f8f9fa;
                color: #333;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
                margin: 0;
            }
            .error-box {
                max-width: 500px;
                padding: 30px;
                border: 2px solid #dc3545;
                background-color: #fff3f3;
                border-radius: 12px;
                text-align: center;
            }
            .error-box h2 {
                color: #c0392b;
            }
            .error-box img {
                width: 100px;
                margin-bottom: 15px;
            }
        </style>
    </head>
    <body>
        <div class="error-box">
            <img src="img/error.png" alt="Error de conexión">
            <h2>Error al conectar con la base de datos</h2>
            <p>Detalles técnicos: ' . htmlspecialchars($conn->connect_error) . '</p>
            <p>Revisa el nombre de la base de datos o si el servidor MySQL está activo.</p>
        </div>
    </body>
    </html>';
    exit();
}
?>
