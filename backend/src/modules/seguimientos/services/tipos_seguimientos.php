<?php

require_once __DIR__ . '/../repositories/tipos_seguimientos.php';

function listar_tipos_seguimientos(): array {
    return obtener_tipos_seguimientos();
}