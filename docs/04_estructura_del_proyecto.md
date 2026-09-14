# 04 - Estructura del Proyecto

## 1. Introducción

El proyecto **Patitas Seguras** se organiza mediante una estructura modular que separa claramente las responsabilidades de cada componente del sistema.

La estructura contempla:

- Frontend.
- Backend principal.
- Microservicios.
- Servicios externos.
- Base de datos.
- Documentación.
- Pruebas.
- Configuración general mediante Docker y variables de entorno.

Esta organización permite mantener el proyecto ordenado, facilitar el trabajo en equipo y permitir que cada parte del sistema pueda evolucionar de manera independiente.

> **Importante:** Es importante aclarar que esta estructura representa la organización general prevista para el proyecto. Algunas carpetas o archivos pueden incorporarse progresivamente a medida que avancen los distintos Sprints.

---

## 2. Estructura general

```
patitas-seguras/
│
├── frontend/
│
├── backend/
│
├── microservices/
│
├── external-services/
│
├── database/
│
├── docs/
│
├── tests/
│
├── docker-compose.yml
├── .env
├── .gitignore
└── README.md
```

Cada directorio contiene una parte específica de la solución.

---

## 3. Frontend

La carpeta:

```
frontend/
```

contiene toda la interfaz visual de **Patitas Seguras**.

Las principales tecnologías utilizadas son:

- HTML.
- CSS.
- Bootstrap.
- JavaScript.

El frontend se encarga de:

- Mostrar información al usuario.
- Capturar formularios y acciones.
- Consumir la API del backend.
- Procesar respuestas JSON.
- Renderizar los datos recibidos.

El frontend **no accede directamente a PostgreSQL**.

### Estructura

```
frontend/
│
├── public/
│   ├── index.php
│   ├── mascotas.php
│   ├── mascota-detalle.php
│   ├── adopcion-info.php
│   ├── transito-info.php
│   ├── voluntariado.php
│   └── donaciones.php
│
├── user/
│   ├── mi-perfil.php
│   ├── solicitar-adopcion.php
│   ├── solicitar-transito.php
│   └── mis-solicitudes.php
│
├── admin/
│   ├── dashboard.php
│   ├── mascotas.php
│   ├── mascota-form.php
│   ├── salud.php
│   ├── solicitudes.php
│   ├── solicitud-detalle.php
│   ├── hogares-transito.php
│   ├── donaciones.php
│   ├── usuarios.php
│   └── configuracion.php
│
├── assets/
│   ├── css/
│   │   └── styles.css
│   │
│   ├── bootstrap/
│   │
│   ├── js/
│   │   ├── app.js
│   │   ├── api.js
│   │   ├── auth.js
│   │   ├── mascotas.js
│   │   ├── solicitudes.js
│   │   ├── adopcion.js
│   │   ├── transito.js
│   │   ├── voluntariado.js
│   │   └── donaciones.js
│   │
│   └── img/
│
└── Dockerfile
```

### 3.1 public/

Contiene las páginas accesibles sin necesidad de iniciar sesión.

Desde esta sección un visitante podrá:

- Acceder a la landing page.
- Consultar el catálogo de mascotas.
- Visualizar el detalle de una mascota.
- Consultar información sobre adopciones.
- Consultar información sobre hogares de tránsito.
- Participar en actividades de voluntariado.
- Consultar las opciones de donación.

### 3.2 user/

Contiene las páginas correspondientes a usuarios autenticados.

Entre las funcionalidades previstas se encuentran:

- Consultar y modificar datos personales.
- Completar solicitudes de adopción.
- Completar solicitudes de hogar de tránsito.
- Consultar el estado de las solicitudes realizadas.

### 3.3 admin/

Contiene el panel administrativo utilizado por administradores y superadministradores.

Desde esta sección se podrán gestionar:

- Mascotas.
- Información médica.
- Solicitudes.
- Hogares de tránsito.
- Donaciones y aportes.
- Usuarios.
- Configuraciones y catálogos del sistema.

Las acciones disponibles dependerán del rol del usuario autenticado.

### 3.4 assets/

Contiene recursos reutilizables del frontend.

#### css/

Almacena los estilos personalizados de **Patitas Seguras**.

#### bootstrap

Contiene los recursos correspondientes a Bootstrap, utilizado para:

- Diseño responsive.
- Componentes visuales.
- Interacciones básicas de interfaz.

#### js/

Contiene los archivos JavaScript encargados principalmente de consumir la API y representar la información recibida.

Por ejemplo:

- `mascotas.js` se utiliza para obtener y mostrar información relacionada con mascotas.

Mientras que:

- `api.js` podrá centralizar funciones HTTP reutilizables.

Por ejemplo:

- `GET`
- `POST`
- `PUT`
- `DELETE`

De esta forma se evita repetir la misma lógica de comunicación con el backend en distintos archivos.

> La lógica principal del negocio se mantiene fuera del frontend.

---

## 4. Backend

La carpeta:

```
backend/
```

contiene el backend principal del sistema, desarrollado en **PHP**.

Su responsabilidad es:

- Recibir solicitudes HTTP.
- Validar información.
- Aplicar reglas de negocio.
- Acceder a PostgreSQL.
- Coordinar los microservicios.
- Comunicarse con servicios externos.
- Devolver respuestas JSON al frontend.

### Estructura

```
backend/
│
├── public/
│   └── index.php
│
├── src/
│   ├── config/
│   │   └──  database.php
│   │   
│   │
│   ├── controllers/
│   │   ├── UsuarioController.php
│   │   ├── MascotaController.php
│   │   ├── SaludController.php
│   │   ├── SolicitudController.php
│   │   ├── AdopcionController.php
│   │   ├── TransitoController.php
│   │   ├── DonacionController.php
│   │   └── VoluntariadoController.php
│   │
│   ├── repositories/
│   │   ├── BaseRepository.php
│   │   ├── UsuarioRepository.php
│   │   ├── MascotaRepository.php
│   │   ├── SaludRepository.php
│   │   ├── SolicitudRepository.php
│   │   ├── AdopcionRepository.php
│   │   ├── TransitoRepository.php
│   │   └── MovimientoRepository.php
│   │
│   ├── services/
│   │   ├── AuthService.php
│   │   ├── CompatibilityService.php
│   │   └── NotificationService.php
│   │
│   ├── routes/
│   │   └── api.php
│   │
│   ├── middleware/
│   │   ├── AuthMiddleware.php
│   │   └── RoleMiddleware.php
│   │
│   └── utils/
│       ├── Request.php
│       ├── Response.php
│       ├── Validator.php
│       └── HttpClient.php
│
└── Dockerfile
```

---

## 5. backend/public

Contiene el punto de entrada público del backend.

```
index.php
```

Funciona como punto único de acceso a la API.

Su función principal es recibir las solicitudes HTTP y dirigirlas hacia las rutas y componentes correspondientes.

Conceptualmente:

```
Request HTTP
     │
     ▼
index.php
     │
     ▼
routes
     │
     ▼
controller
```

---

## 6. backend/src/config

Contiene configuraciones internas.

### database.php

Centraliza la conexión reutilizable con PostgreSQL.

La conexión se realiza mediante PHP y PDO utilizando el controlador PostgreSQL correspondiente.


---

## 7. Controllers

```
controllers/
```

Los controladores reciben las solicitudes correspondientes a cada módulo y coordinan el flujo necesario para resolverlas.

Por ejemplo:

- `MascotaController.php` puede recibir una solicitud para consultar una mascota y utilizar posteriormente:

- `MascotaRepository.php` para obtener los datos desde PostgreSQL.

Los controladores **no deberían contener consultas SQL directamente**.

---

## 8. Repositories

```
repositories/
```

Esta capa se encarga específicamente del acceso a PostgreSQL.

Contiene las consultas necesarias para:

- Obtener información.
- Insertar registros.
- Modificar datos.
- Realizar operaciones relacionadas con cada módulo.

Ejemplo:

- `MascotaRepository.php` contiene las operaciones relacionadas con la persistencia de mascotas.

- `BaseRepository.php` puede centralizar funciones reutilizables relacionadas con la base de datos para evitar duplicación de código.

El flujo principal es:

```
Controller
    │
    ▼
Repository
    │
    ▼
PostgreSQL
```

---

## 9. Services

```
services/
```

Contiene clases encargadas de comunicarse con servicios externos al backend PHP.

- `AuthService.php` - Consume el servicio externo de autenticación.
- `CompatibilityService.php` - Se comunica con el microservicio Python + Flask encargado de evaluar compatibilidad entre una mascota y un postulante.
- `NotificationService.php` - Se comunica con el microservicio Go + Fiber encargado del envío de notificaciones.

Esto permite que los controladores no tengan que implementar directamente la comunicación HTTP con otros servicios.

---

## 10. Routes

```
routes/api.php
```

Centraliza la definición de los endpoints disponibles en la API.

Las rutas permiten relacionar:

```
Método HTTP + URL
```

con el controlador correspondiente.

Por ejemplo, conceptualmente:

```
GET /mascotas
```

puede dirigir la solicitud hacia:

```
MascotaController
```

---

## 11. Middleware

```
middleware/
```

Contiene componentes que se ejecutan antes de permitir determinadas operaciones.

- `AuthMiddleware.php` - Comprueba que el usuario se encuentre correctamente autenticado.
- `RoleMiddleware.php` - Comprueba que el usuario tenga el rol necesario para ejecutar una determinada acción.

Por ejemplo:

- Usuario
- Administrador
- Superadministrador

De esta forma los permisos no tienen que verificarse manualmente en cada controlador.

---

## 12. Utils

```
utils/
```

Contiene funciones y clases reutilizables por diferentes módulos.

- `Request.php` - Facilita la lectura de JSON, parámetros HTTP y datos enviados mediante formularios.
- `Response.php` - Permite mantener un formato uniforme en las respuestas JSON.
- `Validator.php` - Centraliza validaciones generales.
- `HttpClient.php` - Facilita la comunicación HTTP entre el backend y otros servicios.

---

## 13. Microservicios

La carpeta:

```
microservices/
```

contiene los servicios desarrollados por el propio grupo que funcionan de manera independiente al backend PHP.

Actualmente se contemplan dos microservicios:

- `compatibility-service/`
- `notification-service/`

---

## 14. Compatibility Service

```
microservices/compatibility-service/
```

Microservicio desarrollado utilizando:

- Python + Flask

Su responsabilidad es evaluar la compatibilidad entre una mascota y una persona postulante.

### Estructura

```
compatibility-service/
│
├── app/
│   ├── __init__.py
│   ├── routes.py
│   ├── services.py
│   └── rules.py
│
├── requirements.txt
├── run.py
└── Dockerfile
```

- `routes.py` - Define los endpoints HTTP disponibles.
- `services.py` - Contiene la lógica necesaria para realizar la evaluación.
- `rules.py` - Contiene las reglas utilizadas para determinar la compatibilidad.

> La evaluación generada es orientativa. El microservicio **no aprueba ni rechaza automáticamente** una solicitud de adopción.

---

## 15. Notification Service

```
microservices/notification-service/
```

Microservicio desarrollado utilizando:

- Go + Fiber

Su responsabilidad es gestionar las notificaciones del sistema.

Puede utilizar canales como:

- Correo electrónico.
- WhatsApp.

### Estructura

```
notification-service/
│
├── cmd/
│   └── main.go
│
├── internal/
│   ├── handlers/
│   │   └── notification_handler.go
│   │
│   ├── services/
│   │   └── notification_service.go
│   │
│   └── routes/
│       └── routes.go
│
├── go.mod
└── Dockerfile
```

- `cmd/main.go` - Es el punto de entrada del microservicio.
- `handlers/` - Recibe las solicitudes HTTP enviadas al servicio.
- `services/` - Contiene la lógica relacionada con la preparación y envío de notificaciones.
- `routes/` - Define los endpoints disponibles mediante Fiber.

---

## 16. Servicios externos

La carpeta:

```
external-services/
```

representa servicios utilizados por **Patitas Seguras** pero desarrollados fuera del grupo.

Actualmente se contempla:

- `auth-service/`

Este servicio será responsable de:

- Login.
- Validación de autenticación.
- Verificación mediante correo electrónico.

La carpeta **no contiene necesariamente el código fuente del servicio externo**.

Puede contener únicamente:

- `README.md` con la documentación necesaria para realizar la integración.

Esto permite distinguir claramente entre:

- Servicios desarrollados por el grupo

y:

- Servicios externos consumidos por el proyecto

---

## 17. Base de datos

La carpeta:

```
database/
```

contiene los recursos relacionados con PostgreSQL.

### Estructura

```
database/
│
├── init/
│   └── 01_patitas_seguras.sql
│
├── seeds/
│   └── datos_prueba.sql
│
└── README.md
```

### init/

Contiene el script principal encargado de crear la estructura de la base de datos.

`01_patitas_seguras.sql` incluye:

- Tablas.
- Restricciones.
- Relaciones.
- Índices.
- Elementos necesarios para inicializar la base.

### seeds/

Contiene datos ficticios destinados exclusivamente a desarrollo y pruebas.

Por ejemplo:

- `datos_prueba.sql` permite cargar registros necesarios para probar diferentes funcionalidades sin utilizar información real.

### README.md

Documenta:

- Cómo crear la base.
- Cómo ejecutar los scripts.
- Configuración necesaria.
- Consideraciones del entorno PostgreSQL.

---

## 18. Documentación

La carpeta:

```
docs/
```

centraliza la documentación formal del proyecto.

Se divide en:

```
docs/
├── arquitectura/
├── api/
├── database/
├── diagramas/
└── proyecto/
```

### arquitectura/

Documentación de:

- Arquitectura general.
- Servicios.
- Comunicación entre componentes.
- Flujos.

### api/

Documentación de:

- Endpoints.
- Métodos HTTP.
- Parámetros.
- Requests.
- Responses.
- Contratos JSON.

### database/

Contiene documentación como:

- DER.
- Modelo relacional.
- Diccionario de datos.

### diagramas/

Contiene diagramas relacionados con el sistema.

Por ejemplo:

- UML.
- Casos de uso.
- Diagramas de secuencia.

### proyecto/

Contiene documentación general relacionada con:

- Objetivos.
- Alcance.
- Exclusiones.
- Convenio.
- Sprints.
- Organización del proyecto.

---

## 19. Tests

La carpeta:

```
tests/
```

centraliza las pruebas del sistema.

### Estructura

```
tests/
├── backend/
├── microservices/
│   ├── compatibility/
│   └── notifications/
│
└── integration/
```

- `backend/` - Pruebas correspondientes al backend PHP.
- `microservices/compatibility/` - Pruebas relacionadas con el microservicio Flask.
- `microservices/notifications/` - Pruebas relacionadas con el microservicio Go + Fiber.
- `integration/` - Pruebas destinadas a comprobar la comunicación entre distintos servicios.

Por ejemplo:

- Frontend → Backend
- Backend → PostgreSQL
- Backend → Flask
- Backend → Go

---

## 20. Docker Compose

El archivo:

```
docker-compose.yml
```

ubicado en la raíz permite levantar y coordinar los distintos componentes del sistema.

Actualmente el entorno contempla principalmente:

- Frontend
- Backend
- PostgreSQL

y posteriormente podrá incluir:

- Compatibility Service
- Notification Service

Docker Compose permite configurar:

- Construcción de imágenes.
- Contenedores.
- Redes.
- Puertos.
- Volúmenes.
- Variables de entorno.
- Dependencias entre servicios.

---

## 21. Archivo .env

El proyecto utiliza un único archivo:

```
.env
```

ubicado en la raíz.

Contiene configuraciones reales y credenciales utilizadas por los distintos servicios.

Por ejemplo:

```
DB_HOST
DB_PORT
DB_NAME
DB_USER
DB_PASSWORD
```

También podrá incluir configuraciones necesarias para:

- Servicios externos.
- Microservicios.
- Notificaciones.
- Autenticación.

> El archivo `.env` **no debe subirse al repositorio Git**.

---

## 22. .gitignore

Define archivos y directorios que Git debe ignorar.

Entre ellos puede encontrarse:

- `.env`

junto con archivos temporales, dependencias locales o elementos generados automáticamente.

---

## 23. README.md

El archivo ubicado en la raíz funciona como documentación inicial del repositorio.

Debe permitir que una persona que accede por primera vez al proyecto pueda comprender:

- Qué es Patitas Seguras.
- Qué tecnologías utiliza.
- Cómo está organizado.
- Cómo levantar el entorno.
- Qué configuraciones necesita.
- Dónde encontrar la documentación detallada.

---

## 24. Separación de responsabilidades

La estructura del proyecto busca mantener una separación clara:

| Componente | Responsabilidad principal |
|------------|---------------------------|
| Frontend | Interfaz y comunicación con la API |
| Backend PHP | Lógica de negocio y coordinación |
| PostgreSQL | Persistencia de información |
| Compatibility Service | Evaluación de compatibilidad |
| Notification Service | Gestión de notificaciones |
| Auth Service | Autenticación externa |
| Docker | Contenerización |
| Docker Compose | Coordinación del entorno |
| Tests | Verificación del funcionamiento |
| Docs | Documentación técnica y funcional |

---

## 25. Flujo general dentro de la estructura

La organización de carpetas refleja el flujo arquitectónico del sistema.

```
Usuario
   │
   ▼
frontend/
   │
   │ HTTP / JSON
   ▼
backend/
   │
   ├─────────────────────► database/
   │                  PostgreSQL
   │
   ├─────────────────────► microservices/
   │                  │
   │                  ├── compatibility-service
   │                  └── notification-service
   │
   └─────────────────────► external-services/
                      auth-service
```

El backend PHP funciona como coordinador principal.

El frontend no accede directamente a la base de datos ni implementa la lógica central del negocio.

---

## 26. Resumen

La estructura de **Patitas Seguras** busca separar claramente las diferentes responsabilidades del sistema:

```
Interfaz
   ↓
Frontend

Lógica principal
   ↓
Backend PHP

Persistencia
   ↓
PostgreSQL

Procesos especializados
   ↓
Microservicios

Servicios externos
   ↓
Autenticación
```

Esta organización facilita el mantenimiento del código, el trabajo colaborativo, las pruebas, el despliegue mediante Docker y la incorporación progresiva de nuevas funcionalidades durante los distintos Sprints.