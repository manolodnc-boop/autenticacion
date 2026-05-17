<?php
session_start();
require_once 'config/conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

$mensaje = "";
$usuario_id = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass_actual = $_POST['pass_actual'];
    $pass_nueva = $_POST['pass_nueva'];
    $pass_confirmar = $_POST['pass_confirmar'];

    if (!empty($pass_actual) && !empty($pass_nueva) && !empty($pass_confirmar)) {
        if ($pass_nueva !== $pass_confirmar) {
            $mensaje = "La nueva contraseña y la confirmación no coinciden.";
        } else {
            // Traer la contraseña actual (el hash) desde la BD
            $stmt = $pdo->prepare("SELECT password FROM usuarios WHERE id = ?");
            $stmt->execute([$usuario_id]);
            $usuario = $stmt->fetch();

            // Verificar si la contraseña actual ingresada es correcta
            if ($usuario && password_verify($pass_actual, $usuario['password'])) {
                // Crear hash de la nueva contraseña
                $nuevo_hash = password_hash($pass_nueva, PASSWORD_BCRYPT);
                
                // Actualizar en BD
                $stmt = $pdo->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
                if ($stmt->execute([$nuevo_hash, $usuario_id])) {
                    $mensaje = "Contraseña cambiada con éxito. Redireccionando al inicio...";
                    
                    // REDIRECCIÓN CON RETRASO: Espera 3 segundos y lo manda a logout.php 
                    // para destruir la sesión vieja antes de ir al login.
                    header("refresh:3;url=logout.php");
                }
            } else {
                $mensaje = "La contraseña actual es incorrecta.";
            }
        }
    } else {
        $mensaje = "Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Contraseña</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <h2>Cambiar Contraseña</h2>
    <nav>
        <a href="perfil.php">Volver al Perfil</a> | 
        <a href="logout.php">Cerrar Sesión</a>
    </nav>
    <hr>

    <?php 
    if (!empty($mensaje)) {
        $es_exito = false;
        $palabras_clave = ['exitoso', 'correctamente', 'éxito', 'exito'];

        foreach ($palabras_clave as $palabra) {
            if (strpos(mb_strtolower($mensaje), $palabra) !== false) {
                $es_exito = true;
                break;
            }
        }

        $clase = $es_exito ? 'mensaje-exito' : 'mensaje-error';
        echo "<p><strong class='$clase'>$mensaje</strong></p>"; 
    } 
    ?>

    <form method="POST" action="">
        <input type="password" name="pass_actual" placeholder="Contraseña Actual" required <?php if(isset($es_exito) && $es_exito) echo 'disabled'; ?>><br><br>
        <input type="password" name="pass_nueva" placeholder="Nueva Contraseña" required <?php if(isset($es_exito) && $es_exito) echo 'disabled'; ?>><br><br>
        <input type="password" name="pass_confirmar" placeholder="Confirmar Nueva Contraseña" required <?php if(isset($es_exito) && $es_exito) echo 'disabled'; ?>><br><br>
        <button type="submit" <?php if(isset($es_exito) && $es_exito) echo 'disabled'; ?>>Actualizar Contraseña</button>
    </form>
</body>
</html>