<?php

require_once __DIR__ . '/../repositories/fotos_mascotas.php';



// FOTO PRINCIPAL
function procesarFotoMascota($idMascota, $foto) {
    $tamanoMaximo = 5 * 1024 * 1024;

    if ($foto['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Error al subir la imagen.');
    }

    if ($foto['size'] > $tamanoMaximo) {
        throw new Exception('La imagen no puede superar los 5MB.');
    }

    $tiposPermitidos = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp'
    ];

    $tipoArchivo = mime_content_type($foto['tmp_name']);

    if (!isset($tiposPermitidos[$tipoArchivo])) {
        throw new Exception(
            'Formato de imagen no permitido. Solo JPG, PNG o WEBP.'
        );
    }

    $extension = $tiposPermitidos[$tipoArchivo];

    $nombreArchivo =
        'mascota_' .
        $idMascota .
        '_' .
        time() .
        '.' .
        $extension;

    $directorio =
        __DIR__ .
        '/../../../../public/uploads/mascotas/';

    if (!is_dir($directorio)) {
        mkdir($directorio, 0775, true);
    }

    $rutaCompleta = $directorio . $nombreArchivo;

    if (!move_uploaded_file(
        $foto['tmp_name'],
        $rutaCompleta
    )) {
        throw new Exception(
            'No se pudo guardar la imagen.'
        );
    }

    $fotoUrl =
        '/uploads/mascotas/' .
        $nombreArchivo;

    $mascota = actualizarFotoMascota(
        $idMascota,
        $fotoUrl
    );

    if (!$mascota) {
        if (file_exists($rutaCompleta)) {
            unlink($rutaCompleta);
        }

        throw new Exception(
            'Mascota no encontrada.'
        );
    }

    return $mascota;
}



// FOTOS ADICIONALES
function procesarFotoAdicionalMascota(int $idMascota, array $foto): array {
    $tamanoMaximo = 5 * 1024 * 1024;

    // máximo 6 fotos adicionales
    $fotosActuales = obtenerFotosMascota($idMascota);

    if (count($fotosActuales) >= 6) {
        throw new InvalidArgumentException(
            'La mascota ya tiene el máximo de 6 fotos adicionales.'
        );
    }

    if ($foto['error'] !== UPLOAD_ERR_OK) {
        throw new Exception(
            'Error al subir la imagen.'
        );
    }

    if ($foto['size'] > $tamanoMaximo) {
        throw new Exception(
            'La imagen no puede superar los 5MB.'
        );
    }

    $tiposPermitidos = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp'
    ];

    $tipoArchivo = mime_content_type(
        $foto['tmp_name']
    );

    if (!isset($tiposPermitidos[$tipoArchivo])) {
        throw new Exception(
            'Formato de imagen no permitido. Solo JPG, PNG o WEBP.'
        );
    }

    $extension = $tiposPermitidos[$tipoArchivo];

    $nombreArchivo =
        'mascota_' .
        $idMascota .
        '_extra_' .
        time() .
        '.' .
        $extension;

    $directorio =
        __DIR__ .
        '/../../../../public/uploads/mascotas/';

    if (!is_dir($directorio)) {
        mkdir($directorio, 0775, true);
    }

    $rutaCompleta =
        $directorio .
        $nombreArchivo;

    if (!move_uploaded_file(
        $foto['tmp_name'],
        $rutaCompleta
    )) {
        throw new Exception(
            'No se pudo guardar la imagen.'
        );
    }

    $fotoUrl =
        '/uploads/mascotas/' .
        $nombreArchivo;

    $fotoGuardada = agregarFotoMascota(
        $idMascota,
        $fotoUrl
    );

    if (!$fotoGuardada) {
        if (file_exists($rutaCompleta)) {
            unlink($rutaCompleta);
        }

        throw new Exception(
            'No se pudo guardar la foto adicional.'
        );
    }

    return $fotoGuardada;
}



// LISTAR FOTOS ADICIONALES
function listarFotosMascota(int $idMascota): array {
    if ($idMascota <= 0) {
        throw new InvalidArgumentException(
            'El id de la mascota no es válido.'
        );
    }

    return obtenerFotosMascota($idMascota);
}



// ELIMINAR FOTO ADICIONAL
function borrarFotoMascota(
    int $idFoto,
    int $idMascota
): array
{
    if ($idFoto <= 0 || $idMascota <= 0) {
        throw new InvalidArgumentException(
            'Los datos de la foto no son válidos.'
        );
    }

    $foto = eliminarFotoMascota(
        $idFoto,
        $idMascota
    );

    if (!$foto) {
        throw new RuntimeException(
            'Foto no encontrada.'
        );
    }

    $rutaFisica =
        __DIR__ .
        '/../../../../public' .
        $foto['foto_url'];

    if (file_exists($rutaFisica)) {
        unlink($rutaFisica);
    }

    return $foto;
}