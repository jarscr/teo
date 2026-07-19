<p align="center"><a href="https://jarscr.com" target="_blank"><img src="https://raw.githubusercontent.com/jarscr/teo/master/public/static/img/logos/logo-teo.png" width="192"></a></p>

<p align="center">
<a href="https://packagist.org/packages/jarscr/teo"><img src="https://img.shields.io/badge/PHP-^8.3-brightgreen.svg" alt="PHP Version"></a>
<a href="https://packagist.org/packages/jarscr/teo"><img src="https://img.shields.io/packagist/dt/jarscr/teo" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/jarscr/teo"><img src="https://img.shields.io/packagist/v/jarscr/teo" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/jarscr/teo"><img src="https://api.travis-ci.com/jarscr/teo.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/jarscr/teo"><img src="https://img.shields.io/packagist/l/jarscr/teo" alt="License"></a>
</p>

# Acerca de TEO Simple PHP Framework

TEO es un framework PHP para construir aplicaciones y sitios web. Es gratis y [open-source](LICENSE).

Basado en MVC ([daveh/php-mvc](https://github.com/daveh/php-mvc)).

**Requisitos:** PHP **8.3** o **8.4**, Composer, MySQL/MariaDB (opcional según tu app).

## Iniciar usando el framework

1. Instala el proyecto: `composer create-project jarscr/teo app-ejemplo`
2. Entra al directorio e instala dependencias si hace falta: `composer install`
3. Copia la configuración de entorno:

   ```bash
   cp .env.example .env
   ```

4. Edita `.env` con tus datos de base de datos e idioma.
5. Importa el esquema si lo necesitas: `mysql -u root -p < teo.sql`
6. Configura el servidor web para que el **document root** sea la carpeta `public/`.
7. Crea rutas, controladores, vistas y modelos.

> **Importante:** no subas el archivo `.env` a Git. Contiene secretos (credenciales, flags de depuración).

## Configuración

La configuración se carga desde variables de entorno (archivo [`.env`](.env.example) o el entorno del servidor). La clase [App/Config.php](App/Config.php) las lee de forma segura.

Variables principales:

| Variable       | Descripción                                      | Ejemplo        |
|----------------|--------------------------------------------------|----------------|
| `APP_ENV`      | Entorno (`local`, `production`, …)               | `local`        |
| `APP_DEBUG`    | Mostrar errores detallados (`true` / `false`)    | `false`        |
| `APP_LANG`     | Idioma por defecto                               | `es`           |
| `APP_VERSION`  | Versión de la aplicación                         | `1.0.8`        |
| `DB_HOST`      | Host de MySQL                                    | `127.0.0.1`    |
| `DB_NAME`      | Nombre de la base de datos                       | `teo`          |
| `DB_USER`      | Usuario                                          | `teo`          |
| `DB_PASSWORD`  | Contraseña                                       | `change-me`    |
| `DB_CHARSET`   | Charset PDO                                      | `utf8mb4`      |

Uso en código:

```php
use App\Config;

$host = Config::dbHost();
$debug = Config::showErrors();
$lang = Config::lang();
```

En producción deja `APP_DEBUG=false`. Los errores se registran en `logs/` y se muestran plantillas genéricas 404/500.

## Rutas

Las [rutas](Core/Router.php) traducen URLs en controladores y acciones. Se definen en el [front controller](public/index.php).

```php
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('posts/index', ['controller' => 'Posts', 'action' => 'index']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);
```

El router valida nombres de controlador/acción/namespace y solo permite invocar métodos `*Action` a través de los filtros del controlador (no se pueden llamar métodos arbitrarios).

## Controladores

Los controladores viven en `App/Controllers`, extienden [Core\Controller](Core/Controller.php) y usan el namespace `App\Controllers`.

Las acciones llevan el sufijo `Action` (por ejemplo `indexAction`). Los parámetros de ruta están en `$this->route_params`.

### Filtros before / after

```php
protected function before(): mixed
{
    // return false para cancelar la acción
    return null;
}

protected function after(): void
{
}
```

## Vistas

Las vistas están en `App/Views`. Dos formatos:

**PHP:**

```php
View::render('Home/index.php', [
    'name' => 'Dave',
    'colours' => ['red', 'green', 'blue'],
]);
```

**Twig** (recomendado; autoescape HTML activo):

```php
View::renderTemplate('Home/index.html', [
    'name' => 'Dave',
    'colours' => ['red', 'green', 'blue'],
], 'es');
```

Traducciones vía Symfony Translation + Twig Bridge; archivos en `App/Languages/` (`es.php`, `en.php`).

Plantilla de ejemplo: [App/Views/Home/index.html](App/Views/Home/index.html) (hereda de [base.html](App/Views/base.html)).

Las rutas de vista/plantilla rechazan path traversal (`..`).

## Modelos

Los modelos extienden [Core\Model](Core/Model.php) y usan PDO con:

- charset `utf8mb4`
- `PDO::ERRMODE_EXCEPTION`
- `PDO::ATTR_EMULATE_PREPARES = false`
- fetch asociativo por defecto

```php
$db = static::getDB();
$stmt = $db->prepare('SELECT id, username FROM users WHERE id = ?');
$stmt->execute([$id]);
$user = $stmt->fetch();
```

Ejemplo: [App/Models/User.php](App/Models/User.php). El esquema (tablas de `delight-im/auth`) está en [teo.sql](teo.sql) con motor **InnoDB**.

## Errores

Con `APP_DEBUG=true` se muestran detalles escapados en el navegador. Con `false`, se escribe en `logs/YYYY-MM-DD.txt` y se renderizan [404.html](App/Views/404.html) / [500.html](App/Views/500.html).

Opcionalmente puedes integrar [Sentry](https://sentry.io) (`sentry/sentry` en `require-dev`).

## Seguridad (resumen)

- Credenciales solo en `.env` / entorno del servidor
- Cabeceras HTTP básicas (`X-Content-Type-Options`, `X-Frame-Options`, CSP, etc.)
- Bloqueo de acceso a `.env`, `App/`, `Core/`, `vendor/`, `logs/` desde Apache (`.htaccess`)
- Acciones del router acotadas a `*Action` + filtros
- Twig con autoescape; salida de errores sanitizada

## Pruebas

```bash
composer test
# o
vendor/bin/phpunit --configuration phpunit.xml.dist
```

## Configuración del servidor web

- **Apache:** [public/.htaccess](public/.htaccess) (y [.htaccess](.htaccess) en la raíz si el vhost apunta al proyecto)
- **nginx:** [nginx-configuration.txt](nginx-configuration.txt) — el root debe ser `public/`

---

## Licencia

Teo PHP MVC es software open-source bajo [licencia MIT](https://opensource.org/licenses/MIT).

## Desarrolla

<p align="center"><a href="https://jarscr.com" target="_blank"><img src="https://raw.githubusercontent.com/jarscr/teo/master/public/static/img/logos/logo-jarscr.png" width="182"></a></p>
