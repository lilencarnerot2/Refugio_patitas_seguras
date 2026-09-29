<?php

require_once __DIR__ . '/autenticacion.php';
require_once __DIR__ . '/../modules/usuarios/repositories/usuarios.php';
require_once __DIR__ . '/../utils/response.php';


function requerir_roles(array $roles_permitidos): bool
{
    /*
     * Primero verificar autenticacion.
     */
    if (!requerir_autenticacion()) {
        return false;
    }

    $id_usuario =
        (int) $_SESSION['usuario_id'];

    /*
     * Consultar el usuario actual en DB.
     *
     * Para no confiar únicamente en el rol guardado
     * en la sesión.
     */
    $usuario =
        obtener_usuario_por_id($id_usuario);

    if (!$usuario) {

        responderJson(
            false,
            'Usuario no encontrado.',
            null,
            401
        );

        return false;
    }

    /*
     * Usuario deshabilitado.
     */
    if (!$usuario['estado']) {

        responderJson(
            false,
            'Usuario deshabilitado.',
            null,
            403
        );

        return false;
    }

    $nombre_rol =
        $usuario['nombre_rol'] ?? '';

    /*
     * Comprobar rol.
     */
    if (
        !in_array(
            $nombre_rol,
            $roles_permitidos,
            true
        )
    ) {

        responderJson(
            false,
            'No tenés permisos para realizar esta acción.',
            null,
            403
        );

        return false;
    }

    /*
     * Actualizamos el rol de la sesión
     * con el valor actual de la base.
     */
    $_SESSION['usuario_rol'] =
        (int) $usuario['id_rol'];

    return true;
}