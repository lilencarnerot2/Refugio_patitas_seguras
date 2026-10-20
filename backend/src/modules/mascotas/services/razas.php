<?php

require_once __DIR__ . '/../repositories/razas.php';
require_once __DIR__ . '/../repositories/especies.php';

function listarRazasPorEspecie($idEspecie) {
    if ($idEspecie <= 0) {
        throw new Exception(
            'El ID de especie no es válido.'
        );
    }

    if (!existeEspecie($idEspecie)) {
        throw new Exception(
            'La especie indicada no existe.'
        );
    }

    return obtenerRazasPorEspecie($idEspecie);
}


function registrarRaza(array $datos): array {
    $idEspecie = (int)($datos['id_especie'] ?? 0);
    $nombre = trim($datos['nombre_raza'] ?? '');

    if ($idEspecie <= 0) {
        throw new InvalidArgumentException(
            'El ID de especie no es válido.'
        );
    }

    if (!existeEspecie($idEspecie)) {
        throw new InvalidArgumentException(
            'La especie indicada no existe.'
        );
    }

    if ($nombre === '') {
        throw new InvalidArgumentException(
            'El nombre de la raza es obligatorio.'
        );
    }

    if (existeRazaPorNombre($idEspecie, $nombre)) {
        throw new InvalidArgumentException(
            'Ya existe esa raza para la especie indicada.'
        );
    }

    $raza = crearRaza(
        $idEspecie,
        $nombre
    );

    if (!$raza) {
        throw new RuntimeException(
            'No se pudo crear la raza.'
        );
    }

    return $raza;
}


function modificarRaza(int $id, array $datos): array {
    if ($id <= 0) {
        throw new InvalidArgumentException(
            'El ID de raza no es válido.'
        );
    }

    if (!existeRazaPorId($id)) {
        throw new InvalidArgumentException(
            'La raza indicada no existe.'
        );
    }

    $idEspecie = (int)($datos['id_especie'] ?? 0);
    $nombre = trim($datos['nombre_raza'] ?? '');

    if ($idEspecie <= 0) {
        throw new InvalidArgumentException(
            'El ID de especie no es válido.'
        );
    }

    if (!existeEspecie($idEspecie)) {
        throw new InvalidArgumentException(
            'La especie indicada no existe.'
        );
    }

    if ($nombre === '') {
        throw new InvalidArgumentException(
            'El nombre de la raza es obligatorio.'
        );
    }

    if (
        existeRazaPorNombre(
            $idEspecie,
            $nombre,
            $id
        )
    ) {
        throw new InvalidArgumentException(
            'Ya existe esa raza para la especie indicada.'
        );
    }

    $raza = actualizarRaza(
        $id,
        $idEspecie,
        $nombre
    );

    if (!$raza) {
        throw new RuntimeException(
            'No se pudo actualizar la raza.'
        );
    }

    return $raza;
}


function darBajaRaza(int $id): array {
    if ($id <= 0) {
        throw new InvalidArgumentException(
            'El ID de raza no es válido.'
        );
    }

    if (!existeRazaPorId($id)) {
        throw new InvalidArgumentException(
            'La raza indicada no existe.'
        );
    }

    $raza = eliminarRaza($id);

    if (!$raza) {
        throw new RuntimeException(
            'No se pudo eliminar la raza.'
        );
    }

    return $raza;
}