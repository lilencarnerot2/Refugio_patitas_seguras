# 07 - Estructura final y arquitectura

Este documento refleja la estructura que existe en el repositorio y la
responsabilidad de cada componente. Es una actualización documental: no
modifica ni redefine la implementación del backend.

## 1. Estado real del repositorio

La arquitectura desplegable que existe hoy está compuesta por un frontend PHP,
una API/backend PHP, dos microservicios y servicios externos configurados por
variables de entorno. `docker-compose.yml` orquesta los cuatro contenedores
propios. PostgreSQL no está definido como servicio de Compose: se conecta a una
instancia externa mediante configuración.

```text
PatitasSeguras/
├── frontend/
│   ├── admin/
│   ├── assets/
│   │   ├── css/
│   │   ├── img/
│   │   └── js/
│   ├── public/
│   │   └── includes/
│   └── user/
│       └── includes/
├── backend/
│   ├── public/
│   └── src/
│       ├── config/
│       ├── middleware/
│       ├── modules/
│       ├── routes/
│       └── utils/
├── microservices/
│   ├── compatibility-service/
│   └── notification-service/
├── database/
│   ├── README.md
│   └── seeds/
├── docs/
├── docker-compose.yml
├── .env.example
└── README.md
```

No hay carpetas `external-services/` ni `tests/` en la raíz actual. Los
servicios externos son integraciones remotas, no código desplegado dentro de
este repositorio.

### Mapa de comunicación

```text
Navegador
   │ HTML, CSS y JavaScript
   ▼
Frontend PHP ── HTTP/JSON ──► Backend PHP / API
   │                              │
   │                              ├── SQL ──► PostgreSQL externo a Compose
   │                              ├── HTTP ─► Padrón PP2
   │                              ├── HTTP ─► Servicio de correo
   │                              ├── HTTP ─► compatibility-service
   │                              └── HTTP ─► notification-service ──► Twilio
   └── enlaces de pago configurados ─────────────────────────────────► Mercado Pago
```

El navegador no se conecta directamente con la API ni con la base de datos:
las llamadas del cliente pasan por `frontend/public/api.php`, y el backend
coordina las operaciones de negocio, persistencia e integraciones. Los enlaces
de pago son una excepción de navegación: el sitio puede derivar a una URL de
pago configurada, pero no procesa el pago en el backend.

## 2. Frontend

Aplicación PHP servida por Apache en el contenedor `frontend`; consume la API
del backend y no accede directamente a PostgreSQL.

### Páginas

- `frontend/index.php`: inicio y navegación pública.
- `frontend/public/`: catálogo y detalle de mascotas, registro, ingreso,
  recuperación, contacto, donaciones, castraciones, voluntariado y denuncias.
  `api.php` actúa como proxy al backend interno, reenvía cookies y solicitudes
  multipart, y sirve las imágenes públicas de mascotas. `includes/` contiene
  cabecera, pie, sesión y datos editoriales del refugio.
- `frontend/user/`: perfil, datos personales, solicitudes de adopción/tránsito,
  y movimientos del usuario. `includes/` contiene elementos compartidos.
- `frontend/admin/`: panel de usuarios, mascotas, solicitudes, salud,
  donaciones, movimientos, seguimientos y auditorías. `includes/` agrupa
  navegación y vistas reutilizables del panel.
- `frontend/assets/css/`: estilos compartidos.
- `frontend/assets/img/`: imágenes estáticas de la interfaz.
- `frontend/assets/js/`: cliente común de API (`api.js`) y scripts de
  autenticación, formularios, listados, perfiles, movimientos, salud y
  administración.
- `frontend/Dockerfile`: imagen del servidor web PHP.
- Las cabeceras y pies compartidos concentran la estructura común de las
  páginas públicas, de usuario y administrativas. El loader visual global
  usa `frontend/public/includes/loader.php`,
  `frontend/assets/css/styles.css` y `frontend/assets/js/loader.js`.
- La navegación del usuario ofrece accesos al perfil, donaciones y solicitudes
  desde un menú adaptable; el panel de usuario conserva su navegación lateral.

Los puntos de entrada y enrutamiento principales son `frontend/index.php`,
`frontend/public/api.php` y `backend/public/index.php`. El proxy del frontend
usa `BACKEND_INTERNAL_URL`; el backend enruta los recursos por
`backend/src/routes/api.php`.

Los catálogos de negocio se solicitan a la API cuando existe el recurso
correspondiente. Datos editoriales, cuentas del refugio, opciones de montos y
enlaces de pago se mantienen en `frontend/public/includes/datos_refugio.php`,
ya que no hay un endpoint de configuración para administrarlos. La
configuración actual contiene enlaces de Mercado Pago para montos, débito
automático y apadrinamiento; el frontend los presenta como enlaces externos.
No hay SDK ni procesamiento o confirmación de pagos de Mercado Pago en el
backend.

La página inicial todavía muestra métricas estáticas y los pies de página
incluyen enlaces genéricos a redes sociales. No existe un recurso backend para
las métricas ni se configuraron los perfiles reales; se dejan señalados como
pendientes y no se inventan valores ni endpoints.

## 3. Backend PHP

`backend/public/index.php` es el punto de entrada HTTP y
`backend/src/routes/api.php` enruta `?recurso=...` hacia los controladores.
El backend aplica autenticación/autorización, valida operaciones, coordina la
persistencia y devuelve respuestas JSON.

- `backend/src/middleware/autenticacion.php` requiere una sesión PHP válida y
  responde `401` cuando falta autenticación.
- `backend/src/middleware/autorizacion.php` vuelve a consultar el usuario, su
  estado y su rol en la base de datos. Responde `403` si el usuario está
  deshabilitado o autenticado pero no tiene un rol permitido; por ejemplo,
  `?recurso=roles` requiere `Super Administrador`.
- `backend/src/config/`: conexión y configuración de PostgreSQL, incluido el
  contexto de usuario utilizado por auditoría.
- `backend/src/middleware/`: autenticación y autorización por rol.
- `backend/src/routes/`: despacho de recursos API.
- `backend/src/utils/`: respuestas y utilidades comunes.
- `backend/src/modules/`: módulos organizados por responsabilidad:
  - `adopciones/`: formularios, compromisos, condiciones y datos del proceso.
  - `auditoria/`: consulta de auditorías.
  - `auth/`: registro, login, recuperación e integración de autenticación.
  - `compatibilidad/`: coordinación del cálculo de compatibilidad.
  - `donaciones/`: movimientos de aportes, catálogos y reportes.
  - `mail/`: envío de correo.
  - `mascotas/`: mascotas, especies, razas y fotos.
  - `movimientos_mascotas/`: movimientos y tipos de movimientos de mascotas.
  - `notificaciones_wsp/`: integración de notificaciones por WhatsApp.
  - `salud/`: fichas médicas, controles, vacunas, castraciones y tratamientos.
  - `seguimientos/`: seguimientos de adopción y tránsito.
  - `solicitudes/`: solicitudes y sus estados.
  - `transito/`: formularios y reglas de tránsito.
  - `usuarios/`: usuarios, roles, contactos, domicilios y ubicaciones.
  - `voluntariado/`: postulaciones y gestión de voluntariado.
- Los módulos suelen separar `controllers/`, `services/` y
  `repositories/`; `auth`, `mail`, `notificaciones_wsp` y algunos módulos
  pequeños organizan solo las capas que utilizan.
- `backend/Dockerfile`: imagen del backend.

## 4. Microservicios propios

### `microservices/compatibility-service/`

Servicio Python/Flask independiente que calcula compatibilidad de adopción o
tránsito. `run.py` inicia la aplicación; `app/routes.py` expone las rutas y
`app/services.py` coordina las reglas de `app/rules.py`.
`requirements.txt` y `Dockerfile` definen dependencias e imagen.

### `microservices/notification-service/`

Servicio Go que envía mensajes de WhatsApp mediante Twilio.
`main.go` inicia el servidor, `routes/routes.go` configura rutas,
`handlers/whatsapp.go` recibe solicitudes y `services/whatsapp.go` compone y
envía mensajes. `go.mod` y `go.sum` fijan dependencias; `Dockerfile` define la
imagen.

## 5. Persistencia y datos iniciales

- PostgreSQL almacena usuarios locales, mascotas, solicitudes, movimientos,
  salud, seguimientos y auditoría.
- La conexión se configura con `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER` y
  `DB_PASSWORD`; no hay una instancia de base de datos declarada en
  `docker-compose.yml`.
- `database/seeds/datos_pruebas.sql` contiene datos para pruebas.
- `database/README.md` está vacío actualmente; no se lo considera una
  especificación de esquema ni una migración.
- El volumen Docker `mascotas_uploads` conserva las imágenes cargadas fuera del
  ciclo de vida del contenedor backend.
- El backend abre la conexión con PDO y establece en esa conexión el contexto
  de usuario e IP que utilizan los triggers de auditoría.
- El directorio `database/` contiene la documentación disponible y el seed de
  prueba; no representa un contenedor PostgreSQL ni el motor de persistencia.

## 6. Servicios externos

| Integración | Uso | Configuración |
|---|---|---|
| Padrón PP2 | Registro, activación, login y recuperación de contraseña | `PP2_API_URL`, `PP2_API_KEY`, `PROYECTO_ID` |
| Servicio de correo | Envío de mensajes de registro/recuperación | `MAIL_API_URL`, `MAIL_API_KEY` |
| Twilio | Envío de notificaciones de WhatsApp a través del microservicio propio de notificaciones | Variables `TWILIO_*` |
| Mercado Pago | Destino de enlaces de pago que puedan configurarse para la interfaz | No hay SDK ni API de pago integrada en el backend |

Mercado Pago no es un microservicio propio ni procesa pagos dentro del backend:
la interfaz puede enlazar a páginas externas de pago configuradas. El backend
recibe la configuración de compatibilidad y notificaciones por
`COMPATIBILITY_SERVICE_URL` y `NOTIFICATION_SERVICE_URL`.

## 7. Despliegue y comunicación

`docker-compose.yml` levanta:

1. `frontend`, publicado en `${FRONTEND_PORT}` y conectado al backend mediante
   `BACKEND_INTERNAL_URL`.
2. `backend`, publicado en `${BACKEND_PORT}`, con acceso a PostgreSQL y a
   servicios externos.
3. `compatibility-service`, publicado en `${COMPATIBILITY_PORT}`.
4. `notification-service`, publicado en `${NOTIFICATION_PORT}`.

Los contenedores propios comparten la red `patitas_network`. El frontend se
comunica con el backend por HTTP/JSON; solo el backend coordina acceso a la
persistencia y a los servicios especializados.
