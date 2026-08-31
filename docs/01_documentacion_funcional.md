# 🐾 Patitas Seguras  
## Sistema Integral de Gestión para Refugios de Animales

---

## 1. Descripción del proyecto

**Patitas Seguras** es una plataforma web destinada a refugios de animales, orientada principalmente a facilitar la gestión de perros y gatos que se encuentran bajo el cuidado de la organización.

El sistema permitirá centralizar la información de las mascotas alojadas por el refugio, incluyendo sus datos generales, antecedentes e información relacionada con su salud, como controles médicos, evolución del peso, vacunas, desparasitaciones y castración.

Las personas podrán consultar las mascotas disponibles y, mediante un usuario registrado, iniciar solicitudes de adopción o postularse como hogar de tránsito. Para estos procesos se recopilará información relacionada con las características de la vivienda, composición del hogar, presencia de otras mascotas, disponibilidad para el cuidado y demás datos necesarios para que el refugio pueda evaluar cada solicitud.

Como herramienta de apoyo al proceso de adopción, el sistema contará con un mecanismo de compatibilidad que permitirá analizar las características y necesidades de una mascota en relación con las condiciones ofrecidas por un posible adoptante. El resultado será orientativo y la decisión final continuará siendo responsabilidad del refugio.

La plataforma también contemplará el seguimiento de mascotas adoptadas o alojadas temporalmente en hogares de tránsito, incluyendo recordatorios y notificaciones cuando corresponda. Asimismo, permitirá gestionar inscripciones a actividades de voluntariado y registrar aportes y donaciones recibidas por el refugio.

Finalmente, contará con mecanismos de auditoría que permitan mantener trazabilidad sobre operaciones relevantes realizadas dentro del sistema.

De esta manera, **Patitas Seguras** busca centralizar información que habitualmente puede encontrarse distribuida en diferentes medios y proporcionar al refugio una herramienta organizada para administrar sus principales procesos.

---

## 2. Objetivo general

Desarrollar una plataforma web que permita centralizar y facilitar la gestión de mascotas, información sanitaria, usuarios, solicitudes de adopción, hogares de tránsito, seguimiento, voluntariado, donaciones y operaciones administrativas de un refugio de animales, proporcionando una herramienta organizada, segura y accesible para el desarrollo y seguimiento de sus principales actividades.

---

## 3. Objetivos específicos

- Implementar un sistema de usuarios con diferentes roles y permisos de acceso.  
- Registrar y administrar los datos personales, domicilios y medios de contacto de los usuarios.  
- Registrar y administrar las mascotas pertenecientes al refugio, incluyendo especie, raza, sexo, tamaño, fecha de nacimiento estimada, fotografía e información relevante sobre su historia.  
- Permitir la consulta de las mascotas disponibles y el acceso a la información detallada de cada una.  
- Mantener una ficha médica individual para cada mascota.  
- Registrar controles médicos periódicos y conservar la evolución histórica del peso de las mascotas.  
- Registrar información relacionada con vacunas, dosis, próximas dosis, desparasitaciones y castraciones.  
- Permitir que los usuarios registrados generen solicitudes de adopción y hogar de tránsito asociadas a las mascotas.  
- Gestionar los diferentes estados de las solicitudes durante su proceso de evaluación.  
- Implementar formularios que permitan recopilar la información necesaria para evaluar solicitudes de adopción y hogar de tránsito.  
- Registrar información sobre la vivienda, disponibilidad de espacio, patio y sus dimensiones, composición del hogar, presencia de menores, otras mascotas, experiencia previa y condiciones disponibles para el cuidado.  
- Registrar los compromisos asumidos por el solicitante durante el proceso de adopción.  
- Registrar la capacidad y condiciones ofrecidas por las personas que se postulen como hogar de tránsito, incluyendo la posibilidad de administrar medicación cuando sea necesario.  
- Incorporar un mecanismo de compatibilidad que permita comparar las características y necesidades de una mascota con las condiciones ofrecidas por un posible adoptante.  
- Permitir al personal autorizado consultar, evaluar y gestionar las solicitudes recibidas.  
- Registrar movimientos relevantes de las mascotas, como ingresos, adopciones, hogares de tránsito, reingresos o fallecimientos.  
- Facilitar el seguimiento posterior de las mascotas adoptadas o alojadas en hogares de tránsito.  
- Generar notificaciones y recordatorios relacionados con actividades de voluntariado y procesos de seguimiento de adopción o tránsito.  
- Registrar y gestionar aportes y donaciones realizados al refugio, contemplando donaciones monetarias y no monetarias.  
- Registrar métodos de pago y frecuencia de los aportes cuando corresponda.  
- Implementar mecanismos de auditoría que permitan identificar operaciones relevantes realizadas sobre la información del sistema.  
- Garantizar la integridad y consistencia de la información almacenada mediante reglas y restricciones en la base de datos.

---

## 4. Alcance del proyecto

El proyecto comprende el análisis, diseño, desarrollo, pruebas, implementación y documentación de una plataforma web para la gestión de los principales procesos de un refugio de animales.

### 4.1. Gestión de usuarios y seguridad

El sistema permitirá:

- Registrar y administrar usuarios.  
- Gestionar roles y permisos.  
- Utilizar mecanismos de autenticación para controlar el acceso a las funcionalidades correspondientes.  
- Registrar domicilios.  
- Registrar diferentes medios de contacto.  
- Establecer contactos principales.  
- Habilitar o deshabilitar usuarios cuando corresponda.  


---

### 4.2. Gestión de mascotas

El sistema permitirá:

- Registrar mascotas.  
- Modificar sus datos.  
- Consultar y listar mascotas.  
- Clasificarlas por especie y raza.  
- Registrar sexo y tamaño.  
- Registrar fecha de nacimiento estimada.  
- Incorporar fotografías.  
- Registrar información relevante sobre su comportamiento, antecedentes o historia.  
- Mantener información sobre su estado actual.

---

### 4.3. Gestión de salud

Cada mascota podrá disponer de una ficha médica mediante la cual se podrá:

- Registrar controles médicos.  
- Mantener un historial de evolución del peso.  
- Registrar vacunas y sus dosis.  
- Registrar próximas dosis cuando corresponda.  
- Registrar desparasitaciones.  
- Diferenciar tratamientos para parásitos internos, externos o ambos.  
- Registrar información relacionada con la castración.  
- Incorporar observaciones médicas.  

> **Importante:** La información tendrá carácter de registro y seguimiento y no reemplazará el diagnóstico o intervención de un profesional veterinario.

---

### 4.4. Catálogo de mascotas

La plataforma permitirá consultar las mascotas publicadas por el refugio.

Los usuarios podrán:

- Visualizar el catálogo de mascotas.  
- Consultar información general.  
- Acceder al detalle de una mascota.  
- Conocer sus principales características y antecedentes disponibles.

---

### 4.5. Gestión de solicitudes

La plataforma permitirá:

- Crear solicitudes asociadas a usuarios registrados y mascotas.  
- Identificar el tipo de solicitud.  
- Gestionar solicitudes de adopción.  
- Gestionar solicitudes de hogar de tránsito.  
- Consultar las solicitudes recibidas.  
- Administrar sus diferentes estados.  
- Evaluar y actualizar su situación durante el proceso correspondiente.

---

### 4.6. Gestión de adopciones

Para las solicitudes de adopción se podrá recopilar información relacionada con:

- Tipo y condición de vivienda.  
- Existencia de patio.  
- Metros cuadrados disponibles cuando corresponda.  
- Cantidad de integrantes del hogar.  
- Presencia de menores de edad.  
- Cantidad de horas que la mascota podría permanecer sola.  
- Motivo de adopción.  
- Presencia de otras mascotas.  
- Experiencia previa con animales.  
- Condiciones y recursos disponibles para el cuidado.  
- Información relacionada con la esterilización de las mascotas existentes cuando corresponda.  
- Condiciones especiales que el adoptante esté dispuesto a aceptar.  
- Aceptación del seguimiento posterior.  
- Compromisos asociados al proceso de adopción.  
- Observaciones adicionales.  

La información recopilada será utilizada por el refugio para evaluar la solicitud.

---

### 4.7. Evaluación de compatibilidad

El sistema contará con un mecanismo de compatibilidad destinado a colaborar con la evaluación de las solicitudes de adopción.

Este mecanismo permitirá analizar información relacionada con:

- Características de la mascota.  
- Necesidades de la mascota.  
- Características del hogar.  
- Disponibilidad del adoptante.  
- Presencia de otras mascotas.  
- Experiencia y condiciones disponibles para el cuidado.  

El resultado de compatibilidad será utilizado como herramienta de apoyo.

> La aprobación o rechazo definitivo de una solicitud continuará siendo responsabilidad del personal autorizado del refugio.

---

### 4.8. Gestión de hogares de tránsito

El sistema permitirá registrar y evaluar personas que se ofrecen como hogar de tránsito contemplando:

- Características y condiciones de la vivienda.  
- Existencia y dimensiones del patio.  
- Cantidad de integrantes del hogar.  
- Presencia de menores de edad.  
- Tiempo que una mascota podría permanecer sola.  
- Mascotas existentes en el hogar.  
- Capacidad máxima disponible para recibir mascotas.  
- Características de las mascotas que puede recibir.  
- Condiciones especiales aceptadas.  
- Posibilidad de administrar medicación.  
- Disponibilidad.  
- Observaciones adicionales.

---

### 4.9. Seguimiento de mascotas

El sistema contemplará mecanismos para realizar el seguimiento posterior de determinadas mascotas.

#### Adopciones

Las mascotas adoptadas podrán estar sujetas a controles posteriores que permitan al refugio conocer su situación y evolución.  
Las comunicaciones podrán realizarse principalmente mediante **WhatsApp**, de acuerdo con el seguimiento establecido por el refugio.

#### Hogares de tránsito

Las mascotas alojadas en hogares de tránsito deberán contar con un seguimiento periódico.  
Se contemplarán controles o visitas obligatorias inicialmente cada **3 meses** y posteriormente cada **6 meses**, según corresponda.  
Los recordatorios y comunicaciones podrán realizarse mediante **WhatsApp** y **correo electrónico**.

---

### 4.10. Voluntariado

La plataforma permitirá que las personas interesadas puedan inscribirse a actividades de voluntariado.  
Una vez realizada correctamente la inscripción, se podrá enviar una comunicación de confirmación mediante correo electrónico o WhatsApp.  
Las notificaciones de voluntariado estarán asociadas principalmente a la inscripción realizada y no requerirán mantener al voluntario registrado específicamente para realizar comunicaciones periódicas.

---

### 4.11. Gestión de donaciones

El sistema permitirá:

- Registrar aportes y donaciones recibidas por el refugio.  
- Clasificar los diferentes tipos de donación.  
- Contemplar donaciones monetarias y no monetarias.  
- Registrar el monto cuando corresponda.  
- Registrar el método de pago cuando corresponda.  
- Registrar la frecuencia del aporte cuando corresponda.  
- Mantener información sobre el estado de la donación.  
- Conservar información histórica de las operaciones registradas.  

> El sistema almacenará y gestionará la información correspondiente, pero **no funcionará como un procesador financiero propio**.

---

### 4.12. Movimientos de mascotas

El sistema permitirá mantener un registro histórico de acontecimientos relevantes relacionados con las mascotas.

Entre los movimientos contemplados se encuentran:

- Ingreso  
- Adopción  
- Tránsito  
- Reingreso  
- Fallecimiento  

Estos registros permitirán mantener información histórica sobre las diferentes situaciones atravesadas por una mascota.

---

### 4.13. Notificaciones

El sistema contemplará el envío de comunicaciones relacionadas principalmente con:

- Confirmaciones de inscripción a actividades de voluntariado.  
- Seguimiento de mascotas adoptadas.  
- Recordatorios de controles o visitas de mascotas alojadas en hogares de tránsito.  

Dependiendo del proceso, las comunicaciones podrán realizarse mediante:

- Correo electrónico  
- WhatsApp  

> El funcionamiento técnico y los flujos correspondientes al servicio de notificaciones serán detallados en la documentación de arquitectura y flujos del sistema.

---

### 4.14. Auditoría

El sistema contemplará mecanismos para registrar operaciones relevantes realizadas sobre la información.

Los registros de auditoría podrán incluir:

- Usuario responsable  
- Tabla afectada  
- Tipo de acción realizada  
- Fecha y hora  
- Información anterior a la modificación  
- Información posterior a la modificación  
- Dirección IP de origen cuando se encuentre disponible  

Esto permitirá mantener trazabilidad sobre las operaciones administrativas relevantes realizadas dentro de la plataforma.

---

### 4.15. Base de datos

La información será almacenada en una base de datos **PostgreSQL** diseñada específicamente para el proyecto.

El modelo contemplará:

- Claves primarias  
- Claves foráneas  
- Restricciones de obligatoriedad  
- Restricciones de unicidad  
- Reglas de integridad  
- Validaciones mediante `CHECK`  
- Índices  
- Relaciones entre las diferentes entidades  
- Estados e historiales  
- Auditoría de operaciones relevantes

---

## 5. Exclusiones del proyecto

Para mantener un alcance viable dentro del período establecido para el desarrollo, la primera versión de **Patitas Seguras** no contempla:

- Desarrollo de una aplicación móvil nativa para Android o iOS.  
- Sistema propio de mensajería o chat entre usuarios y el refugio.  
- Integración automática con redes sociales.  
- Publicación automática de mascotas en redes sociales.  
- Integración con sistemas externos de clínicas veterinarias.  
- Historia clínica veterinaria avanzada que pretenda reemplazar software profesional veterinario.  
- Diagnóstico médico o recomendaciones veterinarias automáticas.  
- Geolocalización o seguimiento GPS de mascotas.  
- Reconocimiento de mascotas mediante inteligencia artificial.  
- Procesamiento directo de pagos mediante una plataforma bancaria o pasarela de pagos desarrollada por el equipo.  
- Facturación electrónica.  
- Gestión contable completa del refugio.  
- Gestión de inventario de alimentos, medicamentos o insumos.  
- Gestión de camas, caniles o espacios físicos internos del refugio.  
- Gestión de turnos veterinarios.  
- Estadísticas o herramientas avanzadas de inteligencia de negocios.  
- Integraciones con organismos gubernamentales o registros externos.  
- Automatización de la decisión final sobre la aprobación o rechazo de solicitudes de adopción o tránsito.  
- Desarrollo interno de los servicios de autenticación, validación y verificación del inicio de sesión mediante correo electrónico, debido a que serán proporcionados como servicios externos.  
- Funcionalidades adicionales que no se encuentren contempladas dentro de los requerimientos definidos para la primera versión del sistema.

> Las funcionalidades excluidas podrán ser consideradas como posibles mejoras o ampliaciones en futuras versiones de **Patitas Seguras**, sin formar parte del compromiso de desarrollo de la versión inicial.

---

## 6. Actores y roles del sistema

**Patitas Seguras** diferencia las funcionalidades disponibles de acuerdo con el rol y los permisos asignados a cada usuario.

### 6.1. Usuario

Representa a las personas registradas que utilizan la plataforma para interactuar con el refugio.

Entre sus principales acciones se encuentran:

- Consultar mascotas.  
- Visualizar información detallada de las mascotas.  
- Realizar solicitudes de adopción.  
- Postularse como hogar de tránsito.  
- Completar los formularios correspondientes.  
- Realizar donaciones.  
- Acceder a las funcionalidades habilitadas para usuarios registrados.

---

### 6.2. Administrador

Representa al personal encargado de la gestión cotidiana del refugio mediante el sistema.

Entre sus principales responsabilidades se encuentran:

- Gestionar mascotas.  
- Actualizar información de las mascotas.  
- Administrar información médica.  
- Revisar solicitudes.  
- Gestionar procesos de adopción.  
- Gestionar hogares de tránsito.  
- Consultar formularios enviados.  
- Administrar estados de solicitudes y mascotas.  
- Gestionar información relacionada con donaciones.  
- Realizar tareas operativas vinculadas con el seguimiento de las mascotas.

---

### 6.3. Superadministrador

Representa el nivel de administración con mayores permisos dentro del sistema.

Además de las funcionalidades administrativas, podrá realizar acciones relacionadas con la configuración y administración general de la plataforma.

Entre sus principales responsabilidades se encuentran:

- Administrar configuraciones generales.  
- Gestionar aspectos estructurales del sistema.  
- Supervisar información y operaciones administrativas.  
- Acceder a funcionalidades restringidas de administración.  
- Consultar información relacionada con auditorías y trazabilidad.  
- Realizar modificaciones que requieran permisos superiores a los de un administrador convencional.

---

## 7. Reglas de negocio principales

### RN-01 — Roles y permisos

Las funcionalidades disponibles dependerán del rol y los permisos asignados al usuario.  
Un usuario no podrá realizar operaciones administrativas si no posee los permisos correspondientes.

---

### RN-02 — Usuarios habilitados

Los usuarios podrán ser habilitados o deshabilitados según corresponda.  
Un usuario deshabilitado no deberá disponer del mismo acceso operativo que un usuario habilitado.

---

### RN-03 — Solicitudes de usuarios registrados

Las solicitudes de adopción y hogar de tránsito deberán estar asociadas a un usuario registrado.

---

### RN-04 — Asociación entre solicitud y mascota

Toda solicitud de adopción o tránsito deberá identificar la mascota sobre la cual se realiza.

---

### RN-05 — Evaluación mediante formularios

Para evaluar una solicitud deberán recopilarse los datos necesarios mediante el formulario correspondiente al tipo de solicitud.

---

### RN-06 — Decisión sobre solicitudes

La aprobación o rechazo definitivo de una solicitud será responsabilidad del personal autorizado del refugio.  
El sistema podrá proporcionar información y herramientas de apoyo, pero no tomará automáticamente la decisión final.

---

### RN-07 — Compatibilidad

El resultado generado por el mecanismo de compatibilidad tendrá carácter orientativo.  
Deberá utilizarse como información complementaria durante la evaluación de una solicitud y no determinará automáticamente su aprobación o rechazo.

---

### RN-08 — Estado de las mascotas

Cada mascota deberá mantener un estado que represente su situación actual dentro del sistema.  
Los cambios relevantes deberán conservar la trazabilidad correspondiente.

---

### RN-09 — Movimientos de mascotas

Los acontecimientos relevantes relacionados con una mascota podrán registrarse como movimientos históricos.

Entre ellos se contemplan:

- Ingreso  
- Adopción  
- Tránsito  
- Reingreso  
- Fallecimiento

---

### RN-10 — Relación entre especie y raza

Cada raza deberá estar asociada a una especie válida.  
Las razas disponibles para una mascota dependerán de la especie seleccionada.

---

### RN-11 — Información médica

Todo registro médico deberá estar asociado a una mascota existente.  
La información médica almacenada tendrá carácter de registro y seguimiento y no constituirá un diagnóstico automático realizado por el sistema.

---

### RN-12 — Historial de peso

Los registros de peso deberán conservarse históricamente para permitir consultar la evolución de una mascota y no limitarse únicamente a su último peso registrado.

---

### RN-13 — Seguimiento de adopciones

Las mascotas adoptadas podrán estar sujetas a controles posteriores establecidos por el refugio.  
Las comunicaciones de seguimiento podrán realizarse mediante los canales definidos para este proceso.

---

### RN-14 — Seguimiento de hogares de tránsito

Las mascotas alojadas en hogares de tránsito deberán contar con seguimiento periódico.  
Se contemplarán controles o visitas obligatorias inicialmente cada **3 meses** y posteriormente cada **6 meses**, según corresponda.

---

### RN-15 — Notificaciones de voluntariado

Cuando una persona complete correctamente su inscripción a una actividad de voluntariado, podrá recibir una notificación de confirmación mediante los canales establecidos.  
La inscripción no implicará necesariamente mantener al voluntario registrado para el envío de comunicaciones periódicas.

---

### RN-16 — Donaciones

Las donaciones deberán registrar la información correspondiente según su tipo y modalidad.  
Los campos relacionados con monto, método de pago o frecuencia serán utilizados cuando correspondan al tipo de aporte realizado.  
El sistema gestionará el registro de las operaciones, pero no actuará como procesador financiero propio.

---

### RN-17 — Auditoría

Las operaciones consideradas relevantes para la administración deberán conservar información suficiente para permitir su posterior identificación y consulta.

---

### RN-18 — Integridad de los datos

La base de datos deberá aplicar las restricciones necesarias para mantener la integridad y consistencia de la información, evitando relaciones inválidas o datos incompatibles con las reglas definidas por el sistema.

---

## 8. Resumen funcional

**Patitas Seguras** centraliza en una única plataforma la información necesaria para administrar las mascotas de un refugio y los principales procesos relacionados con su cuidado.

La solución integra la gestión de mascotas y su información sanitaria con solicitudes de adopción y hogar de tránsito, evaluación de compatibilidad, seguimiento, voluntariado, donaciones, notificaciones y auditoría.

Las responsabilidades y funcionalidades se diferencian mediante roles y permisos, mientras que las decisiones finales relacionadas con adopciones y hogares de tránsito permanecen bajo responsabilidad del personal autorizado del refugio.

La plataforma busca proporcionar una base organizada y extensible que permita mejorar la administración cotidiana del refugio sin sustituir las decisiones humanas, veterinarias o administrativas que deben permanecer bajo responsabilidad de las personas correspondientes.

---
