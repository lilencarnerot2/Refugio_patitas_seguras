<?php

require_once __DIR__ . '/../services/integracion_registro.php';
require_once __DIR__ . '/../repositories/usuarios.php';
require_once __DIR__ . '/../utils/response.php';

function registroController($datos)
{
    // 1. Validar entrada
    $nombre = trim($datos['nombre'] ?? '');
    $apellido = trim($datos['apellido'] ?? '');
    $email = trim($datos['email'] ?? '');
    $password = $datos['password'] ?? '';

    if ($nombre === '' || $apellido === '' || $email === '' || $password === '') {
        return responderJson(false, 'Todos los campos son obligatorios', null, 400);
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

    // 5. Buscar si ya existe el usuario local
    $usuarioLocal = buscarUsuarioPorUidPP2($uid_pp2);

    if ($usuarioLocal === false) {
        // No existe → crearlo con rol "Usuario" (id_rol = 1)
        $usuarioLocal = crearUsuarioDesdePadron([
            'id_rol' => 1,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'uid_pp2' => $uid_pp2
        ]);
    } else {
        // Existe → actualizar nombre/apellido
        $usuarioLocal = actualizarUsuarioDesdePadron($usuarioLocal['id'], [
            'nombre' => $nombre,
            'apellido' => $apellido
        ]);
    }

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