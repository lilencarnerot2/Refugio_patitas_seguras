<?php

require_once __DIR__ . '/../repositories/enfermedades_condiciones.php';

function listar_enfermedades_condiciones(): array {
    return obtener_enfermedades_condiciones();
}