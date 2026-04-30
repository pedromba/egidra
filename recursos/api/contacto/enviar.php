<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', '0');
error_reporting(E_ERROR);

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
try {
    $campos  = compact('nombre', 'email', 'asunto', 'mensaje');
    $empresa = [
        'nombre'   => MAIL_FROM_NAME,
        'email'    => MAIL_EMPRESA,
        'telefono' => '',
        'ciudad'   => '',
        'pais'     => '',
        'logo_url' => '',
    ];

    $mail = crearMailer();
    $mail->addAddress(MAIL_EMPRESA, MAIL_FROM_NAME);
    $mail->addReplyTo($email, $nombre);
    $mail->Subject = '[Contacto Web] ' . ($asunto ?: "Mensaje de $nombre");
    $mail->Body    = buildPlantillaContacto($campos, $empresa);
    $mail->AltBody = "Nuevo mensaje de: $nombre <$email>\nAsunto: $asunto\n\n$mensaje";
    $mail->send();

} catch (\Exception $e) {
    // El mensaje ya quedó en BD — el fallo de email no interrumpe la respuesta al usuario
    error_log('PHPMailer error (contacto): ' . $e->getMessage());
}

echo json_encode(['success' => true, 'mensaje' => 'Mensaje enviado correctamente. Le responderemos en menos de 24 horas.']);
