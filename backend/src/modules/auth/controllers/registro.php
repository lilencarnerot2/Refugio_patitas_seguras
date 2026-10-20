<?php

require_once __DIR__ . '/../services/integracion_registro.php';
require_once __DIR__ . '/../../usuarios/services/usuarios.php';
require_once __DIR__ . '/../../usuarios/services/roles.php';
require_once __DIR__ . '/../../../utils/response.php';
require_once __DIR__ . '/../../usuarios/services/contactos.php';


function registroController($datos) {
    
    // 1. Validar entrada
    $nombre = trim($datos['nombre'] ?? '');
    $apellido = trim($datos['apellido'] ?? '');
    $email = trim($datos['email'] ?? '');
    $password = $datos['password'] ?? '';
    $fecha_nacimiento = trim($datos['fecha_nacimiento'] ?? '');
    $telefono = trim($datos['telefono'] ?? '');

    if ($nombre === '' || $apellido === '' || $email === '' || $password === '' || $fecha_nacimiento === '' || $telefono === '') {
        return responderJson(
            false,
            'Todos los campos son obligatorios',
            null,
            400
        );
    }
        
    if (strlen($password) < 8 || strlen($password) > 12) {
        return responderJson(false, 'La contraseña debe tener entre 8 y 12 caracteres.', null, 400);
    }

    // 1.5. Validar formato de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return responderJson(false, 'El email no tiene un formato válido', null, 400);
    }

    $fecha = DateTime::createFromFormat(
    'Y-m-d',
    $fecha_nacimiento
    );
    if (!$fecha || $fecha->format('Y-m-d') !== $fecha_nacimiento) {
        return responderJson(
            false,
            'La fecha de nacimiento no es válida',
            null,
            400
        );
    }
    $hoy = new DateTime();
    if ($fecha > $hoy) {
        return responderJson(
            false,
            'La fecha de nacimiento no puede ser futura',
            null,
            400
        );
    }

    // 2. Normalizar email
    $email = strtolower($email);

    // 3. Llamar al Padrón
    $resultado = registrarEnPadron([
        'nombre' => $nombre,
        'apellido' => $apellido,
        'email' => $email,
        'password' => $password
    ]);

    if (!$resultado['ok']) {
        $status = ($resultado['status'] === 401) ? 401 : 500;
        $mensaje = $resultado['error'] ?? 'Error al registrar usuario';
        return responderJson(false, $mensaje, null, $status);
    }

    // 4. El Padrón devuelve { ok, mensaje, usuario_id, proyecto_id }
    $usuarioPadron = $resultado['data'];
    $uid_pp2 = (string) $usuarioPadron['usuario_id'];

    $rol_usuario = buscar_rol_por_nombre('Usuario');

    if (!$rol_usuario) {
        return responderJson(
            false,
            'No existe el rol Usuario en la base local.',
            null,
            500
        );
    }

    // 5. Buscar si ya existe el usuario local
    $usuarioLocal = buscar_usuario_por_uid_pp2($uid_pp2);

    if (!$usuarioLocal) {
        $usuarioLocal = crear_usuario_local_desde_padron(
            [
                'id_rol' => (int) $rol_usuario['id'],
                'nombre' => $nombre,
                'apellido' => $apellido,
                'fecha_nacimiento' => $fecha_nacimiento,
                'uid_pp2' => $uid_pp2,
                'password' => $password
            ]
        );
    } else {
        $usuarioLocal = actualizar_usuario_local_desde_padron((int) $usuarioLocal['id'],
            [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'fecha_nacimiento' => $fecha_nacimiento
            ]
        );
    }

    registrar_contacto_usuario((int) $usuarioLocal['id'],
        [
            'id_tipo_contacto' => 2,
            'valor' => $telefono,
            'es_principal' => true
        ]
    );

    // 6. Verificar que esté habilitado
    if (!$usuarioLocal['estado']) {
        return responderJson(false, 'Usuario deshabilitado', null, 403);
    }

    // 7. Crear la sesión PHP (login automático después del registro)
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['usuario_id'] = $usuarioLocal['id'];
    $_SESSION['usuario_uid_pp2'] = $usuarioLocal['uid_pp2'];
    $_SESSION['usuario_nombre'] = $usuarioLocal['nombre'];
    $_SESSION['usuario_apellido'] = $usuarioLocal['apellido'];
    $_SESSION['usuario_rol'] = $usuarioLocal['id_rol'];


    

    // 8. Devolver OK
    return responderJson(true, 'Usuario registrado correctamente', [
        'usuario' => [
            'id' => $usuarioLocal['id'],
            'nombre' => $usuarioLocal['nombre'],
            'apellido' => $usuarioLocal['apellido'],
            'email' => $email,
            'fecha_nacimiento' => $usuarioLocal['fecha_nacimiento'],
            'id_rol' => $usuarioLocal['id_rol']
        ]
    ], 201);
}

function manejarRegistro()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return responderJson(false, 'Método no permitido', null, 405);
    }

    $body = file_get_contents('php://input');
    $datos = json_decode($body, true);

    if (!is_array($datos)) {
        return responderJson(false, 'JSON inválido', null, 400);
    }

    return registroController($datos);
}