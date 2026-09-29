<?php

require_once __DIR__ . '/../../../config/database.php';


function obtener_contactos_por_usuario(
    int $id_usuario
): array {

    $conexion = conectarDB();

    $sql = "
        SELECT
            c.id,
            c.id_tipo_contacto,
            tc.descripcion AS tipo_contacto,
            c.valor,
            c.es_principal,
            c.observaciones,
            c.estado
        FROM contactos c
        INNER JOIN tipos_contacto tc
            ON tc.id = c.id_tipo_contacto
        WHERE c.id_usuario = :id_usuario
          AND c.estado = TRUE
        ORDER BY
            c.es_principal DESC,
            c.id
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_usuario' => $id_usuario
    ]);

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}


function obtener_contacto_por_id_y_usuario(
    int $id_contacto,
    int $id_usuario
): array|false {

    $conexion = conectarDB();

    $sql = "
        SELECT
            c.id,
            c.id_tipo_contacto,
            tc.descripcion AS tipo_contacto,
            c.valor,
            c.es_principal,
            c.observaciones,
            c.estado
        FROM contactos c
        INNER JOIN tipos_contacto tc
            ON tc.id = c.id_tipo_contacto
        WHERE c.id = :id_contacto
          AND c.id_usuario = :id_usuario
          AND c.estado = TRUE
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_contacto' => $id_contacto,
        ':id_usuario' => $id_usuario
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}


function crear_contacto_usuario(
    int $id_usuario,
    array $datos
): array {

    $conexion = conectarDB();

    try {

        $conexion->beginTransaction();

        /*
         * Verificar tipo de contacto.
         */
        $sql_tipo = "
            SELECT id
            FROM tipos_contacto
            WHERE id = :id
              AND estado = TRUE
        ";

        $consulta_tipo =
            $conexion->prepare($sql_tipo);

        $consulta_tipo->execute([
            ':id' => $datos['id_tipo_contacto']
        ]);

        if (!$consulta_tipo->fetch(PDO::FETCH_ASSOC)) {
            throw new InvalidArgumentException(
                'El tipo de contacto no es válido.'
            );
        }

        /*
         * Si el nuevo contacto será principal,
         * los demás dejan de ser principales.
         */
        if ($datos['es_principal'] === true) {

        $sql_principal = "
            UPDATE contactos
            SET es_principal = FALSE
            WHERE id_usuario = :id_usuario
            AND id_tipo_contacto = :id_tipo_contacto
            AND estado = TRUE
            ";

        $consulta_principal =
        $conexion->prepare($sql_principal);

        $consulta_principal->execute([
            ':id_usuario' => $id_usuario,
            ':id_tipo_contacto' => $datos['id_tipo_contacto']
        ]);
        }

        $sql = "
            INSERT INTO contactos (
                id_tipo_contacto,
                id_usuario,
                valor,
                es_principal,
                observaciones,
                estado
            )
            VALUES (
                :id_tipo_contacto,
                :id_usuario,
                :valor,
                :es_principal,
                :observaciones,
                TRUE
            )
            RETURNING
                id,
                id_tipo_contacto,
                valor,
                es_principal,
                observaciones,
                estado
        ";

        $consulta = $conexion->prepare($sql);

        $consulta->execute([
            ':id_tipo_contacto' =>
                $datos['id_tipo_contacto'],

            ':id_usuario' =>
                $id_usuario,

            ':valor' =>
                $datos['valor'],

            ':es_principal' =>
                $datos['es_principal'],

            ':observaciones' =>
                $datos['observaciones'] ?? null
        ]);

        $contacto =
            $consulta->fetch(PDO::FETCH_ASSOC);

        $conexion->commit();

        return $contacto;

    } catch (Throwable $e) {

        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        throw $e;
    }
}


function actualizar_contacto_usuario(
    int $id_contacto,
    int $id_usuario,
    array $datos
): array|false {

    $conexion = conectarDB();

    try {

        $conexion->beginTransaction();

        /*
         * Confirmar que el contacto pertenece
         * al usuario autenticado.
         */
        $sql_existe = "
            SELECT id
            FROM contactos
            WHERE id = :id_contacto
              AND id_usuario = :id_usuario
              AND estado = TRUE
        ";

        $consulta_existe =
            $conexion->prepare($sql_existe);

        $consulta_existe->execute([
            ':id_contacto' => $id_contacto,
            ':id_usuario' => $id_usuario
        ]);

        if (!$consulta_existe->fetch(PDO::FETCH_ASSOC)) {
            $conexion->rollBack();
            return false;
        }

        /*
         * Verificar tipo de contacto.
         */
        $sql_tipo = "
            SELECT id
            FROM tipos_contacto
            WHERE id = :id
              AND estado = TRUE
        ";

        $consulta_tipo =
            $conexion->prepare($sql_tipo);

        $consulta_tipo->execute([
            ':id' => $datos['id_tipo_contacto']
        ]);

        if (!$consulta_tipo->fetch(PDO::FETCH_ASSOC)) {
            throw new InvalidArgumentException(
                'El tipo de contacto no es válido.'
            );
        }

        /*
         * Si pasa a ser principal,
         * desmarcar los demás.
         */
        if ($datos['es_principal'] === true) {

            $sql_principal = "
                UPDATE contactos
                SET es_principal = FALSE
                WHERE id_usuario = :id_usuario
                AND id_tipo_contacto = :id_tipo_contacto
                AND id <> :id_contacto
                AND estado = TRUE
            ";

            $consulta_principal =
            $conexion->prepare($sql_principal);

            $consulta_principal->execute([
                ':id_usuario' => $id_usuario,
                ':id_tipo_contacto' => $datos['id_tipo_contacto'],
                ':id_contacto' => $id_contacto
            ]);
        }

        $sql = "
            UPDATE contactos
            SET
                id_tipo_contacto = :id_tipo_contacto,
                valor = :valor,
                es_principal = :es_principal,
                observaciones = :observaciones
            WHERE id = :id_contacto
              AND id_usuario = :id_usuario
            RETURNING
                id,
                id_tipo_contacto,
                valor,
                es_principal,
                observaciones,
                estado
        ";

        $consulta = $conexion->prepare($sql);

        $consulta->execute([
            ':id_tipo_contacto' =>
                $datos['id_tipo_contacto'],

            ':valor' =>
                $datos['valor'],

            ':es_principal' =>
                $datos['es_principal'],

            ':observaciones' =>
                $datos['observaciones'] ?? null,

            ':id_contacto' =>
                $id_contacto,

            ':id_usuario' =>
                $id_usuario
        ]);

        $contacto =
            $consulta->fetch(PDO::FETCH_ASSOC);

        $conexion->commit();

        return $contacto;

    } catch (Throwable $e) {

        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        throw $e;
    }
}


function desactivar_contacto_usuario(
    int $id_contacto,
    int $id_usuario
): bool {

    $conexion = conectarDB();

    $sql = "
        UPDATE contactos
        SET
            estado = FALSE,
            es_principal = FALSE
        WHERE id = :id_contacto
          AND id_usuario = :id_usuario
          AND estado = TRUE
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_contacto' => $id_contacto,
        ':id_usuario' => $id_usuario
    ]);

    return $consulta->rowCount() > 0;
}