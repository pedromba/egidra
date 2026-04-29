<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// ─── Configuración SMTP ───────────────────────────────────────────────────────
// Editar estos valores según el servidor de correo en uso.
// En Laragon local: SMTP_HOST = 'localhost', SMTP_PORT = 25, SMTP_AUTH = false
// En producción con Gmail/Zoho/etc: completar usuario, contraseña y activar auth.

if (!defined('SMTP_HOST'))      define('SMTP_HOST',      'mail.egidra.com');
if (!defined('SMTP_PORT'))      define('SMTP_PORT',      587);
if (!defined('SMTP_AUTH'))      define('SMTP_AUTH',      true);
if (!defined('SMTP_USER'))      define('SMTP_USER',      'acc.ops@egidra.com');
if (!defined('SMTP_PASS'))      define('SMTP_PASS',      'sqbh wzyi bwkk cqrs');
if (!defined('SMTP_SECURE'))    define('SMTP_SECURE',    'tls');        // '' | 'tls' | 'ssl'
// Con Gmail/SMTP autenticado el From DEBE coincidir con SMTP_USER
if (!defined('SMTP_FROM'))      define('SMTP_FROM',      SMTP_USER);
if (!defined('SMTP_FROM_NAME')) define('SMTP_FROM_NAME', 'EGIDRA');

/**
 * Devuelve una instancia de PHPMailer lista para usar.
 * Lanza Exception si falla la configuración.
 */
function crearMailer(): PHPMailer
{
  require_once dirname(__DIR__) . '/libs/vendor/autoload.php';

  $mail = new PHPMailer(true);
  $mail->isSMTP();
  $mail->Host       = SMTP_HOST;
  $mail->SMTPAuth   = SMTP_AUTH;
  $mail->Username   = SMTP_USER;
  $mail->Password   = SMTP_PASS;
  $mail->SMTPSecure = SMTP_SECURE;
  $mail->Port       = SMTP_PORT;
  $mail->CharSet    = PHPMailer::CHARSET_UTF8;
  $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
  $mail->isHTML(true);

  return $mail;
}

/**
 * Construye el cuerpo HTML para notificación de contacto web.
 *
 * @param array $campos    ['nombre', 'email', 'asunto', 'mensaje']
 * @param array $empresa   ['nombre', 'email', 'telefono', 'ciudad', 'pais', 'logo_url']
 */
function buildPlantillaContacto(array $campos, array $empresa): string
{
  $esc = fn(string $s) => htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

  $empNombre  = $esc($empresa['nombre']   ?? 'EGIDRA');
  $empEmail   = $esc($empresa['email']    ?? '');
  $empTel     = $esc($empresa['telefono'] ?? '');
  $empLoc     = $esc(trim(($empresa['ciudad'] ?? '') . ', ' . ($empresa['pais'] ?? ''), ', '));
  $logoUrl    = $empresa['logo_url'] ?? '';
  $fecha      = date('d/m/Y H:i');

  $nombre  = $esc($campos['nombre']  ?? '');
  $email   = $esc($campos['email']   ?? '');
  $asunto  = $esc($campos['asunto']  ?? 'Sin asunto');
  $mensaje = nl2br($esc($campos['mensaje'] ?? ''));

  $logoHtml = $logoUrl
    ? '<img src="' . $esc($logoUrl) . '" alt="' . $empNombre . '" style="max-height:48px;max-width:180px;object-fit:contain;">'
    : '<span style="font-size:22px;font-weight:700;letter-spacing:1px;color:#ffffff;">' . $empNombre . '</span>';

  return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Nuevo mensaje de contacto</title>
</head>
<body style="margin:0;padding:0;background:#f0f2f5;font-family:'Segoe UI',Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f2f5;padding:32px 16px;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

  <!-- HEADER -->
  <tr>
    <td style="background:#1a1a2e;border-radius:12px 12px 0 0;padding:28px 36px;text-align:center;">
      {$logoHtml}
    </td>
  </tr>

  <!-- FRANJA AMARILLA -->
  <tr>
    <td style="background:#f59e0b;height:4px;font-size:0;line-height:0;">&nbsp;</td>
  </tr>

  <!-- TÍTULO -->
  <tr>
    <td style="background:#ffffff;padding:32px 36px 8px;">
      <h1 style="margin:0 0 6px;font-size:22px;font-weight:700;color:#1a1a2e;">
        📩 Nuevo mensaje de contacto
      </h1>
      <p style="margin:0;font-size:13px;color:#888;">Recibido el {$fecha} · Enviado desde el formulario web</p>
    </td>
  </tr>

  <!-- DATOS DEL REMITENTE -->
  <tr>
    <td style="background:#ffffff;padding:24px 36px 0;">
      <table width="100%" cellpadding="0" cellspacing="0"
             style="background:#f8f9fb;border-radius:8px;border:1px solid #e8eaed;overflow:hidden;">
        <tr>
          <td colspan="2" style="background:#1a1a2e;padding:10px 16px;">
            <span style="font-size:11px;font-weight:700;color:#f59e0b;text-transform:uppercase;letter-spacing:1px;">Información del remitente</span>
          </td>
        </tr>
        <tr>
          <td style="padding:12px 16px;color:#555;font-size:13px;width:120px;border-bottom:1px solid #e8eaed;vertical-align:top;"><strong>Nombre</strong></td>
          <td style="padding:12px 16px;color:#1a1a2e;font-size:14px;border-bottom:1px solid #e8eaed;vertical-align:top;">{$nombre}</td>
        </tr>
        <tr>
          <td style="padding:12px 16px;color:#555;font-size:13px;border-bottom:1px solid #e8eaed;vertical-align:top;"><strong>Email</strong></td>
          <td style="padding:12px 16px;font-size:14px;border-bottom:1px solid #e8eaed;vertical-align:top;">
            <a href="mailto:{$email}" style="color:#2563eb;text-decoration:none;">{$email}</a>
          </td>
        </tr>
        <tr>
          <td style="padding:12px 16px;color:#555;font-size:13px;vertical-align:top;"><strong>Asunto</strong></td>
          <td style="padding:12px 16px;color:#1a1a2e;font-size:14px;vertical-align:top;">
            <span style="background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">{$asunto}</span>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- MENSAJE -->
  <tr>
    <td style="background:#ffffff;padding:24px 36px 0;">
      <p style="margin:0 0 10px;font-size:11px;font-weight:700;color:#f59e0b;text-transform:uppercase;letter-spacing:1px;">Mensaje</p>
      <div style="background:#f8f9fb;border-left:4px solid #f59e0b;border-radius:0 8px 8px 0;padding:20px 24px;font-size:15px;line-height:1.7;color:#374151;">
        {$mensaje}
      </div>
    </td>
  </tr>

  <!-- ACCIÓN RÁPIDA -->
  <tr>
    <td style="background:#ffffff;padding:28px 36px;text-align:center;">
      <a href="mailto:{$email}?subject=RE: {$asunto}"
         style="display:inline-block;background:#f59e0b;color:#1a1a2e;font-weight:700;font-size:14px;
                padding:12px 32px;border-radius:8px;text-decoration:none;letter-spacing:0.3px;">
        ✉&nbsp; Responder a {$nombre}
      </a>
    </td>
  </tr>

  <!-- SEPARADOR -->
  <tr>
    <td style="background:#ffffff;padding:0 36px;">
      <hr style="border:none;border-top:1px solid #e8eaed;margin:0;">
    </td>
  </tr>

  <!-- FOOTER -->
  <tr>
    <td style="background:#ffffff;border-radius:0 0 12px 12px;padding:20px 36px 28px;">
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td style="font-size:12px;color:#9ca3af;line-height:1.6;">
            <strong style="color:#6b7280;">{$empNombre}</strong><br>
            {$empLoc}<br>
            {$empEmail}{$empTel}
          </td>
          <td align="right" style="font-size:11px;color:#d1d5db;vertical-align:top;">
            Este correo fue generado<br>automáticamente por el<br>formulario de contacto web.
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- BORDE INFERIOR -->
  <tr>
    <td style="background:#f59e0b;height:4px;border-radius:0 0 4px 4px;font-size:0;">&nbsp;</td>
  </tr>

</table>
</td></tr>
</table>

</body>
</html>
HTML;
}
