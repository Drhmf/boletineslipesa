# Boletines LIPESA

Sistema web en PHP para gestionar boletines y asistencia estudiantil.

## Requisitos

- PHP \>= 8.0
- [Composer](https://getcomposer.org/)
- MySQL u otra base de datos compatible

## Instalación

1. Instala las dependencias del proyecto:

   ```bash
   composer install
   ```

2. Copia el archivo `.env.example` a `.env` y completa las credenciales de conexión y claves requeridas:

   ```bash
   cp .env.example .env
   ```

   Edita `.env` y define valores para `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`, `JWT_SECRET`, `CAPTCHA_SECRET` y `CAPTCHA_SITEKEY`.

## Base de datos

1. Crea la base de datos importando el archivo `database.sql`:

   ```bash
   mysql -u <usuario> -p < database.sql
   ```

2. Genera una contraseña encriptada para el usuario administrador y guárdala en la tabla `admins`:

   ```bash
   php -r "echo password_hash('tu_contraseña', PASSWORD_DEFAULT);"
   ```

   Luego ejecuta en MySQL:

   ```sql
   INSERT INTO admins (username, password_hash, nombres, apellidos)
   VALUES ('admin', '<hash_generado>', 'Admin', 'Principal');
   ```

## Ejecución

Para desarrollo puedes levantar el servidor integrado de PHP apuntando al directorio `public`:

```bash
php -S localhost:8000 -t public
```

Abre `http://localhost:8000` en tu navegador para acceder a la aplicación.
