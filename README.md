# Seguimiento Académico San Luis

Prototipo inicial de la plataforma para la I.E. Seminario San Luis Gonzaga:

- **Web:** Laravel 11 con un panel adaptable para coordinación académica.
- **Móvil:** Flutter con una pantalla inicial para teléfonos.
- **Datos:** ficticios, incluidos solo para mostrar la propuesta visual.

La portada pública usa datos genéricos de demostración. El personal puede iniciar sesión en `/login`: administración ve los datos institucionales y mensajes registrados; cada docente ve solo estudiantes vinculados a sus cursos.

## Requisitos

- PHP 8.2 o posterior y Composer.
- Flutter 3.19 o posterior y un emulador/dispositivo configurado para Android.

## Ejecutar el panel web

Desde la carpeta raíz del proyecto:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
php artisan serve
```

Si ya existe el archivo `.env`, no hace falta copiarlo de nuevo. Para inicializar la base de datos y crear cuentas institucionales:

```powershell
php artisan migrate
php artisan staff:create admin
php artisan staff:create teacher
```

El comando solicita los datos por consola y nunca crea contraseñas predeterminadas. Asigna los cursos a las cuentas docentes desde la base de datos antes de iniciar sesión para que aparezcan sus estudiantes. Abre `http://127.0.0.1:8000` para el resumen público y `http://127.0.0.1:8000/login` para el acceso institucional.

## Ejecutar la app móvil

Desde la carpeta `mobile`:

```powershell
flutter create --platforms=android,web .
flutter pub get
flutter run
```

El proyecto Flutter presenta un resumen, rendimiento, alertas, un formulario visual de convivencia y el acceso a Escuela de Padres. El formulario de convivencia no envía información. La app todavía no se conecta al panel Laravel.

## Verificar el panel

Desde la raíz:

```powershell
php artisan test
```

## Módulos contemplados

1. Seguimiento académico y alertas tempranas por rendimiento y asistencia.
2. Dashboard para revisar indicadores y estudiantes.
3. Convivencia escolar: atención confidencial y seguimiento de reportes.
4. Escuela de padres: comunicados y coordinación por grado y sección.

La API móvil mantiene sus endpoints separados; la creación pública de cuentas solo permite roles de familia o estudiante, nunca administración ni docente.
