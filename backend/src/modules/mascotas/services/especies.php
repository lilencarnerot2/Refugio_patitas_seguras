<?php

require_once __DIR__ . '/../repositories/especies.php';

function listarEspecies() {
    return obtenerEspecies();
}


function registrarEspecie(array $datos): array {
    $nombre = trim($datos['nombre_especie'] ?? '');
    if ($nombre === '') {
        throw new InvalidArgumentException(
            'El nombre de la especie es obligatorio.'
        );
    }
    if (existeEspeciePorNombre($nombre)) {
        throw new InvalidArgumentException(
            'Ya existe una especie con ese nombre.'
        );
    }
    $especie = crearEspecie($nombre);
    if (!$especie) {
        throw new RuntimeException(
            'No se pudo crear la especie.'
        );
    }
    return $especie;
}


function modificarEspecie(int $id, array $datos): array{
    if ($id <= 0) {
        throw new InvalidArgumentException(
            'El ID de especie no es válido.'
        );
    }
    if (!existeEspecie($id)) {
        throw new InvalidArgumentException(
            'La especie indicada no existe.'
        );
    }
    $nombre = trim($datos['nombre_especie'] ?? '');
    if ($nombre === '') {
        throw new InvalidArgumentException(
            'El nombre de la especie es obligatorio.'
        );
    }
    if (existeEspeciePorNombre($nombre, $id)) {
        throw new InvalidArgumentException(
            'Ya existe una especie con ese nombre.'
        );
    }
    $especie = actualizarEspecie(
        $id,
        $nombre
    );
    if (!$especie) {
        throw new RuntimeException(
            'No se pudo actualizar la especie.'
        );
    }

    return $especie;
}


function darBajaEspecie(int $id): array {
    if ($id <= 0) {
        throw new InvalidArgumentException(
            'El ID de especie no es válido.'
        );
    }
    if (!existeEspecie($id)) {
        throw new InvalidArgumentException(
            'La especie indicada no existe.'
        );
    }
    $especie = eliminarEspecie($id);

    if (!$especie) {
        throw new RuntimeException(
            'No se pudo eliminar la especie.'
        );
    }
    return $especie;
}