<?php
/**
 * Raíces Alzate - Servicio Centralizado de Correo Electrónico con PHPMailer
 * Plataforma: Raíces Alzate - IE Gilberto Alzate Avendaño
 * 
 * Implementa despacho SMTP autenticado con STARTTLS (puerto 587) y SSL (puerto 465)
 * compatible con Gmail, Outlook, servidores corporativos e institucionales.
 * 
 * Incluye sistema de plantillas HTML responsivas con logotipo institucional,
 * paleta de colores oficial (#D32F2F / #1565C0), botones accesibles,
 * tarjetas de advertencia de seguridad y compatibilidad con clientes de correo móviles y web.
 */

declare(strict_types=1);

// 1. Cargar dependencias de configuración
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/mail.php';

// 2. Cargar clases oficiales de PHPMailer de forma desacoplada
require_once __DIR__ . '/PHPMailer/Exception.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Fábrica para instanciar y configurar un cliente PHPMailer listo para enviar.
 *
 * @param bool $debug Si se debe activar la salida de depuración en consola/HTML
 * @return PHPMailer Instancia configurada
 * @throws Exception Si ocurre un error de configuración
 */
function getMailerInstance(bool $debug = false): PHPMailer {
    $mail = new PHPMailer(true);

    // Configuración de servidor SMTP
    $mail->isSMTP();
    $mail->Host       = SMTP_HOST;
    $mail->SMTPAuth   = SMTP_AUTH;
    $mail->Username   = SMTP_USER;
    $mail->Password   = SMTP_PASS;
    $mail->Port       = SMTP_PORT;

    // Configuración de cifrado de transporte
    if (SMTP_ENCRYPTION === 'ssl' || SMTP_PORT === 465) {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    } elseif (SMTP_ENCRYPTION === 'tls' || SMTP_PORT === 587) {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    } else {
        $mail->SMTPSecure = '';
        $mail->SMTPAutoTLS = false;
    }

    // Opciones SSL para entornos locales de desarrollo (XAMPP / certificados locales)
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer'       => false,
            'verify_peer_name'  => false,
            'allow_self_signed' => true
        ]
    ];

    // Codificación de caracteres estándar
    $mail->CharSet = PHPMailer::CHARSET_UTF8;

    // Nivel de depuración
    if ($debug || SMTP_DEBUG > 0) {
        $mail->SMTPDebug = ($debug ? 2 : SMTP_DEBUG);
    } else {
        $mail->SMTPDebug = 0;
    }

    // Remitente predeterminado
    $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);

    return $mail;
}

/**
 * Adjunta el logo institucional oficial en formato embebido (CID: Inline Content-ID).
 * Esto garantiza que el logo se visualice incluso en clientes de correo que bloquean
 * imágenes externas o cuando se despacha desde entornos locales (localhost).
 *
 * @param PHPMailer $mail Instancia activa de PHPMailer
 * @return string Markup HTML del bloque contenedor con el logo
 */
function adjuntarLogoInstitucional(PHPMailer $mail): string {
    $candidatos = [
        defined('BASE_PATH') ? BASE_PATH . '/assets/img/logo-raices-alzate.png' : null,
        defined('BASE_PATH') ? BASE_PATH . '/assets/icons/logo-raices-alzate.png' : null,
        dirname(__DIR__) . '/assets/img/logo-raices-alzate.png',
        dirname(__DIR__) . '/assets/icons/logo-raices-alzate.png',
    ];

    foreach ($candidatos as $ruta) {
        if ($ruta && file_exists($ruta) && is_readable($ruta)) {
            try {
                $mail->addEmbeddedImage($ruta, 'logo_institucional', 'logo-raices-alzate.png', 'base64', 'image/png');
                return '
                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin: 0 auto 16px;">
                        <tr>
                            <td align="center" style="background-color: #FFFFFF; border-radius: 50%; padding: 4px; box-shadow: 0 6px 18px rgba(0, 0, 0, 0.22); width: 80px; height: 80px;">
                                <img src="cid:logo_institucional" alt="Logo Raíces Alzate" width="76" height="76" style="display: block; width: 76px; height: 76px; border-radius: 50%; object-fit: cover; border: 0; outline: none;">
                            </td>
                        </tr>
                    </table>
                ';
            } catch (\Throwable $e) {
                error_log('Error adjuntando logo institucional CID: ' . $e->getMessage());
            }
            break;
        }
    }

    // Fallback decorativo con gradiente en caso de no encontrarse el archivo
    return '
        <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin: 0 auto 16px;">
            <tr>
                <td align="center" style="background-color: #FFFFFF; border-radius: 50%; width: 76px; height: 76px; font-size: 34px; line-height: 76px; box-shadow: 0 6px 18px rgba(0, 0, 0, 0.22);">
                    🎓
                </td>
            </tr>
        </table>
    ';
}

/**
 * Genera el maquetado HTML institucional de presentación de alta fidelidad.
 * Cumple con los estándares de diseño de correo responsive (Fluid Hybrid Table Layout),
 * con soporte para Outlook MSO, Gmail, Apple Mail y clientes móviles.
 *
 * @param array $datos Parámetros de contenido y personalización
 * @return string Código HTML completo del mensaje
 */
function renderPlantillaInstitucional(array $datos): string {
    $logoHtml         = $datos['logo_html'] ?? '';
    $preheader        = htmlspecialchars($datos['preheader'] ?? 'Notificación oficial de Raíces Alzate', ENT_QUOTES, 'UTF-8');
    $badgeTexto       = htmlspecialchars($datos['badge_texto'] ?? 'Comunidad Alzatista', ENT_QUOTES, 'UTF-8');
    $tituloCuerpo     = htmlspecialchars($datos['titulo_cuerpo'] ?? 'Notificación Institucional', ENT_QUOTES, 'UTF-8');
    $nombre           = htmlspecialchars($datos['nombre'] ?? 'Estimado(a) usuario(a)', ENT_QUOTES, 'UTF-8');
    $contenidoHtml    = $datos['contenido_html'] ?? '';
    $botonTexto       = !empty($datos['boton_texto']) ? htmlspecialchars($datos['boton_texto'], ENT_QUOTES, 'UTF-8') : null;
    $botonUrl         = !empty($datos['boton_url']) ? htmlspecialchars($datos['boton_url'], ENT_QUOTES, 'UTF-8') : null;
    $enlaceAlt        = !empty($datos['enlace_alternativo']) ? htmlspecialchars($datos['enlace_alternativo'], ENT_QUOTES, 'UTF-8') : null;
    $cajaSeguridad    = $datos['caja_seguridad'] ?? null;
    $datosTecnicos    = $datos['datos_tecnicos'] ?? null;
    $colorAcento      = $datos['color_acento'] ?? '#1565C0'; // Azul Alzate por defecto

    // Render del botón de llamada a la acción
    $botonHtml = '';
    if ($botonTexto && $botonUrl) {
        $botonHtml = '
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin: 32px auto;">
                <tr>
                    <td align="center" style="border-radius: 10px; background-color: #D32F2F; box-shadow: 0 4px 14px rgba(211, 47, 47, 0.35);">
                        <a href="' . $botonUrl . '" target="_blank" style="display: inline-block; padding: 16px 36px; font-size: 15px; font-weight: 700; color: #FFFFFF; text-decoration: none; border-radius: 10px; border: 1px solid #D32F2F; text-align: center; letter-spacing: 0.3px;">
                            ' . $botonTexto . ' &rarr;
                        </a>
                    </td>
                </tr>
            </table>
        ';
    }

    // Render de la caja de seguridad/advertencia
    $cajaSeguridadHtml = '';
    if (!empty($cajaSeguridad) && is_array($cajaSeguridad)) {
        $tituloCaja = htmlspecialchars($cajaSeguridad['titulo'] ?? 'Recomendaciones de Seguridad', ENT_QUOTES, 'UTF-8');
        $itemsCaja  = $cajaSeguridad['items'] ?? [];

        $itemsHtml = '';
        foreach ($itemsCaja as $item) {
            $itemsHtml .= '<li style="margin-bottom: 5px;">' . $item . '</li>';
        }

        $cajaSeguridadHtml = '
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 28px 0 24px; background-color: #FFFBEB; border-left: 4px solid #F59E0B; border-radius: 8px; border-top: 1px solid #FEF3C7; border-right: 1px solid #FEF3C7; border-bottom: 1px solid #FEF3C7;">
                <tr>
                    <td style="padding: 16px 20px;">
                        <h3 style="margin: 0 0 8px; font-size: 13px; font-weight: 700; color: #92400E; text-transform: uppercase; letter-spacing: 0.5px;">
                            🛡️ ' . $tituloCaja . '
                        </h3>
                        <ul style="margin: 0; padding-left: 18px; font-size: 13px; color: #78350F; line-height: 1.55;">
                            ' . $itemsHtml . '
                        </ul>
                    </td>
                </tr>
            </table>
        ';
    }

    // Render del enlace alternativo en texto monoespaciado
    $enlaceAltHtml = '';
    if ($enlaceAlt) {
        $enlaceAltHtml = '
            <p style="margin: 24px 0 8px; font-size: 13px; color: #64748B; line-height: 1.5;">
                Si el botón anterior no abre la página, copia y pega el siguiente enlace directamente en tu navegador web:
            </p>
            <div style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 12px 14px; margin-bottom: 24px; word-break: break-all;">
                <a href="' . $enlaceAlt . '" style="font-family: SFMono-Regular, Consolas, Monaco, monospace; font-size: 12px; color: #1565C0; text-decoration: underline; line-height: 1.4;">
                    ' . $enlaceAlt . '
                </a>
            </div>
        ';
    }

    // Render de tabla de datos técnicos (si aplica)
    $tablaTecnicaHtml = '';
    if (!empty($datosTecnicos) && is_array($datosTecnicos)) {
        $filas = '';
        $i = 0;
        foreach ($datosTecnicos as $etiqueta => $valor) {
            $bg = ($i % 2 === 0) ? '#F8FAFC' : '#FFFFFF';
            $filas .= '
                <tr style="background-color: ' . $bg . ';">
                    <td style="padding: 10px 14px; font-size: 13px; font-weight: 700; color: #334155; border: 1px solid #E2E8F0; width: 40%;">' . htmlspecialchars((string)$etiqueta) . '</td>
                    <td style="padding: 10px 14px; font-size: 13px; color: #475569; border: 1px solid #E2E8F0;">' . htmlspecialchars((string)$valor) . '</td>
                </tr>
            ';
            $i++;
        }

        $tablaTecnicaHtml = '
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 20px 0 24px; border-collapse: collapse; border: 1px solid #E2E8F0; border-radius: 8px; overflow: hidden;">
                ' . $filas . '
            </table>
        ';
    }

    $anioActual = date('Y');

    return '<!DOCTYPE html>
<html lang="es" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>' . $tituloCuerpo . ' — Raíces Alzate</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
        body { margin: 0; padding: 0; width: 100% !important; min-width: 100%; height: 100% !important; }
        @media only screen and (max-width: 620px) {
            .email-container { width: 100% !important; max-width: 100% !important; }
            .header-cell { padding: 32px 20px 28px !important; }
            .body-cell { padding: 28px 20px !important; }
            .footer-cell { padding: 24px 20px !important; }
            .btn-action { width: 100% !important; text-align: center !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #F1F5F9; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif; color: #1E293B; line-height: 1.6;">

    <!-- Preheader invisible para bandeja de entrada -->
    <div style="display: none; font-size: 1px; color: #F1F5F9; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
        ' . $preheader . '
    </div>

    <!-- Envoltorio General -->
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #F1F5F9; width: 100%; margin: 0; padding: 40px 12px 48px;">
        <tr>
            <td align="center" valign="top">

                <!-- Tarjeta Principal (Max 600px) -->
                <table role="presentation" class="email-container" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; background-color: #FFFFFF; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04); border: 1px solid #E2E8F0;">
                    
                    <!-- 1. Borde Superior Rojo Alzate -->
                    <tr>
                        <td style="background-color: #B71C1C; height: 6px; font-size: 0; line-height: 0;">&nbsp;</td>
                    </tr>

                    <!-- 2. Encabezado Oficial con Logo y Colores Institucionales -->
                    <tr>
                        <td class="header-cell" align="center" style="background: linear-gradient(135deg, #B71C1C 0%, #D32F2F 55%, #C62828 100%); background-color: #D32F2F; padding: 40px 32px 34px; text-align: center;">
                            
                            <!-- Logo Institucional Oficial -->
                            ' . $logoHtml . '

                            <!-- Nombre Oficial del Proyecto -->
                            <h1 style="margin: 0 0 6px; font-size: 26px; font-weight: 800; color: #FFFFFF; letter-spacing: -0.02em; text-shadow: 0 2px 4px rgba(0,0,0,0.15);">
                                Raíces Alzate
                            </h1>
                            <p style="margin: 0 0 14px; font-size: 13px; font-weight: 600; color: #FFCDD2; text-transform: uppercase; letter-spacing: 0.8px;">
                                Institución Educativa Gilberto Alzate Avendaño
                            </p>

                            <!-- Chip de Identidad -->
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin: 0 auto;">
                                <tr>
                                    <td style="background-color: rgba(255, 255, 255, 0.18); border: 1px solid rgba(255, 255, 255, 0.35); border-radius: 20px; padding: 4px 16px; font-size: 11px; font-weight: 700; color: #FFFFFF; text-transform: uppercase; letter-spacing: 0.6px;">
                                        ' . $badgeTexto . '
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- 3. Cinta Divisoria de Acento (Azul Institucional) -->
                    <tr>
                        <td style="background-color: ' . $colorAcento . '; height: 4px; font-size: 0; line-height: 0;">&nbsp;</td>
                    </tr>

                    <!-- 4. Contenido del Correo -->
                    <tr>
                        <td class="body-cell" style="padding: 38px 40px 32px; background-color: #FFFFFF;">
                            
                            <!-- Título del Mensaje -->
                            <h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #1E293B; letter-spacing: -0.01em;">
                                ' . $tituloCuerpo . '
                            </h2>

                            <!-- Saludo -->
                            <p style="margin: 0 0 16px; font-size: 15px; color: #334155; line-height: 1.6;">
                                Hola, <strong>' . $nombre . '</strong>:
                            </p>

                            <!-- Párrafos principales -->
                            <div style="font-size: 15px; color: #475569; line-height: 1.65;">
                                ' . $contenidoHtml . '
                            </div>

                            <!-- Botón CTA -->
                            ' . $botonHtml . '

                            <!-- Tabla de Detalles Técnicos si existe -->
                            ' . $tablaTecnicaHtml . '

                            <!-- Caja de Seguridad -->
                            ' . $cajaSeguridadHtml . '

                            <!-- Enlace alternativo manual -->
                            ' . $enlaceAltHtml . '

                            <!-- Despedida Institucional -->
                            <p style="margin: 28px 0 0; font-size: 13px; color: #94A3B8; line-height: 1.5;">
                                Atentamente,<br>
                                <strong style="color: #64748B;">Equipo de Seguridad y Tecnología • Raíces Alzate</strong><br>
                                <span style="font-size: 12px; color: #94A3B8;">Institución Educativa Gilberto Alzate Avendaño</span>
                            </p>
                        </td>
                    </tr>

                    <!-- 5. Pie de Página Institucional -->
                    <tr>
                        <td class="footer-cell" style="background-color: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 28px 36px; text-align: center;">
                            <p style="margin: 0 0 6px; font-size: 13px; font-weight: 700; color: #475569;">
                                Institución Educativa Gilberto Alzate Avendaño
                            </p>
                            <p style="margin: 0 0 12px; font-size: 12px; color: #64748B;">
                                Plataforma Oficial de Egresados y Comunidad Alzatista • Medellín, Colombia
                            </p>
                            <div style="height: 1px; background-color: #E2E8F0; width: 80px; margin: 12px auto;"></div>
                            <p style="margin: 0 0 4px; font-size: 11px; color: #94A3B8;">
                                Este es un mensaje institucional automático generado por el sistema de seguridad. Por favor, no respondas a este correo.
                            </p>
                            <p style="margin: 0; font-size: 11px; color: #94A3B8;">
                                &copy; ' . $anioActual . ' Raíces Alzate. Todos los derechos reservados.
                            </p>
                        </td>
                    </tr>

                </table>
                <!-- Fin Tarjeta Principal -->

            </td>
        </tr>
    </table>

</body>
</html>';
}

/**
 * Envía el correo con el enlace criptográfico de recuperación de contraseña
 * utilizando la presentación institucional oficial con logo y colores de la IE.
 *
 * @param string $email Correo del destinatario
 * @param string $nombre Nombre del destinatario
 * @param string $tokenCrudo Token criptográfico de 64 caracteres hex
 * @return bool True si el correo se despachó con éxito, false en caso de fallo
 */
function enviarCorreoRecuperacionToken(string $email, string $nombre, string $tokenCrudo): bool {
    $emailDestino = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    if (!filter_var($emailDestino, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    $nombreLimpio = trim($nombre);

    // Construcción de la URL canónica de restablecimiento
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $basePath = defined('BASE_URL') ? BASE_URL : '/Raices-Alzate';
    
    if (strpos($basePath, 'http://') === 0 || strpos($basePath, 'https://') === 0) {
        $resetUrl = rtrim($basePath, '/') . '/index.php?page=reset-password&token=' . urlencode($tokenCrudo);
    } else {
        $resetUrl = $scheme . '://' . $host . rtrim($basePath, '/') . '/index.php?page=reset-password&token=' . urlencode($tokenCrudo);
    }

    $asunto = 'Recuperación de contraseña — Raíces Alzate';

    try {
        $mail = getMailerInstance(false);
        $mail->addAddress($emailDestino, $nombreLimpio);
        $mail->Subject = $asunto;

        // Adjuntar logo con CID para visualización segura sin bloqueos de navegador
        $logoHtml = adjuntarLogoInstitucional($mail);

        // Generar plantilla de presentación institucional
        $cuerpoHtml = renderPlantillaInstitucional([
            'logo_html'             => $logoHtml,
            'preheader'             => 'Instrucciones de seguridad para restablecer tu contraseña en Raíces Alzate.',
            'badge_texto'           => '🔒 Seguridad & Acceso',
            'titulo_cuerpo'         => 'Solicitud de Restablecimiento de Contraseña',
            'nombre'                => $nombreLimpio,
            'contenido_html'        => '
                <p style="margin: 0 0 16px;">
                    Hemos recibido una solicitud para restablecer la contraseña de acceso a tu cuenta en la plataforma <strong>Raíces Alzate</strong> de la <strong>Institución Educativa Gilberto Alzate Avendaño</strong>.
                </p>
                <p style="margin: 0 0 16px;">
                    Para crear una nueva contraseña segura y recuperar el control de tu cuenta, por favor presiona el siguiente botón:
                </p>
            ',
            'boton_texto'           => 'Restablecer mi Contraseña',
            'boton_url'             => $resetUrl,
            'caja_seguridad'        => [
                'titulo' => 'Medidas de Seguridad de tu Cuenta',
                'items'  => [
                    '<strong>Vigencia de 1 hora:</strong> Por estrictas políticas de ciberseguridad, este enlace de recuperación expirará automáticamente en 60 minutos.',
                    '<strong>Uso único criptográfico:</strong> Una vez utilizado para cambiar la clave, el enlace se invalidará permanentemente de la base de datos.',
                    '<strong>Protección proactiva:</strong> Si tú no solicitaste este cambio, puedes descartar este mensaje de forma segura. Tu contraseña actual no sufrirá alteraciones y tu cuenta permanece resguardada.'
                ]
            ],
            'enlace_alternativo'    => $resetUrl,
            'color_acento'          => '#1565C0'
        ]);

        // Versión en texto plano accesible (AltBody)
        $altBody = "Hola, {$nombreLimpio}:\n\n"
                 . "Hemos recibido una solicitud para restablecer tu contraseña en Raíces Alzate (IE Gilberto Alzate Avendaño).\n\n"
                 . "Para crear tu nueva contraseña, ingresa al siguiente enlace seguro (válido por 1 hora):\n"
                 . "{$resetUrl}\n\n"
                 . "Medidas de seguridad:\n"
                 . "- Enlace válido por 60 minutos.\n"
                 . "- Válido para un único uso.\n"
                 . "- Si no realizaste esta solicitud, ignora este mensaje. Tu cuenta se encuentra totalmente protegida.\n\n"
                 . "Equipo de Seguridad y Tecnología • Raíces Alzate\n"
                 . "Institución Educativa Gilberto Alzate Avendaño • Medellín, Colombia";

        $mail->isHTML(true);
        $mail->Body    = $cuerpoHtml;
        $mail->AltBody = $altBody;

        return $mail->send();

    } catch (Exception $e) {
        error_log('Error en PHPMailer al enviar recuperación: ' . $e->getMessage());
        return false;
    } catch (\Throwable $e) {
        error_log('Error general al enviar correo de recuperación: ' . $e->getMessage());
        return false;
    }
}

/**
 * Envía una notificación por correo al usuario sobre la resolución de su solicitud de registro,
 * utilizando la presentación institucional con logo y colores oficiales.
 *
 * @param string $email Correo electrónico del solicitante
 * @param string $nombre Nombre completo del solicitante
 * @param string $accion 'aprobar_registro' o 'rechazar_registro'
 * @return bool True si el correo se despachó con éxito
 */
function enviarNotificacionRegistro(string $email, string $nombre, string $accion): bool {
    $emailDestino = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    if (!filter_var($emailDestino, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    $nombreLimpio = trim($nombre);
    $isAprobado = ($accion === 'aprobar_registro' || $accion === 'aprobado');

    $asunto = $isAprobado 
        ? '¡Bienvenido a Raíces Alzate! Tu registro ha sido aprobado' 
        : 'Notificación sobre tu solicitud de registro — Raíces Alzate';

    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $basePath = defined('BASE_URL') ? BASE_URL : '/Raices-Alzate';
    $loginUrl = (strpos($basePath, 'http') === 0 ? $basePath : $scheme . '://' . $host . rtrim($basePath, '/')) . '/index.php?page=login';

    try {
        $mail = getMailerInstance(false);
        $mail->addAddress($emailDestino, $nombreLimpio);
        $mail->Subject = $asunto;

        // Logo institucional con CID
        $logoHtml = adjuntarLogoInstitucional($mail);

        if ($isAprobado) {
            $cuerpoHtml = renderPlantillaInstitucional([
                'logo_html'     => $logoHtml,
                'preheader'     => '¡Tu cuenta ha sido aprobada! Ya puedes acceder a la comunidad de Raíces Alzate.',
                'badge_texto'   => '🎓 Registro Aprobado',
                'titulo_cuerpo' => '¡Te damos la bienvenida a Raíces Alzate!',
                'nombre'        => $nombreLimpio,
                'contenido_html'=> '
                    <p style="margin: 0 0 16px;">
                        Nos complace informarte que tu solicitud de registro en <strong>Raíces Alzate</strong>, la plataforma oficial de la comunidad de egresados de la <strong>Institución Educativa Gilberto Alzate Avendaño</strong>, ha sido revisada y <strong>aprobada satisfactoriamente</strong>.
                    </p>
                    <p style="margin: 0 0 16px;">
                        A partir de este momento puedes ingresar con tu correo electrónico y tu contraseña para disfrutar de todos los beneficios de nuestra red: novedades, ofertas de empleo, proyectos colaborativos y vinculación institucional.
                    </p>
                ',
                'boton_texto'   => 'Iniciar Sesión en la Plataforma',
                'boton_url'     => $loginUrl,
                'color_acento'  => '#1565C0'
            ]);

            $altBody = "¡Hola, {$nombreLimpio}!\n\n"
                     . "Tu solicitud de registro en Raíces Alzate ha sido aprobada.\n"
                     . "Puedes iniciar sesión ahora mismo en: {$loginUrl}\n\n"
                     . "IE Gilberto Alzate Avendaño • Medellín, Colombia";
        } else {
            $cuerpoHtml = renderPlantillaInstitucional([
                'logo_html'     => $logoHtml,
                'preheader'     => 'Información importante sobre el estado de tu solicitud de registro en Raíces Alzate.',
                'badge_texto'   => 'ℹ️ Estado de Registro',
                'titulo_cuerpo' => 'Actualización de Solicitud de Registro',
                'nombre'        => $nombreLimpio,
                'contenido_html'=> '
                    <p style="margin: 0 0 16px;">
                        Te informamos que tu solicitud de registro en la plataforma <strong>Raíces Alzate</strong> ha sido revisada por la administración de la institución y en esta oportunidad <strong>no ha sido aprobada</strong>.
                    </p>
                    <p style="margin: 0 0 16px;">
                        Si consideras que se trata de una inconsistencia o necesitas validar tu información académica, te invitamos a comunicarte directamente con la secretaría de la institución educativa o a través de nuestros canales de atención institucional.
                    </p>
                ',
                'color_acento'  => '#D32F2F'
            ]);

            $altBody = "Estimado(a) {$nombreLimpio}:\n\n"
                     . "Te informamos que tu solicitud de registro en Raíces Alzate no ha sido aprobada en esta oportunidad.\n"
                     . "Para más información, contacta a la secretaría de la Institución Educativa Gilberto Alzate Avendaño.\n\n"
                     . "IE Gilberto Alzate Avendaño • Medellín, Colombia";
        }

        $mail->isHTML(true);
        $mail->Body    = $cuerpoHtml;
        $mail->AltBody = $altBody;

        return $mail->send();

    } catch (\Throwable $e) {
        error_log('Error enviando notificación de registro vía PHPMailer: ' . $e->getMessage());
        return false;
    }
}

/**
 * Función de compatibilidad heredada que delega en el nuevo motor institucional.
 */
function generarHtmlCorreo(string $nombre, bool $isAprobado): string {
    return renderPlantillaInstitucional([
        'badge_texto'   => $isAprobado ? '🎓 Registro Aprobado' : 'ℹ️ Estado de Registro',
        'titulo_cuerpo' => $isAprobado ? '¡Registro Aprobado con Éxito!' : 'Actualización de Solicitud',
        'nombre'        => $nombre,
        'contenido_html'=> $isAprobado 
            ? '<p>Tu solicitud de registro ha sido aprobada. Puedes iniciar sesión.</p>'
            : '<p>Tu solicitud de registro no ha sido aprobada en esta oportunidad.</p>',
        'color_acento'  => $isAprobado ? '#1565C0' : '#D32F2F'
    ]);
}
