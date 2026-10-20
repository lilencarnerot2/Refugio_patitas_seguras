<?php
require_once __DIR__ . '/../modules/usuarios/repositories/usuarios.php';

function responderJson(
    $success,
    $message = null,
    $data = null,
    $status = 200
) {
    http_response_code($status);

    $respuesta = [
        'success' => $success
    ];

    if ($message !== null) {
        $respuesta['message'] = $message;
    }

    if ($data !== null) {
        $respuesta['data'] = $data;
    }

    echo json_encode(
        $respuesta,
        JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
    );
}

function usuarioEsMayorDeEdad($idUsuario): bool {
    $usuario = obtener_usuario_por_id($idUsuario);

    if (!$usuario || empty($usuario['fecha_nacimiento'])) {
        return false;
    }

    $fechaNacimiento = new DateTime($usuario['fecha_nacimiento']);
    $hoy = new DateTime();

    $edad = $hoy->diff($fechaNacimiento)->y;

    return $edad >= 18;
}