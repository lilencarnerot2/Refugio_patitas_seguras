<?php

require_once __DIR__ . '/../repositories/fotos_mascotas.php';

function procesarFotoMascota($idMascota, $foto) {
    $tamanoMaximo = 5 * 1024 * 1024;

    // verifica error de subida
    if ($foto['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Error al subir la imagen.');
    }

    // maximo 5 MB
    if ($foto['size'] > $tamanoMaximo) {
        throw new Exception('La imagen no puede superar los 5MB.');
    }

    // tipos de imagenes permitidos
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

    // nombre unico para la imagen
    $nombreArchivo =
        'mascota_' .
        $idMascota .
        '_' .
        time() .
        '.' .
        $extension;

    // carpeta fisica
    $directorio =
        __DIR__ .
        '/../../../../public/uploads/mascotas/';

    if (!is_dir($directorio)) {
        mkdir($directorio, 0775, true);
    }

    $rutaCompleta = $directorio . $nombreArchivo;

    // guardar imagen
    if (!move_uploaded_file(
        $foto['tmp_name'],
        $rutaCompleta
    )) {
        throw new Exception(
            'No se pudo guardar la imagen.'
        );
    }

    // ruta que vamos a guardar en postgres
    $fotoUrl =
        '/uploads/mascotas/' .
        $nombreArchivo;

    // actualizar foto_url
    $mascota = actualizarFotoMascota(
        $idMascota,
        $fotoUrl
    );

    // si la mascota no existe, borrar la foto que acabamos de guardar
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