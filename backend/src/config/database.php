<?php

function conectarDB(){
    $host = getenv('DB_HOST');
    $port = getenv('DB_PORT');
    $dbname = getenv('DB_NAME');
    $user = getenv('DB_USER');
    $password = getenv('DB_PASSWORD');

    try {

        $conexion = new PDO(
            "pgsql:host=$host;port=$port;dbname=$dbname",
            $user,
            $password
        );

        $conexion->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );

        $conexion->setAttribute(
            PDO::ATTR_DEFAULT_FETCH_MODE,
            PDO::FETCH_ASSOC
        );

        /*
         * Contexto para los triggers de auditoría.
         *
         * Si hay una sesión autenticada, enviamos
         * el id del usuario a PostgreSQL.
         */
        $id_usuario = '';

        if (
            session_status() === PHP_SESSION_ACTIVE &&
            isset($_SESSION['usuario_id'])
        ) {
            $id_usuario =
                (string) ((int) $_SESSION['usuario_id']);
        }

        /*
         * IP desde la que llegó la petición.
         */
        $ip_origen =
            $_SERVER['REMOTE_ADDR'] ?? '';

        /*
         * Guardamos variables propias de la aplicación
         * dentro de esta conexión PostgreSQL.
         *
         * Los triggers pueden recuperarlas con:
         *
         * current_setting('app.id_usuario', true)
         * current_setting('app.ip_origen', true)
         */
        $consulta_contexto = $conexion->prepare("
            SELECT
                set_config(
                    'app.id_usuario',
                    :id_usuario,
                    false
                ),
                set_config(
                    'app.ip_origen',
                    :ip_origen,
                    false
                )
        ");

        $consulta_contexto->execute([
            ':id_usuario' => $id_usuario,
            ':ip_origen' => $ip_origen
        ]);

        return $conexion;

    } catch (PDOException $e) {

        die(
            "Error de conexión a la base de datos: " .
            $e->getMessage()
        );
    }
}