<?php
session_start();
// Vaciar el array de sesión
$_SESSION = array();
// Destruir la sesión en el servidor
session_destroy();
// Redirigir al inicio
header("Location: index.php");
exit;
?>