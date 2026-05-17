<?php
// 1. SIEMPRE en la primera línea se inicia la sesión
session_start();
require_once 'config/conexion.php';

// 2. CONTROL DE ACCESO GENERAL: Si no hay sesión iniciada, al login.
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

$mensaje = "";
$usuario_id = $_SESSION['usuario_id'];

// 3. Procesar actualización de datos básicos si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_nombre = trim($_POST['nombre']);
    $nuevo_correo = trim($_POST['correo']);

    if (!empty($nuevo_nombre) && !empty($nuevo_correo)) {
        
        if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $nuevo_nombre)) {
            $mensaje = "El nombre solo puede contener letras y espacios.";
        }
        elseif (!filter_var($nuevo_correo, FILTER_VALIDATE_EMAIL)) {
            $mensaje = "Formato de correo inválido.";
        } else {
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE correo = ? AND id != ?");
            $stmt->execute([$nuevo_correo, $usuario_id]);
            
            if ($stmt->fetch()) {
                $mensaje = "Ese correo ya está en uso por otro usuario.";
            } else {
                $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, correo = ? WHERE id = ?");
                if ($stmt->execute([$nuevo_nombre, $nuevo_correo, $usuario_id])) {
                    $_SESSION['usuario_nombre'] = $nuevo_nombre;
                    $_SESSION['usuario_correo'] = $nuevo_correo;
                    $mensaje = "Datos actualizados correctamente.";
                }
            }
        }
    } else {
        $mensaje = "Los campos no pueden estar vacíos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil - Sistema UTPL</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?> 
        (<?php echo ucfirst($_SESSION['usuario_rol']); ?>)
    </h2>
    <p>Esta es tu zona privada institucional.</p>
    
    <nav>
        <a href="perfil.php">Inicio Perfil</a> | 
        
        <?php if ($_SESSION['usuario_rol'] === 'profesor'): ?>
            <a href="subir_notas.php" style="color: #dc2626; font-weight: bold;">[Panel Profesor: Subir Notas]</a> |
        <?php endif; ?>

        <?php if ($_SESSION['usuario_rol'] === 'estudiante'): ?>
            <a href="ver_notas.php" style="color: #10b981;">[Ver Mis Notas]</a> |
        <?php endif; ?>

        <a href="cambiar_password.php">Cambiar Contraseña</a> | 
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
    
    <h3>Actualizar Datos del Perfil</h3>
    <form method="POST" action="">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>" required><br><br>
        
        <label>Correo Electrónico:</label><br>
        <input type="email" name="correo" value="<?php echo htmlspecialchars($_SESSION['usuario_correo']); ?>" required><br><br>
        
        <button type="submit">Guardar Cambios</button>
    </form>
</body>
</html>