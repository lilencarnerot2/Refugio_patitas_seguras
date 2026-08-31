# 05 - Estrategia Git

## 1. Introducción

El proyecto **Patitas Seguras** utiliza Git como sistema de control de versiones y GitHub como repositorio remoto para organizar y centralizar el código desarrollado por el equipo.

La estrategia de trabajo busca:

* Permitir que varias integrantes trabajen simultáneamente.
* Evitar realizar modificaciones directamente sobre la versión estable.
* Mantener una rama destinada al desarrollo e integración.
* Probar los cambios antes de incorporarlos a la versión estable.
* Mantener un historial claro de modificaciones.
* Identificar versiones estables mediante tags.

La estrategia se mantiene deliberadamente sencilla para adaptarse al tamaño del equipo y a los requisitos establecidos para el proyecto.

---

# 2. Ramas principales

El proyecto utiliza dos ramas permanentes:

```text
main
dev
```

Las ramas `feature/*` se crean únicamente de manera temporal durante el desarrollo de una tarea.

El flujo general es:

```text
feature/*
    │
    ▼
   dev
    │
    ▼
  main
```

---

# 3. Rama `main`

```text
main
```

representa la versión estable del proyecto.

Esta rama no se utiliza para desarrollar funcionalidades directamente.

Su objetivo es contener únicamente código que:

* Ya fue desarrollado.
* Fue integrado previamente en `dev`.
* Fue probado.
* Se considera suficientemente estable para formar parte de una versión del sistema.

Por lo tanto, el equipo evita realizar modificaciones directas sobre `main`.

El flujo esperado es:

```text
Desarrollo
    ↓
feature/*
    ↓
dev
    ↓
Pruebas
    ↓
main
```

---

# 4. Rama `dev`

```text
dev
```

es la rama principal de desarrollo e integración.

En ella se reúnen las funcionalidades desarrolladas por las distintas integrantes antes de incorporarlas a `main`.

Esta rama permite comprobar que los cambios realizados en diferentes partes del sistema funcionan correctamente cuando se integran.

Por ejemplo:

```text
feature/mascotas
        │
        ▼
       dev

feature/donaciones
        │
        ▼
       dev

feature/solicitudes
        │
        ▼
       dev
```

Una vez integrados los cambios, el equipo puede realizar pruebas generales sobre `dev`.

---

# 5. Ramas `feature/*`

Las ramas `feature/*` son ramas temporales utilizadas para desarrollar tareas o funcionalidades específicas.

Por ejemplo:

```text
feature/catalogo-mascotas
feature/donaciones
feature/solicitudes
feature/formulario-adopcion
```

Estas ramas permiten trabajar sin modificar directamente `dev` o `main`.

El flujo comienza normalmente desde `dev`.

```bash
git switch dev
git pull
git switch -c feature/catalogo-mascotas
```

La integrante desarrolla la funcionalidad dentro de su rama y realiza los commits necesarios.

Una vez terminada y probada, la funcionalidad se integra a `dev`.

Después de una integración correcta, la rama `feature/*` puede eliminarse.

De esta forma las únicas ramas permanentes continúan siendo:

```text
main
dev
```

---

# 6. Flujo de trabajo

El flujo habitual para desarrollar una funcionalidad es el siguiente:

```text
1. Actualizar dev
        ↓
2. Crear feature/*
        ↓
3. Desarrollar
        ↓
4. Probar
        ↓
5. Realizar commits
        ↓
6. Subir la rama
        ↓
7. Integrar a dev
        ↓
8. Realizar pruebas en dev
        ↓
9. Eliminar feature/*
```

Cuando el conjunto de funcionalidades presentes en `dev` alcanza un estado estable:

```text
dev
 │
 │ versión validada
 ▼
main
 │
 ▼
tag
```

---

# 7. Inicio de una nueva tarea

Antes de comenzar una funcionalidad se debe actualizar la rama `dev`.

```bash
git switch dev
git pull origin dev
```

Luego se crea una nueva rama:

```bash
git switch -c feature/nombre-funcionalidad
```

Por ejemplo:

```bash
git switch -c feature/donaciones
```

Esto permite comenzar el desarrollo utilizando como base la versión más reciente de `dev`.

---

# 8. Desarrollo y commits

Durante el desarrollo se realizan commits para registrar los avances.

Ejemplo:

```bash
git add .
git commit -m "Agrega formulario de donaciones"
```

Los mensajes deben intentar describir claramente el cambio realizado.

Ejemplos:

```text
Agrega endpoint GET de mascotas

Corrige conexión PDO con PostgreSQL

Agrega renderizado del catálogo de mascotas

Agrega validación del formulario de adopción

Documenta arquitectura del sistema
```

Se busca evitar mensajes poco descriptivos como:

```text
cambios

cosas

prueba

arreglos
```

porque dificultan posteriormente comprender el historial del proyecto.

---

# 9. Publicación de una rama feature

Cuando sea necesario compartir o respaldar la rama en el repositorio remoto:

```bash
git push -u origin feature/donaciones
```

A partir de ese momento la rama también estará disponible en GitHub.

Esto permite que el resto del equipo pueda consultar los cambios realizados.

---

# 10. Integración con `dev`

Cuando una funcionalidad se encuentra terminada y probada, se integra en `dev`.

Conceptualmente:

```text
feature/donaciones
        │
        ▼
       dev
```

Antes de integrar se debe verificar que la rama `dev` esté actualizada.

El equipo debe resolver cualquier conflicto que pueda surgir durante la integración.

Una vez incorporada la funcionalidad, se realizan nuevamente pruebas desde `dev`.

Esto es importante porque una funcionalidad puede funcionar correctamente de manera aislada pero generar problemas al combinarse con cambios realizados por otras integrantes.

---

# 11. Eliminación de ramas feature

Una vez que una rama fue correctamente integrada en `dev`, deja de ser necesaria.

Puede eliminarse localmente:

```bash
git branch -d feature/donaciones
```

Y, si también se había subido al repositorio remoto:

```bash
git push origin --delete feature/donaciones
```

Esto evita acumular ramas correspondientes a tareas que ya fueron finalizadas.

---

# 12. Integración de `dev` a `main`

No todos los cambios realizados en `dev` deben pasar inmediatamente a `main`.

Primero se debe comprobar que el conjunto de funcionalidades integrado funciona correctamente.

El flujo es:

```text
dev
 │
 │ pruebas
 ▼
Versión estable
 │
 ▼
main
```

`main` debe representar siempre una versión estable del proyecto.

Por esta razón:

```text
feature/* → dev
```

es un proceso frecuente durante el desarrollo.

Mientras que:

```text
dev → main
```

se realiza cuando corresponde generar una versión estable.

---

# 13. Uso de tags

Los tags permiten identificar versiones específicas y estables del proyecto.

Una vez que una versión validada llega a `main`, se puede crear un tag.

Por ejemplo:

```text
v01.00.00
v01.01.00
v02.00.00
```

Esto permite identificar claramente qué estado del repositorio corresponde a una entrega o versión determinada.

Conceptualmente:

```text
main
 │
 ├── v01.00.00
 │
 ├── v01.01.00
 │
 └── v02.00.00
```

Los tags no reemplazan las ramas.

Una rama continúa evolucionando, mientras que un tag identifica un punto concreto del historial.

---

# 14. Desarrollo y producción

La estrategia Git también permite diferenciar los estados del sistema.

## Desarrollo

La rama:

```text
dev
```

representa el entorno de desarrollo e integración.

En ella pueden existir funcionalidades todavía en proceso de validación general.

## Versión estable

La rama:

```text
main
```

representa el código considerado estable.

Las versiones destinadas a una entrega estable se identifican mediante tags.

Esto permite mantener una separación clara entre:

```text
Código en desarrollo
        ↓
       dev
```

y:

```text
Código estable
        ↓
       main
        ↓
       tag
```

---

# 15. Servidor de pruebas

Patitas Seguras dispone de un servidor destinado a realizar pruebas del proyecto.

El acceso al servidor se realiza mediante SSH.

El servidor de pruebas trabaja con la rama:

```text
dev
```

De esta forma es posible probar la versión integrada del sistema sin utilizar directamente la versión estable de `main`.

El flujo general es:

```text
Computadoras del equipo
        │
        │ Git
        ▼
Repositorio remoto
        │
        │ dev
        ▼
Servidor de pruebas
        │
        ▼
Docker Compose
        │
        ├── Frontend
        ├── Backend
        └── PostgreSQL
```

En el servidor se puede actualizar el proyecto mediante:

```bash
git switch dev
git pull origin dev
```

y posteriormente reconstruir o levantar los servicios cuando sea necesario:

```bash
docker compose up -d --build
```

---

# 16. Variables de entorno y Git

El archivo:

```text
.env
```

contiene información de configuración que no debe almacenarse dentro del repositorio.

Por ejemplo:

```text
credenciales PostgreSQL
configuraciones del entorno
claves de servicios
```

Por esta razón debe encontrarse incluido dentro de:

```text
.gitignore
```

Cada entorno puede disponer de su propia configuración.

Por ejemplo:

```text
Entorno local
    ↓
.env local

Servidor de pruebas
    ↓
.env del servidor
```

De esta forma el mismo código puede ejecutarse en diferentes entornos sin almacenar credenciales directamente en Git.

---

# 17. Base de datos y entornos

El desarrollo y las pruebas no deben realizarse directamente sobre una base de datos destinada a producción.

Durante el desarrollo se utiliza una instancia PostgreSQL correspondiente al entorno de desarrollo.

Esto permite:

* Realizar pruebas.
* Insertar datos ficticios.
* Modificar información.
* Detectar errores.

sin afectar información perteneciente a otro entorno.

Los scripts SQL necesarios para reproducir la estructura se mantienen dentro del proyecto:

```text
database/
├── init/
└── seeds/
```

De esta manera el modelo de base de datos también queda versionado mediante Git.

---

# 18. Resolución de conflictos

Un conflicto puede producirse cuando dos integrantes modifican las mismas líneas o partes de un archivo.

Por ejemplo:

```text
Integrante A
    ↓
modifica mascotas.js

Integrante B
    ↓
modifica mascotas.js
```

Git puede requerir una resolución manual antes de completar la integración.

Antes de resolver un conflicto se debe revisar qué cambios realizó cada integrante y conservar la versión correcta o combinar ambas modificaciones cuando corresponda.

No se deben eliminar cambios de otra integrante sin verificar previamente su propósito.

---

# 19. Buenas prácticas de trabajo

Para mantener el repositorio organizado se establecen las siguientes prácticas:

* Actualizar `dev` antes de crear una nueva rama.
* Crear ramas `feature/*` para funcionalidades concretas.
* Evitar desarrollar directamente sobre `main`.
* Realizar commits descriptivos.
* Probar una funcionalidad antes de integrarla.
* Volver a probar después de integrarla en `dev`.
* Resolver conflictos revisando los cambios involucrados.
* Eliminar las ramas feature finalizadas.
* No subir `.env`.
* Mantener los scripts PostgreSQL versionados.
* Utilizar tags para identificar versiones estables.
* Mantener `main` como referencia de código estable.

---

# 20. Ejemplo completo

Supongamos que se debe desarrollar el módulo de donaciones.

Primero:

```bash
git switch dev
git pull origin dev
```

Se crea la rama:

```bash
git switch -c feature/donaciones
```

Se desarrolla y prueba la funcionalidad.

Luego:

```bash
git add .
git commit -m "Agrega formulario de donaciones"
git push -u origin feature/donaciones
```

Después se integra:

```text
feature/donaciones
        ↓
       dev
```

Se realizan pruebas generales.

Si la integración fue correcta:

```text
feature/donaciones
        ↓
    ELIMINADA
```

El equipo continúa incorporando funcionalidades a `dev`.

Cuando se alcanza una versión estable:

```text
dev
 ↓
main
 ↓
tag
```

---

# 21. Resumen de la estrategia

La estrategia Git de Patitas Seguras puede representarse de forma simplificada como:

```text
                 feature/mascotas ─────┐
                                       │
                 feature/adopcion ─────┤
                                       ▼
                                    dev
                                       │
                 feature/donaciones ───┘
                                       │
                                       │ Pruebas
                                       ▼
                                     main
                                       │
                                       ▼
                                  v01.00.00
```

Las ramas `feature/*` permiten desarrollar funcionalidades de manera aislada.

`dev` funciona como punto de integración y pruebas.

`main` mantiene el código estable.

Los tags permiten identificar versiones concretas del sistema.

De esta forma el equipo puede desarrollar Patitas Seguras de manera colaborativa manteniendo control sobre los cambios y reduciendo el riesgo de afectar una versión estable del proyecto.
