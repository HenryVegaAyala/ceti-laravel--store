# 🛒 Laravel Tienda — Backend API

API REST construida con **Laravel 12** + **Laravel Passport** (OAuth2), servida mediante **Docker** (PHP 8.5-FPM + Nginx + MySQL 8).

---

## 📋 Requisitos previos

Asegúrate de tener instalado en tu máquina:

| Herramienta | Versión mínima |
|-------------|----------------|
| [Docker Desktop](https://www.docker.com/products/docker-desktop/) | 24+ |
| [Docker Compose](https://docs.docker.com/compose/) | v2+ (incluido en Docker Desktop) |
| [Git](https://git-scm.com/) | Cualquier versión reciente |

> **No necesitas** PHP, Composer ni MySQL instalados localmente. Todo corre dentro de Docker.

---

## 🚀 Instalación paso a paso

### 1. Clona el repositorio

```bash
git clone <URL_DEL_REPOSITORIO>
cd laravel-tienda/backend
```

---

### 2. Crea el archivo de variables de entorno

```bash
copy store\.env.example store\.env
```

Edita `store/.env` y configura las siguientes variables para que coincidan con Docker:

```env
APP_NAME=LaravelTienda
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8500

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=store_db
DB_USERNAME=admin
DB_PASSWORD=admin
```

> ⚠️ El `DB_HOST` debe ser `mysql` (nombre del servicio en Docker Compose), **no** `127.0.0.1`.

---

### 3. Levanta los contenedores Docker

```bash
docker compose up -d --build
```

Esto levantará tres contenedores:

| Contenedor | Descripción | Puerto local |
|------------|-------------|--------------|
| `php` | PHP 8.5-FPM + Laravel | — |
| `mysql` | MySQL 8.0 | `33060` |
| `nginx` | Servidor web Nginx | `8500` |

Verifica que los contenedores estén corriendo:

```bash
docker compose ps
```

---

### 4. Instala las dependencias de PHP (Composer)

```bash
docker exec php composer install --working-dir=/var/www
```

---

### 5. Genera la clave de la aplicación

```bash
docker exec php php /var/www/artisan key:generate
```

---

### 6. Ejecuta las migraciones

```bash
docker exec php php /var/www/artisan migrate
```

---


### 8. Genera las llaves de encriptación de Passport

```bash
docker exec php php /var/www/artisan passport:keys --force
```

> ⚠️ Este paso es **obligatorio**. Sin las llaves, Passport no puede firmar ni verificar los tokens de acceso.

---

### 9. Crea el cliente de acceso personal

```bash
docker exec php php /var/www/artisan passport:client --personal
```

Cuando te pida el nombre, puedes escribir cualquiera, por ejemplo: `Laravel Personal Access Client`

> ⚠️ Este paso es **obligatorio**. Sin este cliente, el login retornará `Personal access client not found`.

---

### 10. (Opcional) Ejecuta los seeders

```bash
docker exec php php /var/www/artisan db:seed
```

---

## ✅ Verificar que funciona

Abre tu navegador o cliente HTTP (Postman, Insomnia, etc.) y accede a:

```
http://localhost:8500
```

---

## 📡 Endpoints disponibles

Base URL: `http://localhost:8500/api`

### 🔓 Públicos (no requieren autenticación)

| Método | Endpoint | Descripción | Body (JSON) |
|--------|----------|-------------|-------------|
| `GET/POST` | `/api/register` | Registrar nuevo usuario | `name`, `email`, `password` |
| `GET/POST` | `/api/login` | Iniciar sesión | `email`, `password` |

### Ejemplo — Registro de usuario

```http
POST http://localhost:8500/api/register
Content-Type: application/json

{
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "password": "password123"
}
```

### Ejemplo — Login

```http
POST http://localhost:8500/api/login
Content-Type: application/json

{
    "email": "juan@example.com",
    "password": "password123"
}
```

Respuesta exitosa:
```json
{
    "message": "Usuario autenticado correctamente",
    "token": "eyJ0eXAiOiJKV1Qi..."
}
```

### 🔒 Protegidos (requieren token Bearer)

Agrega el token en el header de cada petición:

```http
Authorization: Bearer <token_obtenido_en_login>
```

---

## 🗄️ Acceso a la base de datos

Puedes conectarte a MySQL desde cualquier cliente (TablePlus, DBeaver, MySQL Workbench):

| Campo | Valor |
|-------|-------|
| Host | `127.0.0.1` |
| Puerto | `33060` |
| Base de datos | `store_db` |
| Usuario | `admin` |
| Contraseña | `admin` |

---

## 🛠️ Comandos útiles

```bash
# Instalar Passport (crear clientes OAuth en la BD)
docker exec php php /var/www/artisan passport:install --force

# Regenerar las llaves de encriptación de Passport
docker exec php php /var/www/artisan passport:keys

# Crear solo el cliente de acceso personal
docker exec php php /var/www/artisan passport:client --personal

# Crear solo el cliente Password Grant
docker exec php php /var/www/artisan passport:client --password

# Ver los clientes OAuth registrados
docker exec php php /var/www/artisan passport:client --list

# Ver logs de Laravel en tiempo real
docker exec php tail -f /var/www/storage/logs/laravel.log

# Acceder al contenedor PHP
docker exec -it php bash

# Limpiar caché de configuración
docker exec php php /var/www/artisan config:clear
docker exec php php /var/www/artisan cache:clear
docker exec php php /var/www/artisan route:clear

# Ver todas las rutas registradas
docker exec php php /var/www/artisan route:list

# Detener todos los contenedores
docker compose down

# Detener y eliminar volúmenes (borra la BD)
docker compose down -v
```

---

## 📁 Estructura del proyecto

```
backend/
├── docker/
│   └── nginx/
│       └── nginx.conf       # Configuración de Nginx
├── store/                   # Código fuente Laravel
│   ├── app/
│   │   ├── Http/
│   │   │   └── Api/
│   │   │       └── Login/
│   │   │           ├── LoginController.php
│   │   │           └── RegisterController.php
│   │   ├── Modules/
│   │   │   ├── Base/        # Controlador base (ApiController)
│   │   │   └── User/        # Modelo User
│   │   └── Traits/          # JsonResponseTrait
│   ├── database/
│   │   └── migrations/      # Migraciones (users, oauth, cache, jobs)
│   ├── routes/
│   │   └── api.php          # Definición de rutas API
│   └── .env                 # Variables de entorno (no se sube al repo)
├── docker-compose.yml       # Orquestación de contenedores
└── Dockerfile               # Imagen PHP 8.5-FPM
```

---

## ⚙️ Stack tecnológico

- **PHP** 8.5
- **Laravel** 12
- **Laravel Passport** 13 (OAuth2)
- **MySQL** 8.0
- **Nginx** Alpine
- **Docker** + Docker Compose

---

## 🐛 Solución de problemas comunes

### `Unauthenticated.` en rutas públicas
Verifica que el middleware `auth:api` no esté aplicado globalmente. Las rutas públicas deben declararse fuera de cualquier grupo `middleware('auth:api')` en `routes/api.php`.

### `Personal access client not found`
Ejecuta nuevamente:
```bash
docker exec php php /var/www/artisan passport:install --force
```

### `SQLSTATE: Connection refused`
Asegúrate de que el contenedor `mysql` esté corriendo y que en `.env` el `DB_HOST=mysql` (no `localhost`).

### Permisos de storage
```bash
docker exec php chmod -R 775 /var/www/storage /var/www/bootstrap/cache
docker exec php chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
```

