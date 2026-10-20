<?php

require_once __DIR__ . '/../repositories/configuracion.php';


function consultarConfiguracion(): array {
    $configuracion = obtenerConfiguracion();
    if (!$configuracion) {
        throw new RuntimeException(
            'No se encontró la configuración del sistema.'
        );
    }
    return $configuracion;
}


function modificarConfiguracion(array $datos): array {
    $actual = obtenerConfiguracion();
    if (!$actual) {
        throw new RuntimeException(
            'No se encontró la configuración del sistema.'
        );
    }
    $configuracion = [
        'nombre_refugio' => array_key_exists('nombre_refugio', $datos)
            ? trim($datos['nombre_refugio'])
            : $actual['nombre_refugio'],
        'direccion' => array_key_exists('direccion', $datos)
            ? trim($datos['direccion'])
            : $actual['direccion'],
        'telefono' => array_key_exists('telefono', $datos)
            ? trim($datos['telefono'])
            : $actual['telefono'],
        'email_contacto' => array_key_exists('email_contacto', $datos)
            ? trim($datos['email_contacto'])
            : $actual['email_contacto'],
        'horario_atencion' => array_key_exists('horario_atencion', $datos)
            ? trim($datos['horario_atencion'])
            : $actual['horario_atencion'],
        'alias_mercado_pago' => array_key_exists('alias_mercado_pago', $datos)
            ? trim($datos['alias_mercado_pago'])
            : $actual['alias_mercado_pago'],
        'cbu' => array_key_exists('cbu', $datos)
            ? trim($datos['cbu'])
            : $actual['cbu'],
        'titular_cuenta' => array_key_exists('titular_cuenta', $datos)
            ? trim($datos['titular_cuenta'])
            : $actual['titular_cuenta'],
        'banco' => array_key_exists('banco', $datos)
            ? trim($datos['banco'])
            : $actual['banco'],
        'whatsapp' => array_key_exists('whatsapp', $datos)
            ? trim($datos['whatsapp'])
            : $actual['whatsapp'],
        'instagram' => array_key_exists('instagram', $datos)
            ? trim($datos['instagram'])
            : $actual['instagram']
    ];
    if (
        array_key_exists('email_contacto', $datos) &&
        $configuracion['email_contacto'] !== '' &&
        !filter_var(
            $configuracion['email_contacto'],
            FILTER_VALIDATE_EMAIL
        )
    ) {
        throw new InvalidArgumentException(
            'El email de contacto no es válido.'
        );
    }
    if (
        array_key_exists('cbu', $datos) &&
        (
            !ctype_digit($configuracion['cbu']) ||
            strlen($configuracion['cbu']) !== 22
        )
    ) {
        throw new InvalidArgumentException(
            'El CBU debe contener exactamente 22 números.'
        );
    }
    $resultado = actualizarConfiguracion($configuracion);
    if (!$resultado) {
        throw new RuntimeException(
            'No se pudo actualizar la configuración.'
        );
    }
    return $resultado;
}