# 🐾 Patitas Seguras

Plataforma web para la gestión de un refugio de mascotas.

Permite administrar mascotas, usuarios, solicitudes de adopción y tránsito, fichas médicas, donaciones, seguimientos, auditorías y configuraciones del refugio.

---

## Tecnologías

- HTML
- CSS
- Bootstrap
- JavaScript
- PHP 8.2
- PostgreSQL
- Python + Flask
- Go + Fiber
- Docker
- Docker Compose

---

## Requisitos

Para ejecutar el proyecto es necesario tener instalado:

- Git
- Docker
- Docker Compose

No es necesario instalar PHP, PostgreSQL ni las demás tecnologías de forma local, ya que el proyecto se ejecuta mediante contenedores Docker.

---

## Clonar el proyecto

```bash
git clone URL_DEL_REPOSITORIO
cd PatitasSeguras
```

Reemplazar `URL_DEL_REPOSITORIO` por la URL correspondiente del repositorio.

---

## Variables de entorno

Crear un archivo `.env` en la raíz del proyecto.

Ejemplo:

```env
# Le indica al sistema que está corriendo en una pc de prueba o en tu propia pc (activa herramientas de ayuda, errores más descriptivos y usa db local sin afectar datos reales) 
APP_ENV=development 

# BASE DE DATOS
DB_HOST=
DB_PORT=
DB_NAME=
DB_USER=
DB_PASSWORD=

# PUERTOS LOCALES
FRONTEND_PORT=
BACKEND_PORT=
COMPATIBILITY_PORT=
NOTIFICATION_PORT=

# SERVICIOS INTERNOS
COMPATIBILITY_SERVICE_URL=http://compatibility-service:port
NOTIFICATION_SERVICE_URL=http://notification-service:port

BACKEND_INTERNAL_URL=http://backend:port
FRONTEND_URL=http://localhost:port

# SERVICIO EXTERNO PP2
PP2_API_URL=
PP2_API_KEY=

# SERVICIO DE MAIL
MAIL_API_URL=
MAIL_API_KEY=

PROYECTO_ID=

## SERVICIO INTERNO DE NOTIFICACIONES (WSP)
TWILIO_ACCOUNT_SID=
TWILIO_AUTH_TOKEN=
TWILIO_WHATSAPP_FROM=
TWILIO_CONTENT_SID=
```

Las credenciales reales deben configurarse según el entorno.

> El archivo `.env` contiene información sensible y no debe subirse al repositorio.

---

## Levantar el proyecto

Desde la raíz:

```bash
docker compose up -d --build
```

Este comando construye las imágenes y levanta los servicios definidos en `docker-compose.yml`.

---

## Verificar contenedores

```bash
docker compose ps
```

Los servicios deben encontrarse iniciados correctamente.

---

## Acceso local

### Frontend

```text
http://localhost:port
```

### Backend

```text
http://localhost:port
```

Ejemplo de endpoint:

```text
http://localhost:port/public/index.php?recurso=mascotas
```

### PostgreSQL

Desde la máquina local:

```text
localhost:
```

Desde los contenedores:

```text
Host: postgres
Puerto: 
```

---

## Logs

Todos los servicios:

```bash
docker compose logs -f
```

Frontend:

```bash
docker compose logs -f frontend
```

Backend:

```bash
docker compose logs -f backend
```

Base de datos:

```bash
docker compose logs -f postgres
```

---

## Reconstruir servicios

Frontend:

```bash
docker compose up -d --build frontend
```

Backend:

```bash
docker compose up -d --build backend
```

Todo el proyecto:

```bash
docker compose up -d --build
```

---

## Detener el proyecto

```bash
docker compose down
```

Para volver a iniciarlo:

```bash
docker compose up -d
```

---

## Actualizar el proyecto

Obtener los últimos cambios:

```bash
git pull
```

Luego reconstruir los contenedores:

```bash
docker compose up -d --build
```

---

## Estructura principal

```text
PatitasSeguras/
├── frontend/
│   ├── public/
│   ├── admin/
│   └── assets/
│
├── backend/
│   ├── public/
│   └── src/
│       ├── modules/
│       ├── routes/
│       └── utils/
│
├── microservices/
├── docker-compose.yml
├── .env
└── README.md
```

---

## Roles

El sistema posee tres roles:

### Usuario
Puede acceder al catálogo, realizar solicitudes de adopción o tránsito, realizar donaciones y gestionar su información personal.

### Administrador
Puede gestionar las operaciones habituales del refugio, mascotas, solicitudes, salud, donaciones y usuarios comunes.

### Super Administrador
Posee los permisos del Administrador y además puede gestionar administradores, auditorías y configuración general del sistema.

---

## Servicios externos

Patitas Seguras utiliza servicios externos para:

- Registro y autenticación de usuarios.
- Verificación de cuentas.
- Recuperación de contraseña.
- Envío de correos electrónicos.

También utiliza servicios internos para funcionalidades complementarias como compatibilidad y notificaciones.

---

## Ramas Git

Ramas principales:

```text
main
dev
```

Las funcionalidades se desarrollan en ramas:

```text
feature/*
```

Flujo general:

```text
feature/* → dev → main
```

`dev` corresponde al entorno de pruebas y `main` a versiones estables.

---

## Producción

En producción se deben utilizar:

- Variables de entorno propias.
- Base de datos independiente de desarrollo.
- Credenciales seguras.
- URLs correspondientes al servidor.
- Servicios externos configurados para producción.

Nunca deben utilizarse las credenciales del entorno local ni subirse archivos `.env` al repositorio.

---

## Proyecto académico

**Patitas Seguras**

Proyecto desarrollado para la materia **Prácticas Profesionalizantes II**.
```