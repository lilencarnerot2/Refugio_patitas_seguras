<?php

require_once __DIR__ . '/../services/foto_mascotas.php';
require_once __DIR__ . '/../../../utils/response.php';
require_once __DIR__ . '/../../../middleware/autenticacion.php';


function manejarFotosMascota(): void {
    $metodo = $_SERVER['REQUEST_METHOD'] ?? '';

    switch ($metodo) {

        case 'GET':
            // Público: cualquiera puede ver las fotos
            manejarGetFotosMascota();
            break;

        case 'POST':
            // Solo admin y super admin pueden subir
            if (!requerir_roles([
                'Administrador',
                'Super Administrador'
            ])) {
                return;
            }

            manejarPostFotosMascota();
            break;

        case 'DELETE':
            // Solo admin y super admin pueden borrar
            if (!requerir_roles([
                'Administrador',
                'Super Administrador'
            ])) {
                return;
            }

            manejarDeleteFotoMascota();
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



// GET - LISTAR FOTOS ADICIONALES
function manejarGetFotosMascota(): void {
    $idMascota = $_GET['id_mascota'] ?? null;

    if ($idMascota === null || !ctype_digit($idMascota)) {
        responderJson(
            false,
            'El ID de la mascota debe ser un número entero.',
            null,
            400
        );
        return;
    }

    try {

        $fotos = listarFotosMascota(
            (int) $idMascota
        );

        responderJson(
            true,
            null,
            $fotos,
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



// POST - FOTO PRINCIPAL O ADICIONAL
function manejarPostFotosMascota(): void {
    $idMascota = $_GET['id_mascota'] ?? null;
    $tipo = $_GET['tipo'] ?? null;

    if ($idMascota === null || !ctype_digit($idMascota)) {
        responderJson(
            false,
            'El ID de la mascota debe ser un número entero.',
            null,
            400
        );
        return;
    }

    if ($tipo !== 'principal' && $tipo !== 'adicional') {
        responderJson(
            false,
            'El tipo de foto debe ser principal o adicional.',
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

        if ($tipo === 'principal') {

            $resultado = procesarFotoMascota(
                (int) $idMascota,
                $_FILES['foto']
            );

            responderJson(
                true,
                'Foto principal actualizada correctamente.',
                $resultado,
                200
            );

            return;
        }

        $resultado = procesarFotoAdicionalMascota(
            (int) $idMascota,
            $_FILES['foto']
        );

        responderJson(
            true,
            'Foto adicional agregada correctamente.',
            $resultado,
            201
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



// DELETE - FOTO ADICIONAL
function manejarDeleteFotoMascota(): void {
    $idMascota = $_GET['id_mascota'] ?? null;
    $idFoto = $_GET['id_foto'] ?? null;

    if (
        $idMascota === null ||
        !ctype_digit($idMascota) ||
        $idFoto === null ||
        !ctype_digit($idFoto)
    ) {
        responderJson(
            false,
            'Los IDs de la mascota y de la foto deben ser números enteros.',
            null,
            400
        );
        return;
    }

    try {

        $foto = borrarFotoMascota(
            (int) $idFoto,
            (int) $idMascota
        );

        responderJson(
            true,
            'Foto eliminada correctamente.',
            $foto,
            200
        );

    } catch (RuntimeException $e) {

        responderJson(
            false,
            $e->getMessage(),
            null,
            404
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