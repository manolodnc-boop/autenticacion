<?php
session_start();
require_once 'config/conexion.php';
$mensaje = "";

// Si ya está logueado, mandarlo al perfil
if (isset($_SESSION['usuario_id'])) {
    header("Location: perfil.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo']);
    $password = $_POST['password'];

    if (!empty($correo) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        $usuario = $stmt->fetch();

        // Verificar contraseña usando la función segura de PHP
        if ($usuario && password_verify($password, $usuario['password'])) {
            // Crear variables de sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_correo'] = $usuario['correo'];

            // OJO: Agrega esta línea para guardar el rol del usuario
            $_SESSION['usuario_rol'] = $usuario['rol'];
            
            header("Location: perfil.php");
            exit;
        } else {
            $mensaje = "Credenciales incorrectas.";
        }
    } else {
        $mensaje = "Por favor, llena todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - UTPL</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <h2>Iniciar Sesión</h2>
    <?php 
    if (!empty($mensaje)) {
        // Creamos una lista de palabras "positivas"
        $es_exito = false;
        $palabras_clave = ['exitoso', 'correctamente', 'éxito', 'exito'];

        // Buscamos si alguna de esas palabras está en el mensaje
        foreach ($palabras_clave as $palabra) {
            if (strpos(mb_strtolower($mensaje), $palabra) !== false) {
                $es_exito = true;
                break;
            }
        }

        // Asignamos la clase según el resultado
        $clase = $es_exito ? 'mensaje-exito' : 'mensaje-error';
        echo "<p><strong class='$clase'>$mensaje</strong></p>"; 
    } 
    ?>
    <form method="POST" action="">
        <input type="email" name="correo" placeholder="Correo" required><br><br>
        <input type="password" name="password" placeholder="Contraseña" required><br><br>
        <button type="submit">Ingresar</button>
    </form>
    <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
</body>
</html>