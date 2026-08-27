Patitas Seguras
Flujos del sistema

1. Introducción

Este documento describe los principales flujos de comunicación entre los componentes de Patitas Seguras.

El objetivo es mostrar de forma clara cómo se desplaza la información desde que una persona realiza una acción en la interfaz hasta que el sistema procesa, consulta, registra o devuelve una respuesta.

Los principales flujos documentados son:
Flujo Frontend → Backend → PostgreSQL.
Flujo del microservicio de compatibilidad.
Flujo del microservicio de notificaciones.


2. Flujo general Frontend → Backend → PostgreSQL
Este flujo representa la comunicación principal del sistema.
El frontend no accede directamente a la base de datos. Toda consulta o modificación debe pasar primero por el backend PHP.

2.1. Flujo conceptual
Usuario
   │
   ▼
Frontend
HTML + Bootstrap + JavaScript
   │
   │ HTTP Request
   ▼
Backend PHP
   │
   │ Validación y lógica
   ▼
PostgreSQL
   │
   │ Resultado
   ▼
Backend PHP
   │
   │ JSON Response
   ▼
Frontend
   │
   ▼
Usuario

2.2. Paso a paso
Paso 1 — Acción del usuario

El usuario realiza una acción desde la interfaz.

Por ejemplo:
consultar mascotas;
abrir el detalle de una mascota;
completar un formulario;
enviar una solicitud;
consultar información asociada.

Paso 2 — Solicitud desde el frontend
Cuando la acción requiere información del sistema, JavaScript realiza una solicitud HTTP al backend.

Por ejemplo:
GET /public/index.php?recurso=mascotas

o una solicitud con parámetros:

GET /public/index.php?recurso=mascotas&id=1

También podrán utilizarse otros métodos HTTP según la operación:
GET para consultar;
POST para registrar;
PUT o PATCH para modificar;
DELETE para eliminar o desactivar cuando corresponda.

Paso 3 — Recepción en el backend
El backend PHP recibe la solicitud.

A partir de la ruta, recurso o parámetros recibidos, determina qué operación debe realizar.

El backend puede:
validar parámetros;
validar datos obligatorios;
verificar formatos;
aplicar reglas de negocio;
controlar permisos;
determinar qué consulta o acción corresponde ejecutar.

Paso 4 — Acceso a PostgreSQL
Si la operación requiere información persistente, el backend se comunica con PostgreSQL mediante PDO.

Ejemplos de operaciones:
obtener todas las mascotas;
obtener una mascota por ID;
registrar una solicitud;
modificar un estado;
guardar información médica;
consultar datos de un usuario.

El frontend nunca ejecuta consultas SQL directamente.

Paso 5 — Respuesta de PostgreSQL
PostgreSQL procesa la consulta y devuelve el resultado al backend.

Puede tratarse de:
registros encontrados;
confirmación de inserción;
confirmación de actualización;
ausencia de resultados;
error de integridad o consulta.
Paso 6 — Procesamiento de la respuesta

El backend interpreta la respuesta recibida desde PostgreSQL.

Luego prepara una respuesta adecuada para el frontend.

Ejemplo conceptual:

{
  "success": true,
  "data": [
    {
      "id": 1,
      "nombre": "Luna",
      "especie": "Perro",
      "raza": "Mestizo"
    }
  ]
}

En caso de error, también podrá devolver un código HTTP y un mensaje correspondiente.

Paso 7 — Respuesta al frontend
El backend envía la respuesta en formato JSON.

JavaScript procesa esa respuesta.
Según el resultado puede:
renderizar información;
mostrar una mascota;
actualizar una lista;
mostrar un mensaje de éxito;
mostrar un mensaje de error.
Paso 8 — Presentación al usuario

Finalmente, el frontend actualiza la interfaz para mostrar el resultado de la operación.


3. Ejemplo: consulta del catálogo de mascotas
Este es uno de los flujos ya implementados en el proyecto.

Usuario abre catálogo
        │
        ▼
mascotas.js
        │
        │ GET
        ▼
Backend PHP
/public/index.php?recurso=mascotas
        │
        ▼
Repository de mascotas
        │
        ▼
PostgreSQL
        │
        │ SELECT + JOIN
        ▼
Backend PHP
        │
        │ JSON
        ▼
mascotas.js
        │
        ▼
Renderizado del catálogo

El backend obtiene la información desde las tablas correspondientes y puede relacionar datos como:
mascota;
especie;
raza.

El frontend recibe la respuesta ya procesada y se limita a representarla visualmente.


4. Ejemplo: consulta de una mascota por ID
El flujo para consultar una mascota específica es similar.

Usuario selecciona mascota
        │
        ▼
Frontend
        │
        │ GET con id
        ▼
Backend PHP
        │
        ▼
Validación del ID
        │
        ▼
Consulta PostgreSQL
        │
        ▼
Resultado
        │
        ▼
JSON
        │
        ▼
Frontend
        │
        ▼
Detalle de mascota

El backend debe validar que el ID recibido sea válido.
Si la mascota existe, devuelve su información.
Si no existe, podrá responder con un código HTTP 404.
Si el parámetro es inválido, podrá responder con 400.


5. Flujo de registro o modificación de información
Para operaciones que modifican información, el flujo general es el siguiente:

Usuario completa formulario
        │
        ▼
Frontend
        │
        │ POST / PUT / PATCH
        ▼
Backend PHP
        │
        ├── Valida datos
        ├── Aplica reglas de negocio
        └── Verifica permisos
                │
                ▼
            PostgreSQL
                │
                ▼
        Inserción / actualización
                │
                ▼
            Backend
                │
                ▼
          Respuesta JSON
                │
                ▼
            Frontend

El backend debe encargarse de las validaciones principales antes de intentar persistir la información.


6. Flujo del microservicio de compatibilidad
El análisis de compatibilidad entre una mascota y un posible adoptante se realiza mediante un microservicio desarrollado en Python + Flask.
El frontend no se comunica directamente con este microservicio.
La comunicación se realiza a través del backend PHP.

6.1. Flujo conceptual
Frontend
   │
   ▼
Backend PHP
   │
   ├── Obtiene datos de la solicitud
   ├── Obtiene datos de la mascota
   │
   ▼
PostgreSQL
   │
   ▼
Backend PHP
   │
   │ HTTP / JSON
   ▼
Microservicio Flask
   │
   ├── Analiza información
   ├── Aplica reglas
   └── Calcula compatibilidad
   │
   ▼
Resultado
   │
   ▼
Backend PHP
   │
   ▼
Frontend

6.2. Paso a paso

Paso 1 — Inicio de evaluación
Una solicitud de adopción contiene información sobre:
usuario;
mascota;
condiciones de vivienda;
patio;
composición del hogar;
otras mascotas;
disponibilidad;
experiencia;
condiciones de cuidado.

Paso 2 — Obtención de datos
El backend obtiene desde PostgreSQL la información necesaria.
Esto puede incluir:
características de la mascota;
información del formulario de adopción;
condiciones declaradas por el solicitante.

Paso 3 — Preparación de la solicitud
El backend organiza los datos que necesita el microservicio.

Ejemplo conceptual:
{
  "mascota": {
    "tamano": "grande",
    "especie": "perro",
    "necesita_patio": true
  },
  "adoptante": {
    "tiene_patio": true,
    "metros_cuadrados": 40,
    "horas_sola": 3
  }
}

Paso 4 — Envío al microservicio
El backend realiza una solicitud HTTP al microservicio Flask.

Backend PHP
      │
      │ POST + JSON
      ▼
Python + Flask

Paso 5 — Procesamiento
El microservicio analiza los datos recibidos y aplica las reglas de compatibilidad definidas.
Puede considerar factores como:
tamaño de la mascota;
espacio disponible;
presencia de patio;
cantidad de horas sola;
otras mascotas;
experiencia previa;
condiciones especiales.

Paso 6 — Resultado
El microservicio devuelve un resultado de compatibilidad.

Ejemplo conceptual:
{
  "success": true,
  "compatibilidad": 82,
  "resultado": "alta"
}

La estructura definitiva dependerá de la implementación.

Paso 7 — Recepción en el backend
El backend recibe el resultado y puede:
devolverlo al frontend;
utilizarlo como información complementaria;
almacenarlo si el diseño final así lo requiere.

Paso 8 — Evaluación humana
El resultado no determina automáticamente la aprobación.
El personal del refugio utiliza la compatibilidad como apoyo dentro del proceso de evaluación.


7. Consideraciones del flujo de compatibilidad
El microservicio:
no accede directamente al frontend;
no aprueba solicitudes;
no reemplaza la evaluación humana;
no debe tomar decisiones administrativas;
recibe únicamente la información necesaria para el análisis;
devuelve un resultado al backend principal.

El backend continúa siendo el componente encargado de coordinar el proceso.


8. Flujo del microservicio de notificaciones
Las notificaciones serán gestionadas mediante un microservicio desarrollado en Go + Fiber.

Este microservicio se encargará de procesar las solicitudes de comunicación y conectarse con proveedores externos para realizar el envío efectivo.

8.1. Flujo conceptual
Evento del sistema
      │
      ▼
Backend PHP
      │
      ├── Determina si corresponde notificar
      ├── Identifica destinatario
      ├── Define tipo de mensaje
      └── Define canal
      │
      │ HTTP / JSON
      ▼
Microservicio Go + Fiber
      │
      ├── Valida solicitud
      ├── Prepara mensaje
      └── Consume API externa
      │
      ▼
Proveedor externo
Correo / WhatsApp
      │
      ▼
Resultado del envío
      │
      ▼
Go + Fiber
      │
      ▼
Backend PHP


9. Inicio del flujo de notificación
El proceso comienza cuando ocurre un evento que requiere una comunicación.

Ejemplos:
inscripción a voluntariado;
control de seguimiento de adopción;
visita o control de hogar de tránsito.

El backend es quien determina que corresponde generar la notificación.


10. Preparación de la notificación
El backend reúne la información necesaria.

Por ejemplo:
destinatario;
medio de contacto;
tipo de notificación;
contenido necesario;
identificador relacionado;
canal de envío.

Ejemplo conceptual:
{
  "tipo": "seguimiento_transito",
  "canal": "whatsapp",
  "destinatario": "telefono_usuario",
  "datos": {
    "mascota": "Luna",
    "fecha_control": "2026-11-27"
  }
}


11. Comunicación con Go + Fiber
El backend envía la solicitud al microservicio de notificaciones.

PHP
 │
 │ POST /notificaciones
 ▼
Go + Fiber

El microservicio recibe y valida la información.


12. Selección del canal
Según el tipo de comunicación, el microservicio utilizará el canal correspondiente.

Los canales previstos son:
correo electrónico;
WhatsApp.
El tipo de canal dependerá del proceso.


13. Envío mediante proveedor externo
Go + Fiber se comunica con el servicio externo correspondiente.

Go + Fiber
    │
    ├────────► Proveedor de correo
    │
    └────────► Proveedor de WhatsApp

El proveedor externo realiza el envío efectivo.


14. Resultado del envío
El proveedor devuelve un resultado al microservicio.

El resultado puede indicar:

envío correcto;
destinatario inválido;
error del proveedor;
error de autenticación;
error de comunicación.

Go + Fiber interpreta ese resultado y lo devuelve al backend.


15. Notificaciones de voluntariado
El flujo de voluntariado será principalmente inmediato.

Persona completa formulario
        │
        ▼
Frontend
        │
        ▼
Backend PHP
        │
        ├── Valida inscripción
        └── Solicita notificación
                 │
                 ▼
            Go + Fiber
                 │
                 ▼
        Correo o WhatsApp

La comunicación tendrá como objetivo confirmar la inscripción realizada.

No se requiere necesariamente almacenar al voluntario para realizar comunicaciones periódicas.


16. Seguimiento de adopciones
Las mascotas adoptadas podrán contar con seguimientos posteriores.
El backend deberá identificar cuándo corresponde generar un aviso.

De forma conceptual:

Seguimiento pendiente
        │
        ▼
Backend PHP
        │
        ▼
Microservicio Go
        │
        ▼
WhatsApp
        │
        ▼
Adoptante

La frecuencia definitiva de este seguimiento dependerá de las reglas establecidas por el refugio.


17. Seguimiento de hogares de tránsito
Los hogares de tránsito deberán contar con controles periódicos.

Se contemplan:
controles inicialmente cada 3 meses;
controles posteriores cada 6 meses.

El flujo conceptual es:

Fecha de control
      │
      ▼
Backend PHP
      │
      ├── Identifica hogar
      ├── Identifica mascota
      └── Genera solicitud
              │
              ▼
        Go + Fiber
              │
        ┌─────┴─────┐
        ▼           ▼
   WhatsApp       Correo


18. Manejo de errores
Todos los flujos deben contemplar posibles errores.

Frontend
Debe poder manejar:
fallo de conexión;
respuesta inválida;
recurso inexistente;
error del servidor.

Backend
Debe poder manejar:
datos inválidos;
permisos insuficientes;
registros inexistentes;
errores de base de datos;
errores de microservicios;
errores inesperados.

Microservicio de compatibilidad
Debe contemplar:
falta de datos;
estructura JSON inválida;
errores de procesamiento;
indisponibilidad temporal.

Microservicio de notificaciones
Debe contemplar:
datos de contacto inválidos;
canal no soportado;
error del proveedor;
fallo de conexión;
credenciales inválidas;
tiempo de espera agotado.


19. Principio de coordinación
En los distintos flujos se mantiene una regla arquitectónica central:
El backend PHP actúa como coordinador principal entre el frontend, PostgreSQL y los microservicios.

Esto significa que:
el frontend no consulta directamente PostgreSQL;
el frontend no accede directamente a Flask;
el frontend no accede directamente a Go + Fiber;
los microservicios no sustituyen al backend principal;
PostgreSQL mantiene la persistencia;
Flask resuelve compatibilidad;
Go + Fiber resuelve notificaciones e integraciones de comunicación.


20. Resumen de flujos
Los principales flujos de Patitas Seguras pueden resumirse de la siguiente forma:

CONSULTAS Y OPERACIONES

Frontend
   │
   ▼
Backend PHP
   │
   ▼
PostgreSQL
   │
   ▼
Backend
   │
   ▼
Frontend
COMPATIBILIDAD

Frontend
   │
   ▼
Backend PHP
   │
   ├────► PostgreSQL
   │
   ▼
Python + Flask
   │
   ▼
Backend PHP
   │
   ▼
Frontend
NOTIFICACIONES

Evento
  │
  ▼
Backend PHP
  │
  ▼
Go + Fiber
  │
  ▼
Proveedor externo
  │
  ├── Correo
  └── WhatsApp

Estos flujos mantienen una separación clara de responsabilidades y permiten que cada componente del sistema se encargue únicamente de las funciones para las cuales fue diseñado.