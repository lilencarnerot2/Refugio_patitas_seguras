<?php

require_once __DIR__ . '/../repositories/roles.php';

function listar_roles(): array
{
    return obtener_roles();
}


function buscar_rol_por_id(int $id): array|false
{
    if ($id <= 0) {
        throw new InvalidArgumentException(
            'El id del rol no es válido.'
        );
    }

    return obtener_rol_por_id($id);
}

function buscar_rol_por_nombre(string $nombre_rol): array|false
{
    $nombre_rol = trim($nombre_rol);

    if ($nombre_rol === '') {
        throw new InvalidArgumentException(
            'El nombre del rol no es válido.'
        );
    }

    return obtener_rol_por_nombre($nombre_rol);
}