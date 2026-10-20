<?php

require_once __DIR__ . '/../repositories/motivos_adopcion.php';

function listar_motivos_adopcion(): array {
    return obtener_motivos_adopcion();
}


function registrar_motivo_adopcion(array $datos): array {
    $descripcion = trim(
        $datos['descripcion'] ?? ''
    );
    if ($descripcion === '') {
        throw new InvalidArgumentException(
            'La descripción del motivo es obligatoria.'
        );
    }
    if (existe_motivo_adopcion_por_descripcion($descripcion)) {
        throw new InvalidArgumentException(
            'Ya existe un motivo de adopción con esa descripción.'
        );
    }
    $motivo = crear_motivo_adopcion($descripcion);
    if (!$motivo) {
        throw new RuntimeException(
            'No se pudo crear el motivo de adopción.'
        );
    }
    return $motivo;
}


function modificar_motivo_adopcion(
    int $id,
    array $datos
): array {
    if ($id <= 0) {
        throw new InvalidArgumentException(
            'El ID del motivo de adopción no es válido.'
        );
    }
    if (!existe_motivo_adopcion($id)) {
        throw new InvalidArgumentException(
            'El motivo de adopción indicado no existe.'
        );
    }
    $descripcion = trim(
        $datos['descripcion'] ?? ''
    );
    if ($descripcion === '') {
        throw new InvalidArgumentException(
            'La descripción del motivo es obligatoria.'
        );
    }
    if (
        existe_motivo_adopcion_por_descripcion(
            $descripcion,
            $id
        )
    ) {
        throw new InvalidArgumentException(
            'Ya existe un motivo de adopción con esa descripción.'
        );
    }
    $motivo = actualizar_motivo_adopcion(
        $id,
        $descripcion
    );

    if (!$motivo) {
        throw new RuntimeException(
            'No se pudo actualizar el motivo de adopción.'
        );
    }
    return $motivo;
}


function dar_baja_motivo_adopcion(int $id): array {
    if ($id <= 0) {
        throw new InvalidArgumentException(
            'El ID del motivo de adopción no es válido.'
        );
    }
    if (!existe_motivo_adopcion($id)) {
        throw new InvalidArgumentException(
            'El motivo de adopción indicado no existe.'
        );
    }
    $motivo = eliminar_motivo_adopcion($id);
    if (!$motivo) {
        throw new RuntimeException(
            'No se pudo eliminar el motivo de adopción.'
        );
    }
    return $motivo;
}