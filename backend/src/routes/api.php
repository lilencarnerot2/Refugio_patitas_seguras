<?php

require_once __DIR__ . '/../modules/mascotas/controllers/mascotas.php';
require_once __DIR__ . '/../modules/mascotas/controllers/especies.php';
require_once __DIR__ . '/../modules/mascotas/controllers/razas.php';
require_once __DIR__ . '/../modules/auth/controllers/login.php';
require_once __DIR__ . '/../modules/auth/controllers/registro.php';
require_once __DIR__ . '/../utils/response.php';
require_once __DIR__ . '/../middleware/autorizacion.php';
require_once __DIR__ . '/../modules/voluntariado/controllers/voluntariado.php';
require_once __DIR__ . '/../modules/adopciones/controllers/motivos_adopcion.php';
require_once __DIR__ . '/../modules/adopciones/controllers/enfermedades_condiciones.php';
require_once __DIR__ . '/../modules/seguimientos/controllers/tipos_seguimientos.php';


function manejarRutas() {
    $recurso = $_GET['recurso'] ?? '';

    switch ($recurso) {

        case 'roles':
            if (!requerir_roles([
                'Super Administrador'
            ])) {
            break;
            }
            require_once __DIR__ . '/../modules/usuarios/controllers/roles.php';
            manejar_roles();
            break;

        case 'usuarios':
            if (!requerir_roles([
                'Administrador',
                'Super Administrador'
            ])) {
            break;
            }
            require_once __DIR__ . '/../modules/usuarios/controllers/usuarios.php';
            manejar_usuarios();
            break;

        case 'usuarios_rol':
            if (!requerir_roles([
                'Super Administrador'
            ])) {
            break;
            }
            require_once __DIR__ . '/../modules/usuarios/controllers/usuarios.php';
            manejar_rol_usuario();
            break;

        case 'tipos_vivienda':
            require_once __DIR__ . '/../modules/usuarios/controllers/tipos_vivienda.php';
                manejar_tipos_vivienda();
            break;

        case 'provincias':
            require_once __DIR__ . '/../modules/usuarios/controllers/ubicaciones.php';
                manejar_provincias();
            break;

        case 'comunas_municipios':
            require_once __DIR__ . '/../modules/usuarios/controllers/ubicaciones.php';
                manejar_comunas_municipios();
            break;

        case 'localidades':
            require_once __DIR__ . '/../modules/usuarios/controllers/ubicaciones.php';
                manejar_localidades();
            break;

        case 'barrios_zonas':
            require_once __DIR__ . '/../modules/usuarios/controllers/ubicaciones.php';
                manejar_barrios_zonas();
            break;

        case 'domicilios':
            require_once __DIR__ . '/../modules/usuarios/controllers/domicilios.php';
                manejar_domicilios();
            break;

        case 'tipos_contacto':
            require_once __DIR__ . '/../modules/usuarios/controllers/tipos_contacto.php';
                manejar_tipos_contacto();
            break;

        case 'contactos':
            require_once __DIR__ . '/../modules/usuarios/controllers/contactos.php';
            manejar_contactos();
            break;

        case 'mascotas':
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                if (!requerir_roles([
                    'Administrador',
                    'Super Administrador'
                ])) {
                break;
                }
            }
            manejarMascotas();
            break;

        case 'login':
            manejarLogin();
            break;

        case 'registro':
            manejarRegistro();
            break;
          
        case 'password-reset-solicitar':
            require_once __DIR__ . '/../modules/auth/controllers/recuperar.php';
            manejar_solicitar_reset();
            break;

        case 'password-reset-confirmar':
            require_once __DIR__ . '/../modules/auth/controllers/reset.php';
            manejar_confirmar_reset();
            break;    


        case 'especies':
            manejarEspecies();
            break;

        case 'razas':
            manejarRazas();
            break;

        case 'fichas_medicas':
            if (!requerir_roles([
                'Administrador',
                'Super Administrador'
            ])) {
            break;
            }
            require_once __DIR__ . '/../modules/salud/controllers/fichas_medicas.php';
            manejar_fichas_medicas();
            break;


        case 'controles_medicos':
            if (!requerir_roles([
                'Administrador',
                'Super Administrador'
            ])) {
            break;
            }
            require_once __DIR__ . '/../modules/salud/controllers/controles_medicos.php';
            manejar_controles_medicos();
            break;


        case 'vacunas':
            if (!requerir_roles([
                'Administrador',
                'Super Administrador'
            ])) {
            break;
            }
            require_once __DIR__ . '/../modules/salud/controllers/vacunas.php';
            manejar_vacunas();
            break;

        case 'tipos_vacunas':
            if (!requerir_roles([
                'Administrador',
                'Super Administrador'
            ])) {
            break;
            }
            require_once __DIR__ . '/../modules/salud/controllers/tipos_vacunas.php';
            manejar_tipos_vacunas();
            break;

        case 'castraciones':
            if (!requerir_roles([
                'Administrador',
                'Super Administrador'
            ])) {
            break;
            }
            require_once __DIR__ . '/../modules/salud/controllers/castraciones.php';
            manejar_castraciones();
            break;


        case 'desparasitaciones':
            if (!requerir_roles([
                'Administrador',
                'Super Administrador'
            ])) {
            break;
            }
            require_once __DIR__ . '/../modules/salud/controllers/desparasitaciones.php';
            manejar_desparasitaciones();
            break;

        case 'tipos_productos':
            if (!requerir_roles([
                'Administrador',
                'Super Administrador'
            ])) {
            break;
            }
            require_once __DIR__ . '/../modules/salud/controllers/tipos_productos.php';
            manejar_tipos_productos();
            break;

        case 'solicitudes':
            if (!requerir_autenticacion()) {
                break;
            }
            require_once __DIR__ . '/../modules/solicitudes/controllers/solicitudes.php';
            manejar_solicitudes();
            break;


        case 'tipos_solicitudes':
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (!requerir_autenticacion()) {
                    break;
                }
            } else {
                if (!requerir_roles([
                    'Administrador',
                    'Super Administrador'
                ])) {
                    break;
                }
            }
            require_once __DIR__ . '/../modules/solicitudes/controllers/tipos_solicitudes.php';
            manejar_tipos_solicitudes();
            break;

        case 'estados_solicitudes':
            if (!requerir_roles([
                'Administrador',
                'Super Administrador'
            ])) {
            break;
            }
            require_once __DIR__ . '/../modules/solicitudes/controllers/estados_solicitudes.php';
            manejar_estados_solicitudes();
            break;

        case 'seguimientos_adopcion':
            if (!requerir_roles([
                'Administrador',
                'Super Administrador'
            ])) {
            break;
            }
            require_once __DIR__ . '/../modules/seguimientos/controllers/seguimientos_adopcion.php';
            manejar_seguimientos_adopcion();
            break;
        
        case 'seguimientos_transito':
            if (!requerir_roles([
                'Administrador',
                'Super Administrador'
            ])) {
            break;
            }
            require_once __DIR__ . '/../modules/seguimientos/controllers/seguimientos_transito.php';
            manejar_seguimientos_transito();
            break;    
            
        case 'formularios_transito':
            if (!requerir_autenticacion()) {
            break;
            }
            require_once __DIR__ . '/../modules/transito/controllers/formularios_transito.php';
            manejar_formularios_transito();
            break;

        case 'compromisos_adopcion':
            require_once __DIR__ . '/../modules/adopciones/controllers/formularios_adopcion.php';
            manejar_compromisos_adopcion();
            break;

        case 'condiciones_especiales':
            require_once __DIR__ . '/../modules/adopciones/controllers/formularios_adopcion.php';
            manejar_condiciones_especiales();
            break;

        case 'tipos_mascotas':
            require_once __DIR__ . '/../modules/adopciones/controllers/formularios_adopcion.php';
            manejar_tipos_mascotas();
            break;

        case 'condiciones_vivienda':
            require_once __DIR__ . '/../modules/adopciones/controllers/formularios_adopcion.php';
            manejar_condiciones_vivienda();
            break;

        case 'destinos_mascotas':
            require_once __DIR__ . '/../modules/adopciones/controllers/formularios_adopcion.php';
            manejar_destinos_mascotas();
            break;

        case 'formularios_adopcion':
            require_once __DIR__ . '/../modules/adopciones/controllers/formularios_adopcion.php';
            manejar_formularios_adopcion();
            break;

        case 'enfermedades_condiciones':
            manejar_enfermedades_condiciones();
            break;

        case 'tipos_movimientos_mascotas':
            if (!requerir_roles([
                'Administrador',
                'Super Administrador'
            ])) {
            break;
            }
            require_once __DIR__ . '/../modules/movimientos_mascotas/controllers/tipos_movimientos_mascotas.php';
            manejar_tipos_movimientos_mascotas();
            break;


        case 'movimientos_mascotas':
            if (!requerir_roles([
                'Administrador',
                'Super Administrador'
            ])) {
            break;
            }
            require_once __DIR__ . '/../modules/movimientos_mascotas/controllers/movimientos_mascotas.php';
            manejar_movimientos_mascotas();
            break;

        case 'motivos_adopcion':
            manejar_motivos_adopcion();
            break;

        case 'tipos_seguimientos':
            manejar_tipos_seguimientos();
            break;

        case 'tipos_aportes':
            require_once __DIR__ . '/../modules/donaciones/controllers/movimientos.php';
            manejar_tipos_aportes();
            break;

        case 'tipos_movimientos_aportes':
            require_once __DIR__ . '/../modules/donaciones/controllers/movimientos.php';
            manejar_tipos_movimientos_aportes();
            break;

        case 'frecuencia_aportes':
            require_once __DIR__ . '/../modules/donaciones/controllers/movimientos.php';
            manejar_frecuencia_aportes();
            break;

        case 'metodos_pago':
            require_once __DIR__ . '/../modules/donaciones/controllers/movimientos.php';
            manejar_metodos_pago();
            break;

        case 'movimientos':
            require_once __DIR__ . '/../modules/donaciones/controllers/movimientos.php';
            manejar_movimientos();
            break; 
            
        case 'reportes_movimientos':
            require_once __DIR__ . '/../modules/donaciones/controllers/movimientos.php';
            manejar_reportes_movimientos();
            break;    

        case 'voluntariado':
            manejar_voluntariado();
            break;

        case 'auditorias':
            if (!requerir_roles([
                'Super Administrador'
            ])) {
            break;
            }
            require_once __DIR__ .
            '/../modules/auditoria/controllers/auditorias.php';
            manejar_auditorias();
            break;

        case 'compatibilidad':
            require_once __DIR__ . '/../modules/compatibilidad/controllers/compatibilidad.php';
            manejar_compatibilidad();
            break;  

        default:
            responderJson(
                false,
                'Recurso no encontrado.',
                null,
                404
            );
            break;

            
    }
}
