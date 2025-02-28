# API de Usuarios y Actividades - Laravel

Esta API permite gestionar usuarios, actividades y autenticación usando **JWT (JSON Web Token)** en **Laravel**.

## ✨ Características
- CRUD de **usuarios** (`UserController`)
- CRUD de **actividades** (`ActivityController`)
- **Autenticación con JWT** (`AuthController`)
- Búsqueda de usuarios con filtros avanzados (`name`, `email`, `phone`, `created_at`)
- Manejo de **errores con try-catch**
- **Paginación** en respuestas

## Documentación
Importar el archivo BODYTECH.postman_collection.json (se encuentra dentro del proyecto) a [Postman](https://www.postman.com/downloads/) para visualizar y testear los endpoints creados en local, recuerde tener configurado el ambiente del proyecto y el proyecto corriendo.

---

## 🛠 Instalación y Configuración

### 1️⃣ Clonar el repositorio
```sh
git clone https://github.com/andyBlack13/bodytech_backend.git
cd bodytech_backend
```

### 2️⃣ Instalar dependencias
```sh
composer install
```

### 3️⃣ Configurar variables de entorno
```sh
cp .env.example .env
```
Edita el archivo `.env` y configura la base de datos:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bodytech_db
DB_USERNAME=root
DB_PASSWORD=
```
Generar clave de aplicación:
```sh
php artisan key:generate
```

### 4️⃣ Migraciones y Seeders
```sh
php artisan migrate --seed
```

### 5️⃣ Generar clave JWT
```sh
php artisan jwt:secret
```

### 6️⃣ Iniciar el servidor
```sh
php artisan serve
```

---

## 🔗 Endpoints de la API

### 🔐 Autenticación (AuthController)
#### Registro de Usuario
**POST** `/api/register`
```json
{
    "name": "Andrea Camargo",
    "email": "andyy@example.com",
    "phone": "123456789",
    "password": "123456"
}
```

#### Login
**POST** `/api/login`
```json
{
    "email": "andyy@example.com",
    "password": "123456"
}
```

#### Logout
**POST** `/api/logout`

---

### 👤 Usuarios (UserController)
#### Listar Usuarios con Filtros
**GET** `/api/users?name=Andrea&created_at_from=2024-01-01&created_at_to=2024-06-30`

#### Obtener Usuario por ID
**GET** `/api/users/{id}`

#### Actualizar Usuario
**PUT** `/api/users/{id}`
```json
{
    "name": "Andrea Camargo Updated",
    "email": "andyy@exampleupdated.com"
}
```

#### Eliminar Usuario
**DELETE** `/api/users/{id}`

---

### 📄 Actividades (ActivityController)
#### Crear Actividad
**POST** `/api/activities`
```json
{
    "user_id": 1,
    "action": "Inicio de sesión"
}
```

#### Obtener Actividad por ID
**GET** `/api/activities/{id}`

#### Eliminar Actividad
**DELETE** `/api/activities/{id}`

---

## 🔧 Errores y Manejo de Excepciones
Formato de error (500 - Error Interno):
```json
{
    "success": false,
    "message": "Error al obtener usuarios",
    "error": "Mensaje del error"
}
```

Formato de validación fallida (422 - Unprocessable Entity):
```json
{
    "message": "Los datos proporcionados no son válidos",
    "errors": {
        "email": ["El email ya está en uso"]
    }
}
```

---

## 📃 Notas Finales
- **JWT**: Todas las rutas protegidas requieren autenticación con `Authorization: Bearer <TOKEN>`
- **Filtros avanzados** en `/api/users` para búsqueda flexible