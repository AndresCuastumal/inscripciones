<?php
// Verificar si hay buffers activos y limpiarlos solo si es necesario
if (ob_get_level() > 0) {
    ob_clean();
}

header('Content-Type: text/html; charset=utf-8');

// Obtener el mensaje de la URL (usando htmlspecialchars para seguridad)
$mensaje = $_GET['mensaje'] ?? 'Advertencia: Operación duplicada';
// Verificar si el mensaje está vacío (para depuración)
if (empty($mensaje)) {
    die("Error: No se recibió el mensaje");
}

// Ahora mostramos el HTML con la alerta
?>
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>
<body>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '<?php echo $mensaje; ?>',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            window.location.href = '../../index_1.php';
        });
    </script>
</body>
</html>
<?php
exit();
?>