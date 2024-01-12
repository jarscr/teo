<p align="center"><a href="https://jarscr.com" target="_blank"><img src="https://raw.githubusercontent.com/jarscr/teo/master/public/static/img/logos/logo-teo.png" width="192"></a></p>


<p align="center">
<a href="https://packagist.org/packages/jarscr/teo"><img src="https://img.shields.io/badge/PHP-^8.2-brightgreen.svg" alt="PHP Version">
<a href="https://packagist.org/packages/jarscr/teo"><img src="https://img.shields.io/packagist/dt/jarscr/teo" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/jarscr/teo"><img src="https://img.shields.io/packagist/v/jarscr/teo" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/jarscr/teo"><img src="https://api.travis-ci.com/jarscr/teo.svg" alt="Build Status">
<a href="https://packagist.org/packages/jarscr/teo"><img src="https://img.shields.io/packagist/l/jarscr/teo" alt="License"></a>
</p>

# Acerca de TEO Simple PHP Framework

TEO es un Framework en PHP para construir aplicaciones Web y Sitios Web. Es gratis y [open-source](LICENSE). 

Este proyecto esta basado en MVC <a href="https://github.com/daveh/php-mvc">daveh/php-mvc</a>

## Iniciar usando el framework

1. Primero, instale el proyecto con **composer create-project jarscr/teo app-ejemplo**.
1. Ejecuta **composer update** para instalar las dependecias.
1. Configure el servidor web para que apunte a la carpeta **public** como web root.
1. Abra [App/Config.php](App/Config.php) y ingrese los datos de conexión con la base de datos.
1. Crea rutas, agrega controladores, vistas y modelos.

Revisa las instrucciones para que puedas usar este framework.

## Configuración

Configuration settings are stored in the [App/Config.php](App/Config.php) class. Default settings include database connection data and a setting to show or hide error detail. You can access the settings in your code like this: `Config::DB_HOST`. You can add your own configuration settings in here.

## Rutas

Las [Rutas](Core/Router.php) traduce las URL en controladores y acciones. Las rutas se agregan en el [controlador] (public/index.php). Se incluye una ruta de inicio de muestra que se enruta a la acción `index` en el [controlador de home](App/Controllers/Home.php).

Las rutas se agregan con el método `add`. Puede agregar rutas URL fijas y especificar el controlador y la acción, así:

```php
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('posts/index', ['controller' => 'Posts', 'action' => 'index']);
```

O puede agregar **variables** de ruta, así:

```php
$router->add('{controller}/{action}');
```

Además de **controller** y **action**, puede especificar cualquier parámetro que desee entre llaves y también especificar una expresión regular personalizada para ese parámetro:

```php
$router->add('{controller}/{id:\d+}/{action}');
```

También puede especificar un espacio de nombres para el controlador:

```php
$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);
```

## Controladores

Los controladores responden a las acciones del usuario (hacer clic en un enlace, enviar un formulario, etc.). Los controladores son clases que amplían la clase [Core\Controller] (Core/Controller.php).

Los controladores se almacenan en la carpeta `App/Controllers`. Se incluye una muestra de [Home controller] (App/Controllers/Home.php). Las clases de controlador deben estar en el espacio de nombres `App/Controllers`. Puede agregar sub directorios para organizar sus controladores, por lo que al agregar una ruta para estos controladores, debe especificar el espacio de nombres (consulte la sección de enrutamiento anterior).

Las clases de controlador contienen métodos que son las acciones. Para crear una acción, agregue el sufijo ** `Action` ** al nombre del método. El controlador de muestra en [App/Controllers/Home.php] (App/Controllers/Home.php) tiene una acción "index" de muestra.

Puede acceder a los parámetros de ruta (por ejemplo, el parámetro ** id ** que se muestra en los ejemplos de ruta anteriores) en acciones a través de la propiedad `$ this->route_params`.

### Action filters

Los controladores pueden tener métodos de filtrado **before** y **after**. Estos son métodos que se llaman antes y después de **cada** llamada al método de acción en un controlador. Útil para la autenticación, por ejemplo, asegurarse de que un usuario haya iniciado sesión antes de permitirle ejecutar una acción. Opcionalmente, agregue un **antes del filtro** a un controlador como este:

```php
/**
 * Before filter. Return false to stop the action from executing.
 *
 * @return void
 */
protected function before()
{
}
```
Para detener la ejecución de la acción llamada originalmente, devuelve `false` del método de filtro anterior. Se agrega un **filtro posterior** así:

```php
/**
 * After filter.
 *
 * @return void
 */
protected function after()
{
}
```

## Vistas
Las vistas se utilizan para mostrar información (normalmente HTML). Los archivos de visualización van en la carpeta `App/Views`. Las vistas pueden estar en uno de dos formatos: PHP estándar, pero con PHP suficiente para mostrar los datos. Ningún acceso a la base de datos ni nada parecido debería ocurrir en un archivo de vista. Puede representar una vista PHP estándar en un controlador, opcionalmente pasando variables, como esta:


```php
View::render('Home/index.php', [
    'name'    => 'Dave',
    'colours' => ['red', 'green', 'blue']
]);
```
El segundo formato utiliza el motor de plantillas [Twig] (http://twig.sensiolabs.org/). El uso de Twig le permite tener plantillas más simples y seguras que pueden aprovechar cosas como [herencia de plantillas] (http://twig.sensiolabs.org/doc/templates.html#template-inheritance). Puede renderizar una plantilla Twig como esta:


```php
View::renderTemplate('Home/index.html', [
    'name'    => 'Dave',
    'colours' => ['red', 'green', 'blue']
]);
```
Se incluye una plantilla de muestra de Twig en [App/Views/Home/index.html](App/Views/Home/index.html) que hereda de la plantilla base en [App/Views/base.html](App/Views/base.html).


## Modelos

Los modelos se utilizan para obtener y almacenar datos en su aplicación. No saben nada sobre cómo se presentarán estos datos en las vistas. Los modelos extienden la clase `Core\Model` y usan [PDO] (http://php.net/manual/en/book.pdo.php) para acceder a la base de datos. Están almacenados en la carpeta `App/Models`. Se incluye una clase de modelo de usuario de muestra en [App/Models/User.php](App/Models/User.php). Puede obtener la instancia de conexión de la base de datos PDO de esta manera:


```php
$db = static::getDB();
```

## Errores

Si la configuración de `SHOW_ERRORS` se establece en `true`, el navegador mostrará todos los detalles del error si se produce un error o una excepción. Si se establece en `false`", se mostrará un mensaje genérico mediante [App/Views/404.html](App/Views/404.html) o [App/Views/500.html](App/Views/500.html) vistas, según el error.

En este proyecto usamos Sentry.io para monitorear los errores.

## Configuración del servidor web

Las URL amigables se habilitan mediante reglas de reescritura del servidor web. Se incluye un archivo [.htaccess] (public/.htaccess) en la carpeta `public`. 

La configuración equivalente de nginx se encuentra en el archivo [nginx-configuration.txt] (nginx-configuration.txt). 

---

## Licencia

Teo PHP MVC es un software open-sourced licenciado bajo [MIT license](https://opensource.org/licenses/MIT).

## Desarrolla

<p align="center"><a href="https://jarscr.com" target="_blank"><img src="https://raw.githubusercontent.com/jarscr/teo/master/public/static/img/logos/logo-jarscr.png" width="182"></a></p>
