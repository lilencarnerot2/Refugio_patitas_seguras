<?php

require_once __DIR__ . '/../../../config/database.php';


function crear_domicilio_usuario(
    int $id_usuario,
    array $datos
): array {

    $conexion = conectarDB();

    try {

        $conexion->beginTransaction();

        /*
         * 1. Verificar tipo de vivienda
         */
        $sql_tipo = "
            SELECT id
            FROM tipos_vivienda
            WHERE id = :id
              AND estado = TRUE
        ";

        $consulta_tipo = $conexion->prepare($sql_tipo);

        $consulta_tipo->execute([
            ':id' => $datos['id_tipo_vivienda']
        ]);

        if (!$consulta_tipo->fetch(PDO::FETCH_ASSOC)) {
            throw new InvalidArgumentException(
                'El tipo de vivienda no es válido.'
            );
        }


        /*
         * 2. Verificar localidad
         */
        $sql_localidad = "
            SELECT id
            FROM localidades
            WHERE id = :id
              AND estado = TRUE
        ";

        $consulta_localidad =
            $conexion->prepare($sql_localidad);

        $consulta_localidad->execute([
            ':id' => $datos['id_localidad']
        ]);

        if (!$consulta_localidad->fetch(PDO::FETCH_ASSOC)) {
            throw new InvalidArgumentException(
                'La localidad no es válida.'
            );
        }


        /*
         * 3. Buscar barrio/zona existente
         */
        $sql_barrio = "
            SELECT id
            FROM barrios_zonas
            WHERE id_localidad = :id_localidad
              AND LOWER(TRIM(nombre_barrio)) =
                  LOWER(TRIM(:nombre_barrio))
              AND estado = TRUE
            LIMIT 1
        ";

        $consulta_barrio =
            $conexion->prepare($sql_barrio);

        $consulta_barrio->execute([
            ':id_localidad' => $datos['id_localidad'],
            ':nombre_barrio' => $datos['nombre_barrio']
        ]);

        $barrio =
            $consulta_barrio->fetch(PDO::FETCH_ASSOC);


        /*
         * 4. Si no existe, crear barrio/zona
         */
        if (!$barrio) {

            $sql_nuevo_barrio = "
                INSERT INTO barrios_zonas (
                    id_localidad,
                    nombre_barrio,
                    estado
                )
                VALUES (
                    :id_localidad,
                    :nombre_barrio,
                    TRUE
                )
                RETURNING id
            ";

            $consulta_nuevo_barrio =
                $conexion->prepare($sql_nuevo_barrio);

            $consulta_nuevo_barrio->execute([
                ':id_localidad' => $datos['id_localidad'],
                ':nombre_barrio' => $datos['nombre_barrio']
            ]);

            $barrio =
                $consulta_nuevo_barrio->fetch(
                    PDO::FETCH_ASSOC
                );
        }

        $id_barrio_zona = (int) $barrio['id'];


        /*
         * 5. Crear detalle del domicilio
         */
        $sql_detalle = "
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

        $consulta_detalle =
            $conexion->prepare($sql_detalle);

        $consulta_detalle->execute([
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

        $detalle = $consulta_detalle->fetch(
            PDO::FETCH_ASSOC
        );

        $id_domicilio_detalle =
            (int) $detalle['id'];


        /*
         * 6. Crear usuarios_domicilios
         */
        $sql_domicilio = "
            INSERT INTO usuarios_domicilios (
                id_domicilio_detalle,
                codigo_postal
            )
            VALUES (
                :id_domicilio_detalle,
                :codigo_postal
            )
            RETURNING
                id,
                id_domicilio_detalle,
                codigo_postal
        ";

        $consulta_domicilio =
            $conexion->prepare($sql_domicilio);

        $consulta_domicilio->execute([
            ':id_domicilio_detalle' =>
                $id_domicilio_detalle,

            ':codigo_postal' =>
                $datos['codigo_postal'] ?? null
        ]);

        $domicilio =
            $consulta_domicilio->fetch(
                PDO::FETCH_ASSOC
            );


        /*
         * 7. Asociar domicilio al usuario
         */
        $sql_usuario = "
            UPDATE usuarios
            SET id_domicilio = :id_domicilio
            WHERE id = :id_usuario
        ";

        $consulta_usuario =
            $conexion->prepare($sql_usuario);

        $consulta_usuario->execute([
            ':id_domicilio' => $domicilio['id'],
            ':id_usuario' => $id_usuario
        ]);

        if ($consulta_usuario->rowCount() === 0) {
            throw new RuntimeException(
                'No se pudo asociar el domicilio al usuario.'
            );
        }


        $conexion->commit();

        return [
            'id' => (int) $domicilio['id'],
            'id_domicilio_detalle' =>
                $id_domicilio_detalle,
            'id_barrio_zona' =>
                $id_barrio_zona,
            'codigo_postal' =>
                $domicilio['codigo_postal']
        ];

    } catch (Throwable $e) {

        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        throw $e;
    }
}

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

function actualizar_domicilio_usuario(
    int $id_usuario,
    array $datos
): array|false {

    $conexion = conectarDB();

    try {

        $conexion->beginTransaction();

        /*
         * 1. Buscar el domicilio actual del usuario
         */
        $sql_actual = "
            SELECT
                ud.id AS id_domicilio,
                ud.id_domicilio_detalle
            FROM usuarios u
            INNER JOIN usuarios_domicilios ud
                ON ud.id = u.id_domicilio
            WHERE u.id = :id_usuario
        ";

        $consulta_actual =
            $conexion->prepare($sql_actual);

        $consulta_actual->execute([
            ':id_usuario' => $id_usuario
        ]);

        $domicilio_actual =
            $consulta_actual->fetch(PDO::FETCH_ASSOC);

        if (!$domicilio_actual) {
            $conexion->rollBack();
            return false;
        }


        /*
         * 2. Verificar tipo de vivienda
         */
        $sql_tipo = "
            SELECT id
            FROM tipos_vivienda
            WHERE id = :id
              AND estado = TRUE
        ";

        $consulta_tipo =
            $conexion->prepare($sql_tipo);

        $consulta_tipo->execute([
            ':id' => $datos['id_tipo_vivienda']
        ]);

        if (!$consulta_tipo->fetch(PDO::FETCH_ASSOC)) {
            throw new InvalidArgumentException(
                'El tipo de vivienda no es válido.'
            );
        }


        /*
         * 3. Verificar localidad
         */
        $sql_localidad = "
            SELECT id
            FROM localidades
            WHERE id = :id
              AND estado = TRUE
        ";

        $consulta_localidad =
            $conexion->prepare($sql_localidad);

        $consulta_localidad->execute([
            ':id' => $datos['id_localidad']
        ]);

        if (!$consulta_localidad->fetch(PDO::FETCH_ASSOC)) {
            throw new InvalidArgumentException(
                'La localidad no es válida.'
            );
        }


        /*
         * 4. Buscar barrio/zona
         */
        $sql_barrio = "
            SELECT id
            FROM barrios_zonas
            WHERE id_localidad = :id_localidad
              AND LOWER(TRIM(nombre_barrio)) =
                  LOWER(TRIM(:nombre_barrio))
              AND estado = TRUE
            LIMIT 1
        ";

        $consulta_barrio =
            $conexion->prepare($sql_barrio);

        $consulta_barrio->execute([
            ':id_localidad' =>
                $datos['id_localidad'],

            ':nombre_barrio' =>
                $datos['nombre_barrio']
        ]);

        $barrio =
            $consulta_barrio->fetch(PDO::FETCH_ASSOC);


        /*
         * 5. Crear barrio si no existe
         */
        if (!$barrio) {

            $sql_nuevo_barrio = "
                INSERT INTO barrios_zonas (
                    id_localidad,
                    nombre_barrio,
                    estado
                )
                VALUES (
                    :id_localidad,
                    :nombre_barrio,
                    TRUE
                )
                RETURNING id
            ";

            $consulta_nuevo_barrio =
                $conexion->prepare($sql_nuevo_barrio);

            $consulta_nuevo_barrio->execute([
                ':id_localidad' =>
                    $datos['id_localidad'],

                ':nombre_barrio' =>
                    $datos['nombre_barrio']
            ]);

            $barrio =
                $consulta_nuevo_barrio->fetch(
                    PDO::FETCH_ASSOC
                );
        }

        $id_barrio_zona =
            (int) $barrio['id'];


        /*
         * 6. Actualizar domicilios_detalles
         */
        $sql_detalle = "
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
                indicadores_referencia =
                    :indicadores_referencia
            WHERE id = :id_domicilio_detalle
        ";

        $consulta_detalle =
            $conexion->prepare($sql_detalle);

        $consulta_detalle->execute([
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
                $domicilio_actual['id_domicilio_detalle']
        ]);


        /*
         * 7. Actualizar código postal
         */
        $sql_domicilio = "
            UPDATE usuarios_domicilios
            SET codigo_postal = :codigo_postal
            WHERE id = :id_domicilio
        ";

        $consulta_domicilio =
            $conexion->prepare($sql_domicilio);

        $consulta_domicilio->execute([
            ':codigo_postal' =>
                $datos['codigo_postal'] ?? null,

            ':id_domicilio' =>
                $domicilio_actual['id_domicilio']
        ]);


        $conexion->commit();

        /*
         * Devolver el domicilio completo actualizado
         */
        return obtener_domicilio_por_usuario(
            $id_usuario
        );

    } catch (Throwable $e) {

        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        throw $e;
    }
}