# Sistema de Autenticación, Perfil y Roles


El sistema gestiona de forma segura la autenticación de usuarios, el control de acceso según roles (Profesor y Estudiante), el manejo de sesiones globales y la actualización de perfiles junto con el cambio seguro de contraseñas.

## Características Principales

- **Registro Seguro:** Validación en servidor de cédula (exactamente 10 dígitos numéricos) y nombre (solo letras). Control de correos duplicados.
- **Autenticación (Login):** Validación de credenciales mediante el uso de hashes seguros con `password_hash()` y `password_verify()`.
- **Manejo de Sesiones:** Restricción estricta de páginas privadas (`perfil.php`). Si no hay una sesión activa, el sistema redirige al usuario al inicio.
- **Control de Roles (RBAC):** Menú dinámico que oculta o muestra opciones dependiendo de si el usuario ingresa como *Estudiante* o *Profesor*.
- **Seguridad en Contraseñas:** Verificación de la clave actual antes de permitir el cambio, encriptación de la nueva clave y cierre de sesión automático con redirección tras 3 segundos de éxito.
- **Interfaz Limpia:** Alertas visuales dinámicas (Verde para éxitos, Rojo para errores).

## Requisitos del Sistema

Para correr este proyecto localmente, necesitas tener instalado un entorno de servidor local como **XAMPP** con las siguientes especificaciones mínimas:

- **PHP:** Versión 8.2 o superior.
- **Base de Datos:** MySQL 5.2.1.
- **Servidor Web:** Apache.
- Extensión **PDO_MYSQL** activa en PHP.

## Estructura del Proyecto

autenticacion/
│
├── config/
│   └── conexion.php      # Conexión segura a la Base de Datos mediante PDO
│
├── database/
│   └── script.sql        # Estructura exportada de la base de datos (Tablas y restricciones)
│
├── css/
│   └── estilos.css       # Estilos globales y diseño de las alertas (UI)
│
├── index.php             # Formulario de Inicio de Sesión (Login) y lógica de acceso
├── registro.php          # Formulario de Registro con validaciones avanzadas en PHP
├── perfil.php            # Zona privada común y actualización de datos básicos
├── cambiar_password.php  # Lógica de cambio de contraseña con delay de redirección
└── logout.php            # Destrucción completa de la sesión actual