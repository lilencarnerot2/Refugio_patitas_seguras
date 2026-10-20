<?php

require_once __DIR__ . '/../src/config/database.php';

const GEOREF_BASE_URL = 'https://apis.datos.gob.ar/georef/api/v2.0';


function obtener_json_georef(
    string $recurso,
    array $parametros = []
): array {
    $url = GEOREF_BASE_URL . '/' . $recurso;

    if (!empty($parametros)) {
        $url .= '?' . http_build_query($parametros);
    }

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);

    $respuesta = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error_curl = curl_error($ch);

    curl_close($ch);

    if ($error_curl) {
        throw new RuntimeException(
            'Error de conexión con Georef: ' . $error_curl
        );
    }

    if ($http_code < 200 || $http_code >= 300) {
        throw new RuntimeException(
            'Georef respondió HTTP ' .
            $http_code .
            ' para ' .
            $url
        );
    }

    $datos = json_decode($respuesta, true);

    if (!is_array($datos)) {
        throw new RuntimeException(
            'Georef devolvió un JSON inválido.'
        );
    }

    return $datos;
}


function sincronizar_provincias(PDO $conexion): void
{
    echo "Sincronizando provincias...\n";

    $datos = obtener_json_georef(
        'provincias',
        [
            'max' => 100
        ]
    );

    $provincias = $datos['provincias'] ?? [];

    $sql = "
        INSERT INTO provincias (
            codigo_georef,
            nombre
        )
        VALUES (
            :codigo_georef,
            :nombre
        )
        ON CONFLICT (codigo_georef)
        DO UPDATE SET
            nombre = EXCLUDED.nombre
    ";

    $consulta = $conexion->prepare($sql);

    $cantidad = 0;

    foreach ($provincias as $provincia) {

        if (
            !isset(
                $provincia['id'],
                $provincia['nombre']
            )
        ) {
            continue;
        }

        $consulta->execute([
            ':codigo_georef' => $provincia['id'],
            ':nombre' => $provincia['nombre']
        ]);

        $cantidad++;
    }

    echo "Provincias sincronizadas: {$cantidad}\n";
}


function obtener_mapa_provincias(PDO $conexion): array
{
    $sql = "
        SELECT
            id,
            codigo_georef
        FROM provincias
        WHERE codigo_georef IS NOT NULL
    ";

    $consulta = $conexion->prepare($sql);
    $consulta->execute();

    $provincias = $consulta->fetchAll(PDO::FETCH_ASSOC);

    $mapa = [];

    foreach ($provincias as $provincia) {
        $mapa[$provincia['codigo_georef']] =
            (int) $provincia['id'];
    }

    return $mapa;
}


function obtener_todos_los_municipios(): array
{
    $todos = [];
    $inicio = 0;
    $max = 500;

    while (true) {

        $datos = obtener_json_georef(
            'municipios',
            [
                'inicio' => $inicio,
                'max' => $max
            ]
        );

        $municipios = $datos['municipios'] ?? [];

        if (empty($municipios)) {
            break;
        }

        $todos = array_merge(
            $todos,
            $municipios
        );

        $cantidad_recibida = count($municipios);
        $inicio += $cantidad_recibida;

        $total = (int) ($datos['total'] ?? 0);

        echo
            "Municipios descargados: " .
            count($todos) .
            " / " .
            $total .
            "\n";

        if (
            $cantidad_recibida === 0 ||
            $inicio >= $total
        ) {
            break;
        }
    }

    return $todos;
}


function sincronizar_municipios(PDO $conexion): void
{
    echo "Sincronizando municipios...\n";

    $municipios = obtener_todos_los_municipios();

    $mapa_provincias =
        obtener_mapa_provincias($conexion);

    $sql = "
        INSERT INTO comunas_municipios (
            id_provincia,
            codigo_georef,
            nombre
        )
        VALUES (
            :id_provincia,
            :codigo_georef,
            :nombre
        )
        ON CONFLICT (codigo_georef)
        DO UPDATE SET
            id_provincia = EXCLUDED.id_provincia,
            nombre = EXCLUDED.nombre
    ";

    $consulta = $conexion->prepare($sql);

    $cantidad = 0;
    $omitidos = 0;

    foreach ($municipios as $municipio) {

        if (
            !isset(
                $municipio['id'],
                $municipio['nombre'],
                $municipio['provincia']['id']
            )
        ) {
            $omitidos++;
            continue;
        }

        $codigo_provincia =
            $municipio['provincia']['id'];

        if (
            !isset(
                $mapa_provincias[$codigo_provincia]
            )
        ) {
            $omitidos++;
            continue;
        }

        $consulta->execute([
            ':id_provincia' =>
                $mapa_provincias[$codigo_provincia],

            ':codigo_georef' =>
                $municipio['id'],

            ':nombre' =>
                $municipio['nombre']
        ]);

        $cantidad++;

        if ($cantidad % 100 === 0) {
            echo
                "Municipios guardados: " .
                $cantidad .
                "\n";
        }
    }

    echo
        "Municipios sincronizados: " .
        $cantidad .
        "\n";

    echo
        "Municipios omitidos: " .
        $omitidos .
        "\n";
}


function obtener_mapa_municipios(PDO $conexion): array
{
    $sql = "
        SELECT
            id,
            codigo_georef
        FROM comunas_municipios
        WHERE codigo_georef IS NOT NULL
    ";

    $consulta = $conexion->prepare($sql);
    $consulta->execute();

    $municipios = $consulta->fetchAll(PDO::FETCH_ASSOC);

    $mapa = [];

    foreach ($municipios as $municipio) {
        $mapa[$municipio['codigo_georef']] =
            (int) $municipio['id'];
    }

    return $mapa;
}


function obtener_todas_las_localidades(): array
{
    $todas = [];
    $inicio = 0;
    $max = 500;

    while (true) {

        $datos = obtener_json_georef(
            'localidades',
            [
                'inicio' => $inicio,
                'max' => $max
            ]
        );

        $localidades = $datos['localidades'] ?? [];

        if (empty($localidades)) {
            break;
        }

        $todas = array_merge(
            $todas,
            $localidades
        );

        $cantidad_recibida = count($localidades);
        $inicio += $cantidad_recibida;

        $total = (int) ($datos['total'] ?? 0);

        echo
            "Localidades descargadas: " .
            count($todas) .
            " / " .
            $total .
            "\n";

        if (
            $cantidad_recibida === 0 ||
            $inicio >= $total
        ) {
            break;
        }
    }

    return $todas;
}


function sincronizar_localidades(PDO $conexion): void
{
    echo "Sincronizando localidades...\n";

    $localidades = obtener_todas_las_localidades();

    $mapa_municipios =
        obtener_mapa_municipios($conexion);

    $sql = "
        INSERT INTO localidades (
            id_comuna_municipio,
            codigo_georef,
            nombre_localidad,
            estado
        )
        VALUES (
            :id_comuna_municipio,
            :codigo_georef,
            :nombre_localidad,
            TRUE
        )
        ON CONFLICT (codigo_georef)
        DO UPDATE SET
            id_comuna_municipio =
                EXCLUDED.id_comuna_municipio,
            nombre_localidad =
                EXCLUDED.nombre_localidad,
            estado = TRUE
    ";

    $consulta = $conexion->prepare($sql);

    $cantidad = 0;
    $omitidas = 0;
    $duplicadas = 0;

    /*
     * Guarda combinaciones municipio + nombre
     * que ya procesamos desde Georef.
     */
    $localidades_procesadas = [];

    foreach ($localidades as $localidad) {

        if (
            !isset(
                $localidad['id'],
                $localidad['nombre'],
                $localidad['gobierno_local']['id']
            )
        ) {
            $omitidas++;
            continue;
        }

        $codigo_municipio =
            $localidad['gobierno_local']['id'];

        if (
            !isset(
                $mapa_municipios[$codigo_municipio]
            )
        ) {
            $omitidas++;
            continue;
        }

        $id_municipio =
            $mapa_municipios[$codigo_municipio];

        $nombre_localidad =
            trim($localidad['nombre']);

        /*
         * Nuestra DB considera única una localidad
         * por municipio + nombre.
         */
        $clave_localidad =
            $id_municipio .
            '|' .
            $nombre_localidad;

        /*
         * Si Georef devuelve otra localidad con
         * exactamente el mismo municipio y nombre,
         * la omitimos.
         */
        if (
            isset(
                $localidades_procesadas[
                    $clave_localidad
                ]
            )
        ) {
            $duplicadas++;
            continue;
        }

        $localidades_procesadas[
            $clave_localidad
        ] = true;

        $consulta->execute([
            ':id_comuna_municipio' =>
                $id_municipio,

            ':codigo_georef' =>
                $localidad['id'],

            ':nombre_localidad' =>
                $nombre_localidad
        ]);

        $cantidad++;

        if ($cantidad % 100 === 0) {
            echo
                "Localidades guardadas: " .
                $cantidad .
                "\n";
        }
    }

    echo
        "Localidades sincronizadas: " .
        $cantidad .
        "\n";

    echo
        "Localidades omitidas por falta de relación: " .
        $omitidas .
        "\n";

    echo
        "Localidades duplicadas omitidas: " .
        $duplicadas .
        "\n";
}


try {

    echo "=== SINCRONIZACIÓN GEOREF ===\n";

    $conexion = conectarDB();

    $conexion->beginTransaction();

    sincronizar_provincias($conexion);

    sincronizar_municipios($conexion);

    sincronizar_localidades($conexion);

    $conexion->commit();

    echo "=== SINCRONIZACIÓN FINALIZADA ===\n";

} catch (Throwable $e) {

    if (
        isset($conexion) &&
        $conexion instanceof PDO &&
        $conexion->inTransaction()
    ) {
        $conexion->rollBack();
    }

    echo
        "ERROR: " .
        $e->getMessage() .
        "\n";

    exit(1);
}