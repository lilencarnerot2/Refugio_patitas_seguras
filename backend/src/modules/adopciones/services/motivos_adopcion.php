<?php

require_once __DIR__ . '/../repositories/motivos_adopcion.php';

function listar_motivos_adopcion(): array {
    return obtener_motivos_adopcion();
}