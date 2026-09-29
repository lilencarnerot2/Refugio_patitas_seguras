<?php

require_once __DIR__ . '/../repositories/contactos.php';


function listar_contactos_usuario(
    int $id_usuario
): array {

    if ($id_usuario <= 0) {
        throw new InvalidArgumentException(
            'El usuario no es válido.'
        );
    }

    return obtener_contactos_por_usuario(
        $id_usuario
    );
}


function registrar_contacto_usuario(
    int $id_usuario,
    array $datos
): array {

    if ($id_usuario <= 0) {
        throw new InvalidArgumentException(
            'El usuario no es válido.'
        );
    }

    $id_tipo_contacto =
        (int) ($datos['id_tipo_contacto'] ?? 0);

    $valor =
        trim($datos['valor'] ?? '');

    if ($id_tipo_contacto <= 0) {
        throw new InvalidArgumentException(
            'El tipo de contacto es obligatorio.'
        );
    }

    if ($valor === '') {
        throw new InvalidArgumentException(
            'El valor del contacto es obligatorio.'
        );
    }

    $datos['id_tipo_contacto'] =
        $id_tipo_contacto;

    $datos['valor'] =
        $valor;

    $datos['es_principal'] =
        filter_var(
            $datos['es_principal'] ?? false,
            FILTER_VALIDATE_BOOLEAN
        );

    return crear_contacto_usuario(
        $id_usuario,
        $datos
    );
}


function modificar_contacto_usuario(
    int $id_contacto,
    int $id_usuario,
    array $datos
): array|false {

    if ($id_contacto <= 0) {
        throw new InvalidArgumentException(
            'El contacto no es válido.'
        );
    }

    if ($id_usuario <= 0) {
        throw new InvalidArgumentException(
            'El usuario no es válido.'
        );
    }

    $id_tipo_contacto =
        (int) ($datos['id_tipo_contacto'] ?? 0);

    $valor =
        trim($datos['valor'] ?? '');

    if ($id_tipo_contacto <= 0) {
        throw new InvalidArgumentException(
            'El tipo de contacto es obligatorio.'
        );
    }

    if ($valor === '') {
        throw new InvalidArgumentException(
            'El valor del contacto es obligatorio.'
        );
    }

    $datos['id_tipo_contacto'] =
        $id_tipo_contacto;

    $datos['valor'] =
        $valor;

    $datos['es_principal'] =
        filter_var(
            $datos['es_principal'] ?? false,
            FILTER_VALIDATE_BOOLEAN
        );

    return actualizar_contacto_usuario(
        $id_contacto,
        $id_usuario,
        $datos
    );
}


function eliminar_contacto_usuario(
    int $id_contacto,
    int $id_usuario
): bool {

    if ($id_contacto <= 0) {
        throw new InvalidArgumentException(
            'El contacto no es válido.'
        );
    }

    return desactivar_contacto_usuario(
        $id_contacto,
        $id_usuario
    );
}