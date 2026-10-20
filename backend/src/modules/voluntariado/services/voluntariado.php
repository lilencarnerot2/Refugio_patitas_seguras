<?php

require_once __DIR__ . '/../../mail/services/mail.php';
require_once __DIR__ . '/../../notificaciones_wsp/services/whatsapp.php';



// CONFIGURACIÓN
const VOLUNTARIADO_TURNOS = [
    'sabado-09-11' => ['dia' => 'Sábado', 'rango' => '09:00 - 11:00', 'horario' => '09:00-11:00'],
    'sabado-14-16' => ['dia' => 'Sábado', 'rango' => '14:00 - 16:00', 'horario' => '14:00-16:00'],
    'domingo-10-12' => ['dia' => 'Domingo', 'rango' => '10:00 - 12:00', 'horario' => '10:00-12:00'],
];

const VOLUNTARIADO_EMAIL_REFUGIO = 'profesionalizantes29@gmail.com';
const VOLUNTARIADO_WHATSAPP = '11 2345 6789';
const VOLUNTARIADO_DIRECCION = 'Merlo, Buenos Aires, Argentina';


// VALIDACIONES
function validar_fecha_voluntariado(string $fecha): bool {
    $d = DateTime::createFromFormat('Y-m-d', $fecha);

    if (!$d || $d->format('Y-m-d') !== $fecha) {
        return false;
    }

    // No permitir fechas pasadas
    $hoy = new DateTime('today');
    if ($d < $hoy) {
        return false;
    }

    return true;
}


function validar_datos_voluntariado(array $datos): void {
    $nombre = trim((string) ($datos['nombre'] ?? ''));
    $apellido = trim((string) ($datos['apellido'] ?? ''));
    $email = trim((string) ($datos['email'] ?? ''));
    $telefono = trim((string) ($datos['telefono'] ?? ''));
    $fecha = trim((string) ($datos['fecha'] ?? ''));
    $rango_horario = trim((string) ($datos['rango_horario'] ?? ''));

    if ($nombre === '') {
        throw new InvalidArgumentException('El nombre es obligatorio.');
    }
    if (mb_strlen($nombre) > 60) {
        throw new InvalidArgumentException('El nombre no puede superar los 60 caracteres.');
    }

    if ($apellido === '') {
        throw new InvalidArgumentException('El apellido es obligatorio.');
    }
    if (mb_strlen($apellido) > 60) {
        throw new InvalidArgumentException('El apellido no puede superar los 60 caracteres.');
    }

    if ($email === '') {
        throw new InvalidArgumentException('El email es obligatorio.');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException('El email no tiene un formato válido.');
    }
    if (mb_strlen($email) > 120) {
        throw new InvalidArgumentException('El email no puede superar los 120 caracteres.');
    }

    if ($telefono === '') {
        throw new InvalidArgumentException('El teléfono es obligatorio.');
    }
    if (mb_strlen($telefono) > 30) {
        throw new InvalidArgumentException('El teléfono no puede superar los 30 caracteres.');
    }

    if ($fecha === '') {
        throw new InvalidArgumentException('La fecha es obligatoria.');
    }
    if (!validar_fecha_voluntariado($fecha)) {
        throw new InvalidArgumentException('La fecha no es válida o es anterior a hoy.');
    }

    if ($rango_horario === '') {
        throw new InvalidArgumentException('El rango horario es obligatorio.');
    }
    if (!isset(VOLUNTARIADO_TURNOS[$rango_horario])) {
        throw new InvalidArgumentException('El turno seleccionado no es válido.');
    }

    if (empty($datos['acepta_indicaciones']) || $datos['acepta_indicaciones'] !== true) {
        throw new InvalidArgumentException('Debés aceptar las indicaciones del refugio.');
    }

    if (empty($datos['acepta_avisar']) || $datos['acepta_avisar'] !== true) {
        throw new InvalidArgumentException('Debés aceptar avisar si no podés asistir.');
    }

    if (isset($datos['limitaciones']) && mb_strlen((string) $datos['limitaciones']) > 500) {
        throw new InvalidArgumentException('Las limitaciones no pueden superar los 500 caracteres.');
    }

    if (isset($datos['observaciones']) && mb_strlen((string) $datos['observaciones']) > 1000) {
        throw new InvalidArgumentException('Las observaciones no pueden superar los 1000 caracteres.');
    }
}



// HELPERS

function si_no($valor): string {
    return !empty($valor) ? 'Sí' : 'No';
}


// REGISTRO
function registrar_voluntariado(array $datos): array {
    validar_datos_voluntariado($datos);

    $nombre = trim((string) $datos['nombre']);
    $apellido = trim((string) $datos['apellido']);
    $email = strtolower(trim((string) $datos['email']));
    $telefono = trim((string) $datos['telefono']);
    $fecha = trim((string) $datos['fecha']);
    $rango_horario = trim((string) $datos['rango_horario']);

    $turno = VOLUNTARIADO_TURNOS[$rango_horario];
    $dia = $turno['dia'];
    $rango_legible = $turno['rango'];

    // -------- Mail al refugio --------
    $asuntoRefugio = "Nueva postulación de voluntariado - $nombre $apellido";

    $cuerpoRefugio =
        "Nueva postulación de voluntariado:\n\n" .
        "Nombre: $nombre $apellido\n" .
        "Email: $email\n" .
        "Teléfono: $telefono\n" .
        "Fecha: $fecha ($dia)\n" .
        "Horario: $rango_legible\n\n" .
        "Experiencia con perros/gatos: " . si_no($datos['experiencia_perros_gatos'] ?? false) . "\n" .
        "Puede pasearlos: " . si_no($datos['puede_pasearlos'] ?? false) . "\n" .
        "Puede limpiar espacios: " . si_no($datos['puede_limpiar'] ?? false) . "\n" .
        "Puede colaborar con alimento/agua: " . si_no($datos['puede_colaborar_alimento'] ?? false) . "\n" .
        "Puede manipular animales grandes: " . si_no($datos['puede_manipular_grandes'] ?? false) . "\n" .
        "Limitaciones: " . (!empty($datos['limitaciones']) ? $datos['limitaciones'] : 'Ninguna') . "\n" .
        "Observaciones: " . (!empty($datos['observaciones']) ? $datos['observaciones'] : 'Ninguna') . "\n\n" .
        "Aceptó indicaciones del refugio: Sí\n" .
        "Aceptó avisar si no puede asistir: Sí\n";

    $resultadoRefugio = enviarMail(
        VOLUNTARIADO_EMAIL_REFUGIO,
        $asuntoRefugio,
        $cuerpoRefugio,
        null,
        'voluntariado-refugio-' . md5($email . $fecha . $rango_horario)
    );

    // -------- Mail al usuario --------
    $asuntoUsuario = 'Confirmación de postulación - Voluntariado Patitas Seguras';

    $cuerpoUsuario =
        "Hola $nombre,\n\n" .
        "Recibimos tu postulación para ser voluntario/a:\n\n" .
        "Fecha: $fecha ($dia)\n" .
        "Horario: $rango_legible\n" .
        "Dirección: " . VOLUNTARIADO_DIRECCION . "\n" .
        "Refugio: Patitas Seguras\n\n" .
        "Indicaciones básicas:\n" .
        "- Llegá 10 minutos antes.\n" .
        "- Traé ropa cómoda que se pueda ensuciar.\n" .
        "- Seguí las indicaciones del equipo del refugio.\n\n" .
        "En caso de no poder asistir, avisar por WhatsApp al número del refugio: " . VOLUNTARIADO_WHATSAPP . "\n\n" .
        "Gracias por sumarte 🐾";

    $resultadoUsuario = enviarMail(
        $email,
        $asuntoUsuario,
        $cuerpoUsuario,
        null,
        'voluntariado-usuario-' . md5($email . $fecha . $rango_horario)
    );


    // -------- WhatsApp al usuario --------
    $whatsapp_enviado = false;

    if (!empty($telefono)) {

        try {

            $resultadoWhatsapp = enviar_notificacion_whatsapp(
                $telefono,
                'voluntariado_confirmado',
                $nombre,
                ''
            );

            $whatsapp_enviado = $resultadoWhatsapp['success'] ?? true;

        } catch (Throwable $e) {

            error_log(
                'ERROR NOTIFICACION WHATSAPP VOLUNTARIADO: ' .
                $e->getMessage()
            );
        }
    }


    return [
        'mail_refugio_enviado' => $resultadoRefugio['ok'] ?? false,
        'mail_usuario_enviado' => $resultadoUsuario['ok'] ?? false,
        'whatsapp_enviado' => $whatsapp_enviado,
        'turno' => [
            'fecha' => $fecha,
            'dia' => $dia,
            'rango' => $rango_legible,
        ],
    ];
}

function listar_turnos_voluntariado(): array {
    $resultado = [];

    foreach (VOLUNTARIADO_TURNOS as $clave => $turno) {

        if ($turno['dia'] === 'Sábado') {
            $dia_semana = 6;
        } elseif ($turno['dia'] === 'Domingo') {
            $dia_semana = 0;
        } else {
            continue;
        }

        $resultado[] = [
            'clave' => $clave,
            'dia' => $turno['dia'],
            'dia_semana' => $dia_semana,
            'rango' => $turno['rango'],
            'horario' => $turno['horario']
        ];
    }

    return $resultado;
}