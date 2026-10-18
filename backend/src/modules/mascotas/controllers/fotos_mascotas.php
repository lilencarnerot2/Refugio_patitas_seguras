<?php

require_once __DIR__ . '/../services/foto_mascotas.php';
require_once __DIR__ . '/../../../utils/response.php';

function manejarFotoMascota() {
    $metodo = $_SERVER['REQUEST_METHOD'] ?? '';
    switch ($metodo) {
        case 'POST':
            manejarPostFotoMascota();
            break;
        default:
            responderJson(
                false,
                'Método no permitido.',
                null,
                405
            );
            break;
    }
}

function manejarPostFotoMascota() {
    $id = $_GET['id'] ?? null;
    if ($id === null || !ctype_digit($id)) {
        responderJson(
            false,
            'El ID de la mascota debe ser un número entero.',
            null,
            400
        );

        return;
    }
    if (!isset($_FILES['foto'])) {
        responderJson(
            false,
            'No se recibió ninguna imagen.',
            null,
            400
        );

        return;
    }
    try {
        $mascota = procesarFotoMascota(
            (int)$id,
            $_FILES['foto']
        );
        responderJson(
            true,
            'Foto de la mascota actualizada correctamente.',
            $mascota,
            200
        );

    } catch (Exception $e) {
        responderJson(
            false,
            $e->getMessage(),
            null,
            400
        );
    }
}