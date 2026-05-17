<?php
require_once 'config/conexion.php';
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = trim($_POST['cedula']);
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $password = $_POST['password'];

    // 1. Validar campos vacíos
    if (empty($cedula) || empty($nombre) || empty($correo) || empty($password)) {
        $mensaje = "Todos los campos son obligatorios.";
    } 
    // 2. VALIDACIÓN DE CÉDULA: Solo números y exactamente 10 dígitos
    elseif (!preg_match('/^[0-9]{10}$/', $cedula)) {
        $mensaje = "La cédula debe contener exactamente 10 dígitos numéricos.";
    }
    // 3. VALIDACIÓN DE NOMBRE: Solo letras, espacios, acentos y Ñ
    elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $nombre)) {
        $mensaje = "El nombre solo puede contener letras y espacios.";
    }
    // 4. Validar formato de correo
    elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "Formato de correo inválido.";
    } else {
        
        // 5. NUEVA VALIDACIÓN: Verificar si la CÉDULA ya existe
        $stmt_cedula = $pdo->prepare("SELECT id FROM usuarios WHERE cedula = ?");
        $stmt_cedula->execute([$cedula]);
        
        if ($stmt_cedula->fetch()) {
            $mensaje = "La cédula ingresada ya está registrada en el sistema.";
        } else {
            
            // 6. Verificar si el CORREO ya existe (Ya la tenías)
            $stmt_correo = $pdo->prepare("SELECT id FROM usuarios WHERE correo = ?");
            $stmt_correo->execute([$correo]);
            
            if ($stmt_correo->fetch()) {
                $mensaje = "El correo ya está registrado.";
            } else {
                
                // Si pasa todas las validaciones, procedemos a guardar de forma segura
                $passwordHash = password_hash($password, PASSWORD_BCRYPT);
                
                $stmt = $pdo->prepare("INSERT INTO usuarios (cedula, nombre, correo, password) VALUES (?, ?, ?, ?)");
                if ($stmt->execute([$cedula, $nombre, $correo, $passwordHash])) {
                    $mensaje = "Registro exitoso. ¡Ya puedes iniciar sesión!";
                } else {
                    $mensaje = "Error al registrar al usuario.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - UTPL</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <h2>Registro de Usuario</h2>
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
        <input type="text" name="cedula" placeholder="Cédula" required><br><br>
        <input type="text" name="nombre" placeholder="Nombre Completo" required><br><br>
        <input type="email" name="correo" placeholder="Correo Electrónico" required><br><br>
        <input type="password" name="password" placeholder="Contraseña" required><br><br>
        <button type="submit">Registrar</button>
    </form>
    <p>¿Ya tienes cuenta? <a href="index.php">Inicia sesión aquí</a></p>
</body>
</html>