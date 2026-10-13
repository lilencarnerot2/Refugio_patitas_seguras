<?php

function crear_detalle_domicilio(
    PDO $conexion,
    int $id_barrio_zona,
    array $datos
): int {

    $sql = "
        INSERT INTO domicilios_detalles (
            id_tipo_vivienda,
            id_barrio_zona,
            calle,
            altura,
            piso,
            departamento,
            ruta_nombre,
            kilometro,
            entre_calle_1,
            entre_calle_2,
            indicadores_referencia
        )
        VALUES (
            :id_tipo_vivienda,
            :id_barrio_zona,
            :calle,
            :altura,
            :piso,
            :departamento,
            :ruta_nombre,
            :kilometro,
            :entre_calle_1,
            :entre_calle_2,
            :indicadores_referencia
        )
        RETURNING id
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_tipo_vivienda' =>
            $datos['id_tipo_vivienda'],

        ':id_barrio_zona' =>
            $id_barrio_zona,

        ':calle' =>
            $datos['calle'] ?? null,

        ':altura' =>
            $datos['altura'] ?? null,

        ':piso' =>
            $datos['piso'] ?? null,

        ':departamento' =>
            $datos['departamento'] ?? null,

        ':ruta_nombre' =>
            $datos['ruta_nombre'] ?? null,

        ':kilometro' =>
            $datos['kilometro'] ?? null,

        ':entre_calle_1' =>
            $datos['entre_calle_1'] ?? null,

        ':entre_calle_2' =>
            $datos['entre_calle_2'] ?? null,

        ':indicadores_referencia' =>
            $datos['indicadores_referencia'] ?? null
    ]);

    $detalle = $consulta->fetch(PDO::FETCH_ASSOC);

    if (!$detalle) {
        throw new RuntimeException(
            'No se pudo crear el detalle del domicilio.'
        );
    }

    return (int) $detalle['id'];
}


function actualizar_detalle_domicilio(
    PDO $conexion,
    int $id_domicilio_detalle,
    int $id_barrio_zona,
    array $datos
): void {

    $sql = "
        UPDATE domicilios_detalles
        SET
            id_tipo_vivienda = :id_tipo_vivienda,
            id_barrio_zona = :id_barrio_zona,
            calle = :calle,
            altura = :altura,
            piso = :piso,
            departamento = :departamento,
            ruta_nombre = :ruta_nombre,
            kilometro = :kilometro,
            entre_calle_1 = :entre_calle_1,
            entre_calle_2 = :entre_calle_2,
            indicadores_referencia = :indicadores_referencia
        WHERE id = :id_domicilio_detalle
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_tipo_vivienda' =>
            $datos['id_tipo_vivienda'],

        ':id_barrio_zona' =>
            $id_barrio_zona,

        ':calle' =>
            $datos['calle'] ?? null,

        ':altura' =>
            $datos['altura'] ?? null,

        ':piso' =>
            $datos['piso'] ?? null,

        ':departamento' =>
            $datos['departamento'] ?? null,

        ':ruta_nombre' =>
            $datos['ruta_nombre'] ?? null,

        ':kilometro' =>
            $datos['kilometro'] ?? null,

        ':entre_calle_1' =>
            $datos['entre_calle_1'] ?? null,

        ':entre_calle_2' =>
            $datos['entre_calle_2'] ?? null,

        ':indicadores_referencia' =>
            $datos['indicadores_referencia'] ?? null,

        ':id_domicilio_detalle' =>
            $id_domicilio_detalle
    ]);
}