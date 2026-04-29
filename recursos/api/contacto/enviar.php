<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../config/conexion.php';
require_once __DIR__ . '/../../../config/logger.php';
require_once __DIR__ . '/../../../config/mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'mensaje' => 'Método no permitido.']);
    exit;
}

$nombre  = trim($_POST['nombre']  ?? '');
$email   = trim($_POST['email']   ?? '');
$asunto  = trim($_POST['asunto']  ?? '');
$mensaje = trim($_POST['mensaje'] ?? '');

if (!$nombre || !$email || !$mensaje) {
    echo json_encode(['success' => false, 'mensaje' => 'Por favor complete todos los campos obligatorios.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'mensaje' => 'El correo electrónico no es válido.']);
    exit;
}

if (mb_strlen($mensaje) < 10) {
    echo json_encode(['success' => false, 'mensaje' => 'El mensaje es demasiado corto.']);
    exit;
}

// ── Guardar en BD ─────────────────────────────────────────────────────────────
$stmt = $conexion->prepare(
    "INSERT INTO contacto (nombre, email, asunto, mensaje) VALUES (?, ?, ?, ?)"
);
$stmt->bind_param('ssss', $nombre, $email, $asunto, $mensaje);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'mensaje' => 'Error al guardar el mensaje. Por favor inténtelo de nuevo.']);
    $stmt->close();
    exit;
}

$nuevo_id = $conexion->insert_id;
$stmt->close();

registrar_log($conexion, null, 'SISTEMA', "Nuevo mensaje de contacto de: $nombre <$email>", 'contacto', $nuevo_id);

// ── Enviar email via PHPMailer ────────────────────────────────────────────────
$empRow = $conexion->query(
    "SELECT nombre, email, telefono, ciudad, pais, logo, logo_blanco FROM empresa WHERE id = 1 LIMIT 1"
)->fetch_assoc();

$destino = $empRow['email'] ?? null;

if ($destino && filter_var($destino, FILTER_VALIDATE_EMAIL)) {
    try {
        // Construir URL del logo para el email (usar logo principal, si no el blanco)
        $logoPath = $empRow['logo'] ?: ($empRow['logo_blanco'] ?? '');
        $logoUrl  = '';
        if ($logoPath) {
            // URL absoluta para que el cliente de correo pueda cargar la imagen
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host     = $_SERVER['HTTP_HOST'] ?? 'egidra.com';
            $logoUrl  = "$protocol://$host/" . ltrim($logoPath, '/');
        }

        $campos  = compact('nombre', 'email', 'asunto', 'mensaje');
        $empresa = [
            'nombre'   => $empRow['nombre']   ?? 'EGIDRA',
            'email'    => $empRow['email']     ?? '',
            'telefono' => $empRow['telefono']  ? "  ·  {$empRow['telefono']}" : '',
            'ciudad'   => $empRow['ciudad']    ?? '',
            'pais'     => $empRow['pais']      ?? '',
            'logo_url' => $logoUrl,
        ];

        $mail = crearMailer();
        $mail->addAddress($destino, $empresa['nombre']);
        $mail->addReplyTo($email, $nombre);
        $mail->Subject = '[Contacto Web] ' . ($asunto ?: "Mensaje de $nombre");
        $mail->Body    = buildPlantillaContacto($campos, $empresa);
        $mail->AltBody = "Nuevo mensaje de: $nombre <$email>\nAsunto: $asunto\n\n$mensaje";
        $mail->send();

    } catch (\Exception $e) {
        // El mensaje ya quedó en BD — el fallo de email no es crítico para el usuario
        error_log('PHPMailer error (contacto): ' . $e->getMessage());
    }
}

echo json_encode(['success' => true, 'mensaje' => 'Mensaje enviado correctamente. Le responderemos en menos de 24 horas.']);
