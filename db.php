<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "staynova";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo '
    <html>
    <head>
        <title>Error de Conexión</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #fefefe;
                color: #333;
                text-align: center;
                padding: 50px;
            }
            img {
                width: 150px;
                margin-bottom: 20px;
            }
            .error-box {
                display: inline-block;
                padding: 20px;
                border: 2px solid #e74c3c;
                background-color: #fbeaea;
                border-radius: 10px;
            }
        </style>
    </head>
    <body>
        <div class="error-box">
            <img src="img/error.png" alt="Error">
            <h2>¡Error al conectar con la base de datos!</h2>
            <p>Detalles técnicos: ' . $conn->connect_error . '</p>
            <p>Verifica tu servidor MySQL o el nombre de tu base de datos.</p>
        </div>
    </body>
    </html>';
    exit();
}
?>