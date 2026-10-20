<?php
/**
 * Cliente HTTP para el servicio externo "Servicio de Mail PP2".
 * Este service se encarga de la comunicación con el servicio de mail
 * que provee la cátedra.
 *
 * IMPORTANTE: la MAIL_API_KEY vive SIEMPRE en el backend. Nunca debe
 * exponerse al frontend ni al JavaScript del navegador.
 */

/**
 * Envía un mail a través del servicio externo.
 *
 * @param string      $destinatario   Dirección (o lista separada por coma).
 * @param string      $asunto         Asunto del mail.
 * @param string      $texto          Cuerpo en texto plano.
 * @param string|null $html           Cuerpo en HTML (opcional).
 * @param string|null $idempotencyKey Clave para evitar duplicados (opcional).
 *
 * @return array ['ok' => bool, 'status' => int, 'data' => array|null, 'error' => string|null]
 */

function enviarMail($destinatario, $asunto, $texto, $html = null, $idempotencyKey = null)
{
    $url = getenv('MAIL_API_URL');
    $token = getenv('MAIL_API_KEY');

    if (!$url || !$token) {
        return [
            'ok' => false,
            'status' => 500,
            'error' => 'Faltan MAIL_API_URL o MAIL_API_KEY en el .env'
        ];
    }

    $cuerpo = [
        'to'      => $destinatario,
        'subject' => $asunto,
        'text'    => $texto,
    ];

    if ($html !== null) {
        $cuerpo['html'] = $html;
    }
    if ($idempotencyKey !== null) {
        $cuerpo['idempotency_key'] = $idempotencyKey;
    }

    $payload = json_encode($cuerpo);

    $ch = curl_init($url . '/api/v1/emails');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'X-API-Key: ' . $token
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $respuesta = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $errorCurl = curl_error($ch);
    curl_close($ch);

    if ($errorCurl) {
        return [
            'ok' => false,
            'status' => 500,
            'error' => 'Error de conexión con el Servicio de Mail: ' . $errorCurl
        ];
    }

    $datos = json_decode($respuesta, true);

    // 202 = encolado, 200 = aceptado. Cualquiera de los dos es éxito.
    if ($httpCode === 202 || $httpCode === 200) {
        return [
            'ok' => true,
            'status' => $httpCode,
            'data' => $datos
        ];
    }

    return [
        'ok' => false,
        'status' => $httpCode,
        'error' => $datos['detail'] ?? 'Error desconocido del Servicio de Mail'
    ];
}
