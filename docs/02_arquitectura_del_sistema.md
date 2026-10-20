# Patitas Seguras
## Arquitectura del sistema

---

## 1. Introducción

La arquitectura de **Patitas Seguras** fue diseñada con una separación clara de responsabilidades entre frontend, backend, base de datos, microservicios y servicios externos.

El sistema utiliza una arquitectura distribuida en diferentes componentes que se comunican entre sí mediante solicitudes HTTP y APIs.

Esta organización permite mantener separadas las responsabilidades de presentación, procesamiento de datos, persistencia e integraciones externas, facilitando el mantenimiento y la evolución del proyecto.

La arquitectura general contempla los siguientes componentes:

- Frontend web.
- Backend principal.
- Base de datos PostgreSQL.
- Microservicio de compatibilidad.
- Microservicio de notificaciones.
- Servicios externos de registro, login y envío de mails.
- Infraestructura mediante Docker.

---

## 2. Arquitectura general

De forma simplificada, la arquitectura puede representarse de la siguiente manera:

```
                    ┌─────────────────────────────┐
                    │           Usuario           │
                    │      Navegador web          │
                    └──────────────┬──────────────┘
                                   │
                                   ▼
                    ┌─────────────────────────────┐
                    │          Frontend           │
                    │ HTML + CSS + Bootstrap + JS │
                    └──────────────┬──────────────┘
                                   │
                              HTTP / JSON
                                   │
                                   ▼
                    ┌─────────────────────────────┐
                    │      Backend principal      │
                    │            PHP              │
                    └───────┬──────────┬──────────┘
                            │          │
                            │          │ HTTP / JSON
                            │          │
                            ▼          ▼
              ┌──────────────────┐   ┌─────────────────────┐
              │    PostgreSQL    │   │   Microservicios   │
              │  Base de datos  │   │                     │
              └──────────────────┘   │ Python + Flask      │
                                     │ Compatibilidad      │
                                     │                     │
                                     │ Go + Fiber          │
                                     │ Notificaciones      │
                                     └──────────┬──────────┘
                                                │
                                                ▼
                                     ┌─────────────────────┐
                                     │ Servicios externos  │
                                     │ Registro/validacion │
                                     │ Login / validación  │
                                     │ Mails / envío       │
                                     └─────────────────────┘
```

El frontend constituye la capa con la que interactúa el usuario.

El backend principal concentra la lógica de negocio y el acceso a los datos.

PostgreSQL mantiene la información persistente del sistema.

Los microservicios permiten desacoplar funcionalidades específicas como la evaluación de compatibilidad y el envío de notificaciones.

Los servicios externos son consumidos por Patitas Seguras, pero no forman parte del desarrollo interno del equipo.

---

## 3. Componentes de la arquitectura

### 3.1. Frontend

El frontend representa la capa de presentación de **Patitas Seguras**.

Es responsable de mostrar la información al usuario, presentar formularios y permitir la interacción con las diferentes funcionalidades de la plataforma.

Las principales tecnologías utilizadas son:

- HTML.
- CSS.
- Bootstrap.
- JavaScript.

#### Responsabilidades principales

El frontend se encarga de:

- presentar las páginas del sistema;
- mostrar el catálogo de mascotas;
- mostrar el detalle de cada mascota;
- presentar formularios;
- recopilar información ingresada por los usuarios;
- realizar solicitudes al backend;
- recibir respuestas en formato JSON;
- representar dinámicamente los datos obtenidos;
- proporcionar retroalimentación visual al usuario.

JavaScript se utiliza principalmente para consumir las APIs del backend y representar los datos recibidos.

Bootstrap se utiliza para los componentes visuales, estilos responsivos y comportamiento de interfaz provisto por el framework.

Esta separación busca evitar incorporar lógica de negocio en el frontend.

---

## 4. Backend principal

El backend principal está desarrollado en **PHP**.

Es el componente encargado de recibir las solicitudes provenientes del frontend, procesarlas, aplicar las reglas correspondientes y comunicarse con la base de datos o con otros servicios cuando sea necesario.

### Responsabilidades principales

El backend se encarga de:

- exponer los endpoints utilizados por el frontend;
- recibir parámetros y datos enviados por los usuarios;
- validar la información recibida;
- ejecutar la lógica de negocio;
- consultar información almacenada;
- registrar o modificar información;
- comunicarse con PostgreSQL;
- comunicarse con los microservicios;
- devolver respuestas en formato JSON;
- gestionar códigos de estado HTTP;
- controlar errores;
- aplicar políticas de acceso correspondientes;
- coordinar la comunicación entre los diferentes componentes internos del sistema.

El backend funciona como la principal capa intermediaria entre el frontend y la información persistente.

El frontend no accede directamente a PostgreSQL ni a los microservicios.

---

## 5. Base de datos

La información persistente de **Patitas Seguras** se almacena en una base de datos **PostgreSQL**.

La base de datos concentra la información estructurada necesaria para el funcionamiento de la plataforma.

Entre otros datos, almacena información relacionada con:

- usuarios;
- roles;
- domicilios;
- contactos;
- mascotas;
- especies;
- razas;
- información médica;
- solicitudes;
- formularios de adopción;
- formularios de hogar de tránsito;
- compromisos;
- movimientos de mascotas;
- donaciones;
- estados;
- historiales;
- auditorías.

### Responsabilidades principales

PostgreSQL se encarga de:

- almacenar la información persistente;
- garantizar relaciones entre las diferentes entidades;
- aplicar restricciones de integridad;
- controlar claves primarias y foráneas;
- aplicar restricciones `NOT NULL`, `UNIQUE` y `CHECK`;
- mantener historiales;
- proporcionar información al backend mediante consultas;
- almacenar datos relacionados con auditoría cuando corresponda.

La comunicación con PostgreSQL es realizada por el backend mediante **PDO** con el controlador de PostgreSQL.

---

## 6. Microservicio de compatibilidad

La evaluación de compatibilidad entre una mascota y un posible adoptante se implementará como un microservicio independiente desarrollado en **Python con Flask**.

Esta funcionalidad se encuentra separada del backend principal para mantener desacoplada la lógica específica utilizada para analizar compatibilidades.

### Responsabilidades principales

El microservicio se encargará de:

- recibir información relevante sobre una mascota;
- recibir información correspondiente al posible adoptante;
- analizar los datos utilizando las reglas de compatibilidad definidas;
- calcular o determinar un resultado de compatibilidad;
- devolver el resultado al backend principal.

El resultado tendrá carácter orientativo.

La decisión final sobre una adopción continuará siendo responsabilidad del personal autorizado del refugio.

### Comunicación

El microservicio expondrá una API HTTP.

```
Backend PHP
    │
    │ HTTP / JSON
    ▼
Microservicio Flask
    │
    │ Procesamiento
    ▼
Resultado de compatibilidad
    │
    ▼
Backend PHP
```

El flujo detallado será especificado en `03_flujos_del_sistema.md`.

---

## 7. Microservicio de notificaciones

El servicio de notificaciones se implementará como un microservicio independiente desarrollado en **Go con Fiber**.

Su función será centralizar el envío de comunicaciones generadas por **Patitas Seguras** y mantener esta responsabilidad separada del backend principal.

El backend PHP será quien determine, según la lógica de negocio, cuándo corresponde generar una notificación. Luego enviará al microservicio la información necesaria para efectuarla.

### Responsabilidades principales

El microservicio de notificaciones se encargará de:

- recibir solicitudes de notificación provenientes del backend;
- identificar el tipo de notificación;
- identificar el canal de comunicación correspondiente;
- preparar la información necesaria para el envío;
- consumir APIs o servicios externos de mensajería;
- gestionar respuestas de los proveedores externos;
- informar al backend si la operación fue procesada correctamente o si ocurrió un error.

### Canales contemplados

El microservicio podrá gestionar comunicaciones mediante:

- correo electrónico;
- WhatsApp.

El envío efectivo dependerá de proveedores o APIs externas.

### Casos principales

El servicio de notificaciones será utilizado principalmente para:

- confirmaciones de inscripción a actividades de voluntariado;
- seguimiento de mascotas adoptadas;
- recordatorios y coordinación de controles de mascotas alojadas en hogares de tránsito.

### Comunicación

De forma conceptual:

```
Backend PHP
    │
    │ Solicitud HTTP / JSON
    ▼
Microservicio Go + Fiber
    │
    ├── Determina canal
    ├── Prepara mensaje
    └── Consume servicio externo
                 │
                 ▼
        Correo / WhatsApp
                 │
                 ▼
         Resultado del envío
                 │
                 ▼
Microservicio Go + Fiber
    │
    ▼
Backend PHP
```

El flujo técnico completo será detallado en `03_flujos_del_sistema.md`.

---

## 8. Servicios externos

Algunas funcionalidades utilizadas por **Patitas Seguras** serán proporcionadas por servicios externos al desarrollo del equipo.

Actualmente se contemplan como servicios externos:

- Registro de usuarios;
- verificación de login;
- proveedores utilizados para el envío efectivo de correo electrónico;

Esto significa que el equipo no desarrollará internamente la infraestructura propia de autenticación ni los proveedores de mensajería.

Sin embargo, sí será responsabilidad del equipo integrar estos servicios correctamente dentro de **Patitas Seguras**.

En el caso de las notificaciones, el microservicio desarrollado en Go + Fiber actuará como intermediario entre el backend y los proveedores externos.

---

## 9. Responsabilidad de cada tecnología

### HTML

Se utiliza para definir la estructura del contenido de las páginas web.

**Responsabilidades:**
- estructura de páginas;
- formularios;
- secciones;
- contenido visible;
- elementos semánticos.

---

### CSS

Se utiliza para personalizar la presentación visual del sistema.

**Responsabilidades:**
- estilos personalizados;
- ajustes visuales;
- distribución;
- presentación específica no cubierta directamente por Bootstrap.

---

### Bootstrap

Se utiliza como framework visual principal del frontend.

**Responsabilidades:**
- diseño responsivo;
- grillas;
- botones;
- formularios;
- tarjetas;
- navegación;
- componentes visuales;
- comportamiento interactivo incorporado por el framework.

---

### JavaScript

JavaScript se utiliza principalmente para la comunicación del frontend con el backend y el renderizado de información dinámica.

**Responsabilidades:**
- realizar peticiones mediante `fetch`;
- consumir endpoints;
- procesar respuestas JSON;
- mostrar información recibida;
- enviar datos de formularios;
- gestionar mensajes de éxito o error cuando corresponda.

La lógica de negocio principal no deberá concentrarse en JavaScript.

---

### PHP

PHP constituye el backend principal del proyecto.

**Responsabilidades:**
- recibir solicitudes;
- exponer endpoints;
- validar datos;
- ejecutar reglas de negocio;
- consultar y modificar información;
- comunicarse con PostgreSQL;
- coordinar la comunicación con microservicios;
- generar respuestas HTTP y JSON;
- gestionar errores del backend.

---

### PostgreSQL

PostgreSQL constituye la capa de persistencia.

**Responsabilidades:**
- almacenar información;
- mantener relaciones;
- asegurar integridad referencial;
- ejecutar restricciones;
- mantener historiales;
- soportar auditorías;
- responder consultas realizadas desde el backend.

---

### Python + Flask

Python con Flask se utilizará para el microservicio de compatibilidad.

**Responsabilidades:**
- recibir datos relacionados con mascota y adoptante;
- aplicar reglas de análisis;
- obtener un resultado de compatibilidad;
- exponer el resultado mediante una API HTTP.

---

### Go + Fiber

Go con Fiber se utilizará para el microservicio de notificaciones e integraciones asociadas a comunicaciones.

**Responsabilidades:**
- recibir solicitudes de notificación desde el backend;
- procesar la información necesaria para cada comunicación;
- seleccionar o utilizar el canal correspondiente;
- consumir APIs externas de correo electrónico o WhatsApp;
- gestionar las respuestas de dichos servicios;
- devolver al backend el resultado de la operación.

---

### Docker

Docker se utiliza para ejecutar los distintos componentes del sistema en entornos aislados y reproducibles.

Permitirá contenerizar:
- frontend;
- backend;
- PostgreSQL;
- microservicio de compatibilidad;
- microservicio de notificaciones.

Sus principales beneficios dentro del proyecto son:
- mantener entornos consistentes;
- facilitar la instalación;
- aislar dependencias;
- reducir diferencias entre computadoras de desarrollo;
- facilitar el despliegue en el servidor de pruebas.

---

### Docker Compose

Docker Compose permite definir y ejecutar de forma conjunta los diferentes contenedores que conforman **Patitas Seguras**.

Se utiliza para configurar:
- servicios;
- imágenes;
- puertos;
- variables de entorno;
- volúmenes;
- redes internas.

Actualmente el entorno base contempla:

| Componente | Puerto local |
|------------|--------------|
| Frontend   | 8080         |
| Backend    | 8082         |
| PostgreSQL | 9432         |

Los puertos correspondientes a los microservicios serán definidos al momento de incorporarlos al entorno Docker.

---

### Git

Git se utiliza para el control de versiones del proyecto.

Permite:
- registrar cambios;
- trabajar mediante ramas;
- mantener historial;
- integrar el trabajo de los diferentes miembros del equipo;
- controlar las versiones del sistema.

La estrategia de ramas y versionado se encuentra documentada por separado en `05_estrategia_git.md`.

---

## 10. Separación de responsabilidades

Una de las decisiones principales de arquitectura consiste en evitar que un componente asuma responsabilidades que corresponden a otro.

```
Frontend
│
├── Presenta información
├── Captura datos
└── Consume API
        │
        ▼
Backend PHP
│
├── Valida
├── Aplica lógica de negocio
├── Coordina servicios
│
├──────────────► PostgreSQL
│                  └── Persistencia
│
├──────────────► Flask
│                  └── Compatibilidad
│
└──────────────► Go + Fiber
                   └── Notificaciones
                           │
                           ▼
                  Servicios externos
```

Por lo tanto:

- el frontend no accede directamente a la base de datos;
- el frontend no se comunica directamente con los microservicios;
- PostgreSQL no contiene lógica de presentación;
- JavaScript no concentra la lógica de negocio principal;
- el microservicio de compatibilidad no decide si una adopción se aprueba;
- el microservicio de notificaciones no decide cuándo corresponde realizar un seguimiento;
- Go + Fiber se encarga del procesamiento e integración necesaria para enviar la comunicación;
- PHP actúa como punto central de coordinación entre la interfaz, los datos y los microservicios.

---

## 11. Comunicación entre componentes

La comunicación entre los componentes se realizará principalmente mediante HTTP.

Cuando exista intercambio de información entre frontend, backend o microservicios, se utilizará principalmente el formato JSON.

```
Frontend
   │
   │ HTTP / JSON
   ▼
Backend PHP
   │
   ├────────────► PostgreSQL
   │
   ├────────────► Flask
   │               Compatibilidad
   │
   └────────────► Go + Fiber
                   Notificaciones
                       │
                       ▼
                Servicios externos
                Correo / WhatsApp
```

El backend principal actuará como coordinador de estas comunicaciones.

---

## 12. Entornos

**Patitas Seguras** contempla la separación entre entornos de desarrollo y producción.

### Desarrollo

El entorno de desarrollo permite realizar modificaciones y pruebas sin afectar los datos o servicios correspondientes a producción.

Se utiliza:
- Docker;
- PostgreSQL local;
- rama `dev`;
- servidor de pruebas.

### Producción

El entorno de producción deberá contener únicamente versiones consideradas estables.

Las modificaciones desarrolladas y probadas deberán atravesar el flujo definido por el equipo antes de llegar a producción.

La estrategia detallada de ramas, entornos y versiones será especificada en la documentación correspondiente a Git.

---

## 13. Infraestructura y despliegue

El proyecto utiliza Docker para mantener una infraestructura reproducible tanto en el entorno local como en el servidor de pruebas.

El despliegue en el servidor se realiza mediante acceso SSH.

De forma general:

```
Repositorio Git
      │
      ▼
Servidor de pruebas
      │
      ├── Variables de entorno
      ├── Docker
      └── Docker Compose
               │
               ▼
      Servicios Patitas Seguras
```

Esta estrategia permite levantar los diferentes componentes utilizando una configuración similar a la utilizada durante el desarrollo local.

---

## 14. Criterios arquitectónicos principales

La arquitectura de **Patitas Seguras** se basa en los siguientes criterios:

- separación de responsabilidades;
- modularidad;
- comunicación mediante APIs;
- persistencia centralizada en PostgreSQL;
- desacoplamiento de funcionalidades específicas mediante microservicios;
- utilización de servicios externos cuando una funcionalidad no pertenece al alcance de desarrollo interno;
- backend principal como coordinador de servicios;
- ambientes separados para desarrollo y producción;
- ejecución reproducible mediante Docker;
- control de versiones mediante Git;
- posibilidad de incorporar nuevos servicios sin modificar completamente la arquitectura existente.

---

## 15. Resumen de arquitectura

La arquitectura de **Patitas Seguras** utiliza un frontend web desarrollado con HTML, CSS, Bootstrap y JavaScript encargado de la interacción con los usuarios.

Las solicitudes son enviadas al backend principal desarrollado en PHP, que concentra la lógica de negocio, la validación y el acceso a la información almacenada en PostgreSQL.

Las funcionalidades específicas se encuentran separadas mediante microservicios.

Python con Flask se utiliza para el microservicio encargado de analizar la compatibilidad entre una mascota y un posible adoptante.

Go con Fiber se utiliza para el microservicio de notificaciones, encargado de procesar las solicitudes de comunicación y conectarse con proveedores externos de correo electrónico o WhatsApp.

Los servicios de autenticación, validación y verificación de login, y envío de emails son consumidos como servicios externos.

Toda la infraestructura se organiza mediante Docker, permitiendo disponer de entornos reproducibles tanto localmente como en el servidor de pruebas.

Esta arquitectura permite que cada tecnología tenga una responsabilidad específica y evita concentrar toda la lógica del sistema en una única aplicación.

---