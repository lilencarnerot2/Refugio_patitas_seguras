<?php

require_once __DIR__ . '/../repositories/roles.php';
require_once __DIR__ . '/../repositories/usuarios.php';
require_once __DIR__ . '/../../auditoria/services/auditorias.php';

function listar_usuarios(): array{
    return obtener_usuarios();
}


function buscar_usuario_por_id(int $id): array|false{
    if ($id <= 0) {
        throw new InvalidArgumentException(
            'El id del usuario no es válido.'
        );
    }

    return obtener_usuario_por_id($id);
}


function buscar_usuario_por_uid_pp2(string $uid_pp2): array|false{
    $uid_pp2 = trim($uid_pp2);

    if ($uid_pp2 === '') {
        throw new InvalidArgumentException(
            'El uid_pp2 no es válido.'
        );
    }

    return obtener_usuario_por_uid_pp2($uid_pp2);
}

function crear_usuario_local_desde_padron(array $datos): array|false{
    if (!isset($datos['id_rol']) || !isset($datos['nombre']) || !isset($datos['apellido']) || !isset($datos['uid_pp2'])) {
        throw new InvalidArgumentException(
            'Faltan datos para crear el usuario local.'
        );
    }

    return crear_usuario_desde_padron($datos);
}

function actualizar_usuario_local_desde_padron(int $id, array $datos): array|false{
    if ($id <= 0) {
        throw new InvalidArgumentException(
            'El id del usuario no es válido.'
        );
    }

    return actualizar_usuario_desde_padron($id, $datos);
}

function modificar_usuario(
    int $id,
    array $datos
): array|false{
    if ($id <= 0) {
        throw new InvalidArgumentException(
            'El id del usuario no es válido.'
        );
    }

    $usuario = obtener_usuario_por_id($id);

    if (!$usuario) {
        return false;
    }

    $nombre = trim($datos['nombre'] ?? '');
    $apellido = trim($datos['apellido'] ?? '');
    $fecha_nacimiento = trim(
        $datos['fecha_nacimiento'] ?? ''
    );

    if ($nombre === '') {
        throw new InvalidArgumentException(
            'El nombre es obligatorio.'
        );
    }

    if ($apellido === '') {
        throw new InvalidArgumentException(
            'El apellido es obligatorio.'
        );
    }

    if ($fecha_nacimiento === '') {
        throw new InvalidArgumentException(
            'La fecha de nacimiento es obligatoria.'
        );
    }

    $fecha = DateTime::createFromFormat(
        'Y-m-d',
        $fecha_nacimiento
    );

    if (
        !$fecha ||
        $fecha->format('Y-m-d') !== $fecha_nacimiento
    ) {
        throw new InvalidArgumentException(
            'La fecha de nacimiento no es válida.'
        );
    }

    $usuario_actualizado = actualizar_usuario(
        $id,
        [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'fecha_nacimiento' => $fecha_nacimiento
        ]
    );

    if (!$usuario_actualizado) {
        return false;
    }

    registrar_auditoria(
        'usuarios',
        'UPDATE',
        $usuario,
        $usuario_actualizado
    );

    return $usuario_actualizado;
}


function eliminar_usuario(
    int $id,
    int $id_usuario_actual,
    string $rol_usuario_actual
): bool{
    if ($id <= 0) {
        throw new InvalidArgumentException(
            'El id del usuario no es válido.'
        );
    }

    if ($id === $id_usuario_actual) {
        throw new InvalidArgumentException(
            'No podés deshabilitar tu propio usuario.'
        );
    }

    $usuario = obtener_usuario_por_id($id);

    if (!$usuario) {
        return false;
    }

    if (
        $rol_usuario_actual === 'Administrador' &&
        $usuario['nombre_rol'] !== 'Usuario'
    ) {
        throw new InvalidArgumentException(
            'Un Administrador solo puede deshabilitar usuarios comunes.'
        );
    }

    $eliminado = desactivar_usuario($id);

    if (!$eliminado) {
        return false;
    }

    $usuario_desactivado = $usuario;
    $usuario_desactivado['estado'] = false;

    registrar_auditoria(
        'usuarios',
        'DELETE',
        $usuario,
        $usuario_desactivado
    );

return true;
}


function cambiar_rol_usuario(
    int $id_usuario,
    int $id_rol,
    int $id_usuario_actual
): bool{
    if ($id_usuario <= 0) {
        throw new InvalidArgumentException(
            'El id del usuario no es válido.'
        );
    }

    if ($id_rol <= 0) {
        throw new InvalidArgumentException(
            'El id del rol no es válido.'
        );
    }

    if ($id_usuario === $id_usuario_actual) {
        throw new InvalidArgumentException(
            'No podés cambiar tu propio rol.'
        );
    }

    $usuario = obtener_usuario_por_id($id_usuario);

    if (!$usuario) {
        return false;
    }

    $rol = obtener_rol_por_id($id_rol);

    if (!$rol) {
        throw new InvalidArgumentException(
            'El rol seleccionado no existe.'
        );
    }

    if (
        isset($rol['estado']) &&
        !$rol['estado']
    ) {
        throw new InvalidArgumentException(
            'El rol seleccionado está deshabilitado.'
        );
    }

    $actualizado = actualizar_rol_usuario(
        $id_usuario,
        $id_rol
    );

    if (!$actualizado) {
    return false;
    }

    $usuario_actualizado = obtener_usuario_por_id(
        $id_usuario
    );

    registrar_auditoria(
        'usuarios',
        'UPDATE',
        $usuario,
        $usuario_actualizado ?: null
    );

    return true;
}