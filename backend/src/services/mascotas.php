<?php

require_once __DIR__ . '/../repositories/mascotas.php';

function listarMascotas(){
    return obtenerMascotas();
}

function buscarMascotaPorId($id){
    return obtenerMascotaPorId($id);
}

function registrarMascota($datos)
{
    validarDatosMascota($datos);

    $datosMascota = [
        'id_especie' => (int)$datos['id_especie'],
        'id_raza' => (int)$datos['id_raza'],
        'nombre' => trim($datos['nombre']),
        'sexo' => $datos['sexo'],
        'tamaño' => $datos['tamaño'],
        'fecha_nacimiento_estimada' =>
            $datos['fecha_nacimiento_estimada'] ?? null,
        'historial' =>
            $datos['historial'] ?? null,
        'foto_url' =>
            $datos['foto_url'] ?? null
    ];

    return crearMascota($datosMascota);
}

function validarDatosMascota($datos)
{
    $camposObligatorios = [
        'id_especie',
        'id_raza',
        'nombre',
        'sexo',
        'tamaño'
    ];

    foreach ($camposObligatorios as $campo) {
        if (!isset($datos[$campo]) || $datos[$campo] === '') {
            throw new Exception(
                "El campo $campo es obligatorio."
            );
        }
    }

    if (!is_numeric($datos['id_especie'])) {
        throw new Exception(
            'El ID de especie debe ser numérico.'
        );
    }

    if (!is_numeric($datos['id_raza'])) {
        throw new Exception(
            'El ID de raza debe ser numérico.'
        );
    }

    $nombre = trim($datos['nombre']);

    if ($nombre === '') {
        throw new Exception('El nombre es obligatorio.');
    }

    if (strlen($nombre) > 30) {
        throw new Exception('El nombre no puede superar los 30 caracteres.');
    }

    $sexosPermitidos = [
        'macho',
        'hembra'
    ];

    if (!in_array($datos['sexo'], $sexosPermitidos, true)) {
        throw new Exception(
            'El sexo ingresado no es válido.'
        );
    }

    $tamañosPermitidos = [
        'pequeño',
        'mediano',
        'grande'
    ];

    if (!in_array($datos['tamaño'], $tamañosPermitidos, true)) {
        throw new Exception(
            'El tamaño ingresado no es válido.'
        );
    }

    if (!existeEspecie((int)$datos['id_especie'])) {
        throw new Exception(
            'La especie indicada no existe.'
        );
    }

    if (!existeRazaParaEspecie(
        (int)$datos['id_raza'],
        (int)$datos['id_especie']
    )) {
        throw new Exception(
            'La raza indicada no corresponde a la especie seleccionada.'
        );
    }

    if (
        !empty($datos['fecha_nacimiento_estimada'])
        && !validarFechaMascota($datos['fecha_nacimiento_estimada'])
    ) {
        throw new Exception(
            'La fecha de nacimiento estimada no es válida.'
        );
    }


    if (
        !empty($datos['foto_url'])
        && !filter_var($datos['foto_url'], FILTER_VALIDATE_URL)
    ) {
        throw new Exception(
            'La URL de la foto no es válida.'
        );
    }
}

function modificarMascota($id, $datos)
{
    $mascotaExistente = obtenerMascotaPorId($id);

    if (!$mascotaExistente) {
        throw new Exception('Mascota no encontrada.');
    }

    validarDatosMascota($datos);

    $datosMascota = [
        'id_especie' => (int)$datos['id_especie'],
        'id_raza' => (int)$datos['id_raza'],
        'nombre' => trim($datos['nombre']),
        'sexo' => $datos['sexo'],
        'tamaño' => $datos['tamaño'],
        'fecha_nacimiento_estimada' =>
            $datos['fecha_nacimiento_estimada'] ?? null,
        'historial' =>
            $datos['historial'] ?? null,
        'foto_url' =>
            $datos['foto_url'] ?? null
    ];

    return actualizarMascota(
        $id,
        $datosMascota
    );
}

function eliminarMascota($id)
{
    $mascota = obtenerMascotaPorId($id);

    if (!$mascota) {
        throw new Exception('Mascota no encontrada.');
    }

    return desactivarMascota($id);
}

function validarFechaMascota($fecha)
{
    $fechaIngresada = DateTime::createFromFormat('Y-m-d', $fecha);

    if (
        !$fechaIngresada
        || $fechaIngresada->format('Y-m-d') !== $fecha
    ) {
        return false;
    }

    $hoy = new DateTime();

    return $fechaIngresada <= $hoy;
}