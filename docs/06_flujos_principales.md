# 06 - Flujos principales

Este documento describe los recorridos funcionales implementados entre la
interfaz, la API PHP, PostgreSQL y los servicios externos. La API/backend es
la autoridad para validar datos, permisos y estados; los controles del
frontend son ayudas de uso y no reemplazan esas reglas.

La distribución de componentes y sus límites de comunicación se detalla en
[07 - Estructura final y arquitectura](./07_reforma_de_arquitectura.md).

## 1. Registro y activación

1. La persona completa `frontend/public/registro.php`; `registro.js` envía los
   datos a `?recurso=registro`.
2. `backend/src/modules/auth/controllers/registro.php` valida los campos,
   formato de correo y fecha de nacimiento, y registra la cuenta en Padrón PP2.
   Luego sincroniza o crea el usuario local y registra el contacto.
3. Para una cuenta nueva, la respuesta indica que se debe confirmar la cuenta
   desde el correo. El registro no inicia una sesión; la persona podrá ingresar
   una vez activada la cuenta en Padrón PP2.
4. Si el correo ya corresponde a una cuenta pendiente de activación, la
   respuesta indica que debe completar esa activación. Si la cuenta ya está
   activa, la API responde `409` y dirige a la persona a iniciar sesión o
   recuperar la contraseña.

## 2. Inicio de sesión

1. `frontend/public/login.php` y `frontend/assets/js/auth.js` envían correo y
   contraseña a `?recurso=login`.
2. El backend valida las credenciales con Padrón PP2 y busca o sincroniza el
   usuario local.
3. Si la cuenta está habilitada, el backend inicia la sesión PHP y devuelve los
   datos de usuario y rol.
4. El frontend actualiza la navegación según el estado recibido. Las páginas y
   recursos protegidos vuelven a comprobar sesión y rol en el servidor; ocultar
   enlaces en la interfaz no constituye autorización.

## 3. Recuperación y cambio de contraseña

1. Desde `frontend/public/recuperar.php`, `recuperar.js` envía el correo a
   `?recurso=password-reset-solicitar`.
2. El backend solicita el restablecimiento a Padrón PP2. Si recibe un token,
   genera un enlace al frontend y solicita el envío del correo al servicio de
   mail.
3. La persona abre el enlace y envía el token junto con la nueva contraseña a
   `?recurso=password-reset-confirmar`.
4. El backend delega la confirmación a Padrón PP2 y devuelve el resultado.
   Desde el perfil, el cambio iniciado con
   `?recurso=password-change-request` permite ingresar el correo de la cuenta
   autenticada y envía allí un enlace para elegir la nueva contraseña; el
   backend verifica que coincida con el correo asociado a esa cuenta.

## 4. Solicitud de adopción

1. El catálogo público consulta mascotas disponibles. Para iniciar la
   solicitud, la persona debe tener una sesión válida.
2. `frontend/user/solicitar_adopcion.php` carga los catálogos y la ficha de la
   mascota; `formulario-solicitud.js` prepara los datos de vivienda, hogar,
   contactos y condiciones especiales.
3. El frontend crea o reutiliza la solicitud mediante `?recurso=solicitudes`
   y envía el formulario a `?recurso=formularios_adopcion`.
4. El backend comprueba autenticación, pertenencia de la solicitud, estado y
   reglas del formulario. Los datos de domicilio y contacto se guardan en sus
   recursos propios.
5. El equipo administrador gestiona la solicitud y sus seguimientos desde el
   panel. Cuando se solicita el cálculo de compatibilidad, el backend coordina
   la consulta al microservicio `compatibility-service`.

## 5. Solicitud de tránsito

1. Desde el catálogo se selecciona una mascota y se requiere una sesión válida.
2. `frontend/user/solicitar_transito.php` comparte el componente
   `formulario-solicitud.js`, que carga catálogos y recopila la información del
   hogar de tránsito.
3. El frontend crea o reutiliza la solicitud con `?recurso=solicitudes` y envía
   el formulario a `?recurso=formularios_transito`.
4. El backend valida autenticación, permisos, solicitud, fechas y datos del
   formulario. El usuario puede consultar su estado desde sus solicitudes.
5. El equipo administrador gestiona el trámite y los seguimientos de tránsito;
   las notificaciones externas se tramitan desde el backend cuando corresponde.

## 6. Movimientos y donaciones

1. `frontend/public/donaciones.php` presenta los datos de transferencia y
   opciones de montos. Las URLs de Mercado Pago, débito automático y
   apadrinamiento se configuran en
   `frontend/public/includes/datos_refugio.php`; las URLs actualmente
   configuradas se abren como enlaces externos. El backend registra los
   movimientos informados, pero no procesa pagos ni confirma transacciones con
   Mercado Pago. La interfaz de movimientos envía los registros a
   `?recurso=movimientos`.
2. Los movimientos monetarios admiten una persona autenticada o un aporte
   anónimo. Los aportes no monetarios requieren sesión; la API determina el
   usuario asociado y valida los datos del movimiento.
3. La consulta de movimientos es autenticada. El usuario consulta sus propios
   registros y el personal autorizado utiliza los listados y reportes del
   panel administrativo.
4. `movimientos.php` valida tipos, método de pago, monto, frecuencia, fecha,
   descripción, filtros y permisos. Los errores de negocio se muestran desde
   la respuesta de la API, en lugar de repetir esas reglas en JavaScript.
5. Los reportes administrativos se obtienen con `?recurso=reportes_movimientos`.

## 7. Movimientos de mascotas y auditorías

1. Los cambios administrativos de datos se realizan a través de los recursos
   del backend. El contexto de usuario de la conexión se usa para asociar las
   operaciones auditables.
2. Los triggers de auditoría registran operaciones de inserción, actualización
   y eliminación en la base de datos, con la tabla, acción, usuario y datos de
   la operación disponibles para consulta.
3. `?recurso=auditorias` es de solo lectura y está restringido al rol
   Super Administrador. Permite filtrar por usuario, tabla, acción y fechas, o
   consultar un registro por ID.
4. `frontend/admin/auditorias.php`, `auditorias.js` y
   `auditoria_detalle.php` presentan el listado y detalle. El frontend no crea
   ni modifica registros de auditoría.

## 8. Validación y manejo de errores

- El backend valida de nuevo cada operación y aplica permisos y reglas de
  negocio, incluso cuando el frontend aporta controles para mejorar la
  experiencia.
- Los formularios de movimientos delegan las reglas de obligatoriedad y monto
  a la API y muestran sus respuestas.
- Los formularios de adopción y tránsito mantienen ayudas de interfaz para
  evitar solicitudes incompletas en un flujo que primero crea la solicitud y
  luego guarda el formulario. La autorización y validación final siguen siendo
  responsabilidad del backend.
- Los datos editoriales, de contacto y bancarios del refugio se centralizan en
  `frontend/public/includes/datos_refugio.php`. No se encontró un recurso de la
  API que los provea; hasta que exista una fuente administrable, permanecen
  como configuración del frontend.

## 9. Autenticación y autorización

- Los recursos que requieren una sesión responden `401` cuando no hay un
  usuario autenticado.
- Los recursos restringidos por rol vuelven a consultar en la base de datos el
  estado y el rol actual del usuario. Un usuario deshabilitado o autenticado
  sin uno de los roles permitidos recibe `403`.
- Por ejemplo, `?recurso=roles` requiere el rol `Super Administrador`; un
  usuario autenticado con el rol `Usuario` recibe `403` y una respuesta JSON
  indicando que no tiene permisos. La autorización se aplica en el backend,
  independientemente de los controles de navegación del frontend.
