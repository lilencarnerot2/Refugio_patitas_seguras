-- Seed de roles para Patitas Seguras
-- Ejecutar con: psql -h <host> -p <port> -U <user> -d <db> -f database/seeds/roles.sql

INSERT INTO roles (nombre_rol, descripcion) VALUES
('Usuario', 'Persona registrada que consulta mascotas, realiza solicitudes de adopción o tránsito y dona.'),
('Administrador', 'Personal del refugio que gestiona mascotas, solicitudes, salud y operaciones cotidianas.'),
('Superadministrador', 'Nivel máximo de administración: configuración general, auditoría y permisos superiores.')
ON CONFLICT (nombre_rol) DO NOTHING;