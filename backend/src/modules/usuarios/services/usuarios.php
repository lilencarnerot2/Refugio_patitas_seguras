<?php

require_once __DIR__ . '/../repositories/usuarios.php';

function listar_usuarios(): array
{
    return obtener_usuarios();
}


function buscar_usuario_por_id(int $id): array|false
{
    if ($id <= 0) {
        throw new InvalidArgumentException(
            'El id del usuario no es válido.'
        );
    }

    return obtener_usuario_por_id($id);
}


function buscar_usuario_por_uid_pp2(string $uid_pp2): array|false
{
    $uid_pp2 = trim($uid_pp2);

    if ($uid_pp2 === '') {
        throw new InvalidArgumentException(
            'El uid_pp2 no es válido.'
        );
    }

    return obtener_usuario_por_uid_pp2($uid_pp2);
}

function crear_usuario_local_desde_padron(array $datos): array|false
{
    if (!isset($datos['id_rol']) || !isset($datos['nombre']) || !isset($datos['apellido']) || !isset($datos['uid_pp2'])) {
        throw new InvalidArgumentException(
            'Faltan datos para crear el usuario local.'
        );
    }

    return crear_usuario_desde_padron($datos);
}

function actualizar_usuario_local_desde_padron(int $id, array $datos): array|false
{
    if ($id <= 0) {
        throw new InvalidArgumentException(
            'El id del usuario no es válido.'
        );
    }

    return actualizar_usuario_desde_padron($id, $datos);
}