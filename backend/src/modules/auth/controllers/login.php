<?php

require_once __DIR__ . '/../services/integracion_registro.php';
require_once __DIR__ . '/../../usuarios/services/usuarios.php';
require_once __DIR__ . '/../../usuarios/services/roles.php';
require_once __DIR__ . '/../../../utils/response.php';

function loginController($datos)
{
    // 1. Validar entrada
    $email = trim($datos['email'] ?? '');
    $password = $datos['password'] ?? '';

    if ($email === '' || $password === '') {
        return responderJson(
            false,
            'Email y contraseña son obligatorios',
            null,
            400
        );
    }

    // 2. Normalizar email
    $email = strtolower($email);

    // 3. Validar credenciales contra el Padrón PP2
    $resultado = loginEnPadron($email, $password);

    if (!$resultado['ok']) {
        $status = ($resultado['status'] === 401)
            ? 401
            : 500;

        return responderJson(
            false,
            $resultado['error'] ?? 'Error al validar credenciales',
            null,
            $status
        );
    }

    // 4. Obtener datos del usuario del Padrón
    $usuarioPadron = $resultado['data'];

    $uid_pp2 = (string) $usuarioPadron['id'];

    $rol_usuario = buscar_rol_por_nombre('Usuario');

    if (!$rol_usuario) {
        return responderJson(
            false,
            'No existe el rol Usuario en la base local.',
            null,
            500
        );
    }

    // 5. Buscar el usuario en nuestra base de datos
    $usuarioLocal = buscar_usuario_por_uid_pp2($uid_pp2);

    if ($usuarioLocal === false) {
        $usuarioLocal = crear_usuario_local_desde_padron([
            'id_rol' => (int) $rol_usuario['id'],
            'nombre' => $usuarioPadron['nombre'],
            'apellido' => $usuarioPadron['apellido'],
            'uid_pp2' => $uid_pp2,
            'password' => $password
        ]);
    } else {
        $usuarioLocal = actualizar_usuario_local_desde_padron(
            (int) $usuarioLocal['id'],
            [
                'nombre' => $usuarioPadron['nombre'],
                'apellido' => $usuarioPadron['apellido']
            ]
        );
    }

    // 6. Verificar que el usuario esté habilitado
    if (!$usuarioLocal['estado']) {
        return responderJson(
            false,
            'Usuario deshabilitado',
            null,
            403
        );
    }

    // 7. Crear sesión PHP
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['usuario_id'] = $usuarioLocal['id'];
    $_SESSION['usuario_uid_pp2'] = $usuarioLocal['uid_pp2'];
    $_SESSION['usuario_nombre'] = $usuarioLocal['nombre'];
    $_SESSION['usuario_apellido'] = $usuarioLocal['apellido'];
    $_SESSION['usuario_rol'] = $usuarioLocal['id_rol'];

    // 8. Responder correctamente al frontend
    return responderJson(
        true,
        'Inicio de sesión exitoso',
        [
            'usuario' => [
                'id' => $usuarioLocal['id'],
                'nombre' => $usuarioLocal['nombre'],
                'apellido' => $usuarioLocal['apellido'],
                'email' => $email,
                'id_rol' => $usuarioLocal['id_rol']
            ]
        ],
        200
    );
}

function manejarLogin()
{
    // 1. Verificar método HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return responderJson(
            false,
            'Método no permitido',
            null,
            405
        );
    }

    // 2. Leer el body JSON
    $body = file_get_contents('php://input');

    $datos = json_decode($body, true);

    if (!is_array($datos)) {
        return responderJson(
            false,
            'JSON inválido',
            null,
            400
        );
    }

    // 3. Ejecutar login
    return loginController($datos);
}