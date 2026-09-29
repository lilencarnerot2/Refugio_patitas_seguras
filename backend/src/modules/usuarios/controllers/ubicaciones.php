<?php

require_once __DIR__ . '/../services/ubicaciones.php';
require_once __DIR__ . '/../../../utils/response.php';

function manejar_provincias()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        return responderJson(false, 'Método no permitido', null, 405);
    }

    try {
        return responderJson(
            true,
            null,
            listar_provincias(),
            200
        );

    } catch (Throwable $e) {

        error_log('ERROR PROVINCIAS: ' . $e->getMessage());

        return responderJson(false, 'Error interno', null, 500);
    }
}

function manejar_comunas_municipios()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        return responderJson(false, 'Método no permitido', null, 405);
    }

    try {
        $id_provincia = (int) ($_GET['id_provincia'] ?? 0);

        return responderJson(
            true,
            null,
            listar_comunas_por_provincia($id_provincia),
            200
        );

    } catch (InvalidArgumentException $e) {

        return responderJson(
            false,
            $e->getMessage(),
            null,
            400
        );

    } catch (Throwable $e) {

        error_log('ERROR COMUNAS MUNICIPIOS: ' . $e->getMessage());

        return responderJson(false, 'Error interno', null, 500);
    }
}

function manejar_localidades()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        return responderJson(false, 'Método no permitido', null, 405);
    }

    try {
        $id_comuna_municipio = (int) ($_GET['id_comuna_municipio'] ?? 0);

        return responderJson(
            true,
            null,
            listar_localidades_por_comuna($id_comuna_municipio),
            200
        );

    } catch (InvalidArgumentException $e) {

        return responderJson(
            false,
            $e->getMessage(),
            null,
            400
        );

    } catch (Throwable $e) {

        error_log('ERROR LOCALIDADES: ' . $e->getMessage());

        return responderJson(false, 'Error interno', null, 500);
    }
}

function manejar_barrios_zonas()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        return responderJson(false, 'Método no permitido', null, 405);
    }

    try {
        $id_localidad = (int) ($_GET['id_localidad'] ?? 0);

        return responderJson(
            true,
            null,
            listar_barrios_por_localidad($id_localidad),
            200
        );

    } catch (InvalidArgumentException $e) {

        return responderJson(
            false,
            $e->getMessage(),
            null,
            400
        );

    } catch (Throwable $e) {

        error_log('ERROR BARRIOS ZONAS: ' . $e->getMessage());

        return responderJson(false, 'Error interno', null, 500);
    }
}