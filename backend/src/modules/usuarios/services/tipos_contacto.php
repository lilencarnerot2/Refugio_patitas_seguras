<?php

require_once __DIR__ . '/../repositories/tipos_contacto.php';

function listar_tipos_contacto(): array
{
    return obtener_tipos_contacto();
}