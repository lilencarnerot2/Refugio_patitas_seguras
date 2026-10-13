<?php

require_once __DIR__ . '/../../../config/database.php';


function obtener_domicilio_por_usuario(
    int $id_usuario
): array|false {

    $conexion = conectarDB();

    $sql = "
        SELECT
            ud.id,
            ud.codigo_postal,

            dd.id AS id_domicilio_detalle,
            dd.id_tipo_vivienda,
            tv.nombre_tipo AS tipo_vivienda,

            dd.id_barrio_zona,
            bz.nombre_barrio AS barrio_zona,

            l.id AS id_localidad,
            l.nombre_localidad AS localidad,

            cm.id AS id_comuna_municipio,
            cm.nombre AS municipio,

            p.id AS id_provincia,
            p.nombre AS provincia,

            dd.calle,
            dd.altura,
            dd.piso,
            dd.departamento,
            dd.ruta_nombre,
            dd.kilometro,
            dd.entre_calle_1,
            dd.entre_calle_2,
            dd.indicadores_referencia

        FROM usuarios u

        INNER JOIN usuarios_domicilios ud
            ON ud.id = u.id_domicilio

        INNER JOIN domicilios_detalles dd
            ON dd.id = ud.id_domicilio_detalle

        INNER JOIN tipos_vivienda tv
            ON tv.id = dd.id_tipo_vivienda

        INNER JOIN barrios_zonas bz
            ON bz.id = dd.id_barrio_zona

        INNER JOIN localidades l
            ON l.id = bz.id_localidad

        INNER JOIN comunas_municipios cm
            ON cm.id = l.id_comuna_municipio

        INNER JOIN provincias p
            ON p.id = cm.id_provincia

        WHERE u.id = :id_usuario
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_usuario' => $id_usuario
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}