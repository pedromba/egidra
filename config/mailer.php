<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// ─── Transporte SMTP ──────────────────────────────────────────────────────────
// Para cambiar al servidor corporativo en producción, editar solo estas líneas:
//   SMTP_HOST = 'mail.egidra.com' | SMTP_PORT = 465 | SMTP_SECURE = 'ssl'
//   SMTP_USER = 'acc.ops@egidra.com' | SMTP_PASS = '<contraseña>'
if (!defined('SMTP_HOST'))   define('SMTP_HOST',   'smtp.gmail.com');
if (!defined('SMTP_PORT'))   define('SMTP_PORT',   465);
if (!defined('SMTP_AUTH'))   define('SMTP_AUTH',   true);
if (!defined('SMTP_USER'))   define('SMTP_USER',   'pmba098@gmail.com');
if (!defined('SMTP_PASS'))   define('SMTP_PASS',   'sqbh wzyi bwkk cqrs');
if (!defined('SMTP_SECURE')) define('SMTP_SECURE', 'ssl');  // 'ssl'=465 | 'tls'=587

// ─── Identidad, buzones y URLs ───────────────────────────────────────────────
// MAIL_FROM:       dirección visible en "De:". Con Gmail debe ser igual a SMTP_USER.
//                  En producción con servidor propio: 'acc.ops@egidra.com'
// MAIL_FROM_NAME:  nombre visible en el cliente de correo del destinatario.
// MAIL_EMPRESA:    buzón que recibe los mensajes del formulario de contacto web.
//                  En producción: 'acc.ops@egidra.com'
// SITE_URL:        URL raíz del sitio web público.       En producción: 'https://egidra.com'
// ADMIN_URL:       URL de la página de login del panel.  En producción: 'https://egidra.com/admin'
if (!defined('MAIL_FROM'))      define('MAIL_FROM',      SMTP_USER);
if (!defined('MAIL_FROM_NAME')) define('MAIL_FROM_NAME', 'EGIDRA');
if (!defined('MAIL_EMPRESA'))   define('MAIL_EMPRESA',   'pmba098@gmail.com');
if (!defined('SITE_URL'))       define('SITE_URL',       'http://localhost/egidra.com');
if (!defined('ADMIN_URL'))      define('ADMIN_URL',       SITE_URL . '/admin');

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
  $mail->Timeout    = 5; // seconds — fail fast instead of hanging
  $mail->CharSet    = PHPMailer::CHARSET_UTF8;
  $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
  $mail->isHTML(true);

  return $mail;
}

/**
 * Genera una contraseña aleatoria de $len caracteres (sin caracteres ambiguos).
 */
function generarPassword(int $len = 8): string
{
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
    $pass  = '';
    for ($i = 0; $i < $len; $i++) {
        $pass .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $pass;
}

/**
 * Construye el cuerpo HTML para envío de credenciales de acceso.
 *
 * @param string $nombre    Nombre del destinatario
 * @param string $email     Email del destinatario
 * @param string $password  Contraseña en texto plano
 * @param string $rol       Rol asignado (Editor / Super)
 * @param string $tipo      'nueva' (cuenta nueva) | 'reset' (contraseña reseteada)
 */
function buildPlantillaCredenciales(string $nombre, string $email, string $password, string $rol, string $tipo = 'nueva'): string
{
    $esc     = fn(string $s) => htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $fecha   = date('d/m/Y H:i');
    $eNombre = $esc($nombre);
    $eEmail  = $esc($email);
    $ePass   = $esc($password);
    $eRol    = $esc($rol);
    $eAdmin  = $esc(ADMIN_URL);
    $eSite   = $esc(SITE_URL);
    $eName   = $esc(MAIL_FROM_NAME);

    $titulo    = $tipo === 'reset'
        ? '🔑 Tu contraseña ha sido reseteada'
        : '🎉 Tu cuenta en ' . MAIL_FROM_NAME . ' ha sido creada';
    $subtitulo = $tipo === 'reset'
        ? 'El administrador ha generado una nueva contraseña para tu cuenta.'
        : 'Bienvenido/a al panel de administración de ' . MAIL_FROM_NAME . '.';
    $btnTexto  = $tipo === 'reset' ? 'Acceder con mi nueva contraseña' : 'Acceder al panel ahora';

    return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>{$titulo}</title>
</head>
<body style="margin:0;padding:0;background:#f0f2f5;font-family:'Segoe UI',Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f2f5;padding:32px 16px;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

  <!-- HEADER -->
  <tr>
    <td style="background:#1a1a2e;border-radius:12px 12px 0 0;padding:28px 36px;text-align:center;">
      <a href="{$eSite}" style="text-decoration:none;">
        <span style="font-size:22px;font-weight:700;letter-spacing:1px;color:#ffffff;">{$eName}</span>
      </a>
    </td>
  </tr>

  <!-- FRANJA AMARILLA -->
  <tr><td style="background:#f59e0b;height:4px;font-size:0;line-height:0;">&nbsp;</td></tr>

  <!-- TÍTULO -->
  <tr>
    <td style="background:#ffffff;padding:32px 36px 16px;">
      <h1 style="margin:0 0 8px;font-size:22px;font-weight:700;color:#1a1a2e;">{$titulo}</h1>
      <p style="margin:0;font-size:13px;color:#888;">Hola <strong>{$eNombre}</strong>, {$subtitulo}</p>
    </td>
  </tr>

  <!-- CREDENCIALES -->
  <tr>
    <td style="background:#ffffff;padding:16px 36px 24px;">
      <table width="100%" cellpadding="0" cellspacing="0"
             style="background:#f8f9fb;border-radius:8px;border:1px solid #e8eaed;">
        <tr>
          <td colspan="2" style="background:#1a1a2e;padding:10px 16px;border-radius:8px 8px 0 0;">
            <span style="font-size:11px;font-weight:700;color:#f59e0b;text-transform:uppercase;letter-spacing:1px;">Tus credenciales de acceso</span>
          </td>
        </tr>
        <tr>
          <td style="padding:12px 16px;color:#555;font-size:13px;width:130px;border-bottom:1px solid #e8eaed;"><strong>Email</strong></td>
          <td style="padding:12px 16px;color:#1a1a2e;font-size:14px;border-bottom:1px solid #e8eaed;">{$eEmail}</td>
        </tr>
        <tr>
          <td style="padding:12px 16px;color:#555;font-size:13px;border-bottom:1px solid #e8eaed;"><strong>Contraseña</strong></td>
          <td style="padding:12px 16px;border-bottom:1px solid #e8eaed;">
            <code style="background:#fef3c7;color:#92400e;padding:4px 12px;border-radius:6px;font-size:15px;font-weight:700;letter-spacing:1px;">{$ePass}</code>
          </td>
        </tr>
        <tr>
          <td style="padding:12px 16px;color:#555;font-size:13px;"><strong>Rol</strong></td>
          <td style="padding:12px 16px;color:#1a1a2e;font-size:14px;">{$eRol}</td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- BOTÓN ACCESO -->
  <tr>
    <td style="background:#ffffff;padding:0 36px 32px;text-align:center;">
      <a href="{$eAdmin}"
         style="display:inline-block;background:#f59e0b;color:#1a1a2e;font-weight:700;font-size:15px;
                padding:14px 36px;border-radius:8px;text-decoration:none;letter-spacing:0.3px;">
        🔐&nbsp; {$btnTexto}
      </a>
      <p style="margin:12px 0 0;font-size:11px;color:#9ca3af;">
        O copia este enlace en tu navegador:<br>
        <a href="{$eAdmin}" style="color:#6b7280;font-size:11px;">{$eAdmin}</a>
      </p>
    </td>
  </tr>

  <!-- AVISO -->
  <tr>
    <td style="background:#f8f9fb;border-top:1px solid #e8eaed;padding:18px 36px;">
      <p style="margin:0;font-size:12px;color:#9ca3af;line-height:1.6;">
        Por seguridad, te recomendamos cambiar esta contraseña tras tu primer acceso.<br>
        Si no esperabas este correo, ignóralo o contacta al administrador.
      </p>
    </td>
  </tr>

  <!-- FOOTER -->
  <tr>
    <td style="background:#f8f9fb;border-radius:0 0 12px 12px;padding:12px 36px 20px;text-align:center;">
      <p style="margin:0;font-size:11px;color:#9ca3af;">Generado automáticamente el {$fecha} · {$eName}</p>
    </td>
  </tr>

  <tr><td style="background:#f59e0b;height:4px;border-radius:0 0 4px 4px;font-size:0;">&nbsp;</td></tr>

</table>
</td></tr>
</table>

</body>
</html>
HTML;
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
