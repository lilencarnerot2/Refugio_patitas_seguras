<?php

require_once __DIR__ . '/../repositories/roles.php';

function listar_roles(): array{
    return obtener_roles();
}


function buscar_rol_por_id(int $id): array|false{
    if ($id <= 0) {
        throw new InvalidArgumentException(
            'El id del rol no es válido.'
        );
    }

    return obtener_rol_por_id($id);
}

function buscar_rol_por_nombre(string $nombre_rol): array|false{
    $nombre_rol = trim($nombre_rol);

    if ($nombre_rol === '') {
        throw new InvalidArgumentException(
            'El nombre del rol no es válido.'
        );
    }

    return obtener_rol_por_nombre($nombre_rol);
}

function registrar_rol(array $datos): array{
    $nombre_rol =
        trim($datos['nombre_rol'] ?? '');

    $descripcion =
        trim($datos['descripcion'] ?? '');

    if ($nombre_rol === '') {
        throw new InvalidArgumentException(
            'El nombre del rol es obligatorio.'
        );
    }

    if (existe_rol_por_nombre($nombre_rol)) {
        throw new InvalidArgumentException(
            'Ya existe un rol con ese nombre.'
        );
    }

    $rol_creado = crear_rol([
    'nombre_rol' => $nombre_rol,
    'descripcion' =>
        $descripcion !== ''
            ? $descripcion
            : null
    ]);

    return $rol_creado;
}


function modificar_rol(
    int $id,
    array $datos): array|false {

    if ($id <= 0) {
        throw new InvalidArgumentException(
            'El id del rol no es válido.'
        );
    }

    $nombre_rol =
        trim($datos['nombre_rol'] ?? '');

    $descripcion =
        trim($datos['descripcion'] ?? '');

    if ($nombre_rol === '') {
        throw new InvalidArgumentException(
            'El nombre del rol es obligatorio.'
        );
    }

    if (
        existe_rol_por_nombre(
            $nombre_rol,
            $id
        )
    ) {
        throw new InvalidArgumentException(
            'Ya existe otro rol con ese nombre.'
        );
    }

    $rol_anterior = obtener_rol_por_id($id);

    if (!$rol_anterior) {
        return false;
    }

    $rol_actualizado = actualizar_rol(
        $id,
        [
            'nombre_rol' => $nombre_rol,
            'descripcion' =>
                $descripcion !== ''
                    ? $descripcion
                    : null
        ]
    );

    if (!$rol_actualizado) {
        return false;
    }

    return $rol_actualizado;
}


function eliminar_rol(int $id): bool{
    if ($id <= 0) {
        throw new InvalidArgumentException(
            'El id del rol no es válido.'
        );
    }

    $rol_anterior = obtener_rol_por_id($id);

    if (!$rol_anterior) {
        return false;
    }

    $eliminado = desactivar_rol($id);

    if (!$eliminado) {
        return false;
    }

    $rol_desactivado = $rol_anterior;
    $rol_desactivado['estado'] = false;

    return true;
}