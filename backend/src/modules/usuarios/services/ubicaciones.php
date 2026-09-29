<?php

require_once __DIR__ . '/../repositories/ubicaciones.php';

function listar_provincias(): array
{
    return obtener_provincias();
}

function listar_comunas_por_provincia(int $id_provincia): array
{
    if ($id_provincia <= 0) {
        throw new InvalidArgumentException(
            'El id de provincia no es válido.'
        );
    }

    return obtener_comunas_por_provincia($id_provincia);
}

function listar_localidades_por_comuna(int $id_comuna_municipio): array
{
    if ($id_comuna_municipio <= 0) {
        throw new InvalidArgumentException(
            'El id de comuna o municipio no es válido.'
        );
    }

    return obtener_localidades_por_comuna($id_comuna_municipio);
}

function listar_barrios_por_localidad(int $id_localidad): array
{
    if ($id_localidad <= 0) {
        throw new InvalidArgumentException(
            'El id de localidad no es válido.'
        );
    }

    return obtener_barrios_por_localidad($id_localidad);
}