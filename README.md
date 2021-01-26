<p align="center"><a href="https://jarscr.com" target="_blank"><img src="https://raw.githubusercontent.com/jarscr/teo/master/public/static/img/logos/logo-teo.png" width="192"></a></p>


<p align="center">
<a href="https://packagist.org/packages/jarscr/teo"><img src="https://img.shields.io/badge/PHP-^7.3-brightgreen.svg" alt="PHP Version">
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

Controllers respond to user actions (clicking on a link, submitting a form etc.). Controllers are classes that extend the [Core\Controller](Core/Controller.php) class.

Controllers are stored in the `App/Controllers` folder. A sample [Home controller](App/Controllers/Home.php) included. Controller classes need to be in the `App/Controllers` namespace. You can add subdirectories to organise your controllers, so when adding a route for these controllers you need to specify the namespace (see the routing section above).

Controller classes contain methods that are the actions. To create an action, add the **`Action`** suffix to the method name. The sample controller in [App/Controllers/Home.php](App/Controllers/Home.php) has a sample `index` action.

You can access route parameters (for example the **id** parameter shown in the route examples above) in actions via the `$this->route_params` property.

### Action filters

Controllers can have **before** and **after** filter methods. These are methods that are called before and after **every** action method call in a controller. Useful for authentication for example, making sure that a user is logged in before letting them execute an action. Optionally add a **before filter** to a controller like this:

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

To stop the originally called action from executing, return `false` from the before filter method. An **after filter** is added like this:

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

## Views

Views are used to display information (normally HTML). View files go in the `App/Views` folder. Views can be in one of two formats: standard PHP, but with just enough PHP to show the data. No database access or anything like that should occur in a view file. You can render a standard PHP view in a controller, optionally passing in variables, like this:

```php
View::render('Home/index.php', [
    'name'    => 'Dave',
    'colours' => ['red', 'green', 'blue']
]);
```

The second format uses the [Twig](http://twig.sensiolabs.org/) templating engine. Using Twig allows you to have simpler, safer templates that can take advantage of things like [template inheritance](http://twig.sensiolabs.org/doc/templates.html#template-inheritance). You can render a Twig template like this:

```php
View::renderTemplate('Home/index.html', [
    'name'    => 'Dave',
    'colours' => ['red', 'green', 'blue']
]);
```

A sample Twig template is included in [App/Views/Home/index.html](App/Views/Home/index.html) that inherits from the base template in [App/Views/base.html](App/Views/base.html).

## Models

Models are used to get and store data in your application. They know nothing about how this data is to be presented in the views. Models extend the `Core\Model` class and use [PDO](http://php.net/manual/en/book.pdo.php) to access the database. They're stored in the `App/Models` folder. A sample user model class is included in [App/Models/User.php](App/Models/User.php). You can get the PDO database connection instance like this:

```php
$db = static::getDB();
```

## Errors

If the `SHOW_ERRORS` configuration setting is set to `true`, full error detail will be shown in the browser if an error or exception occurs. If it's set to `false`, a generic message will be shown using the [App/Views/404.html](App/Views/404.html) or [App/Views/500.html](App/Views/500.html) views, depending on the error.

## Web server configuration

Pretty URLs are enabled using web server rewrite rules. An [.htaccess](public/.htaccess) file is included in the `public` folder. Equivalent nginx configuration is in the [nginx-configuration.txt](nginx-configuration.txt) file.

---

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Developer

<p align="center"><a href="https://jarscr.com" target="_blank"><img src="https://raw.githubusercontent.com/jarscr/teo/master/public/static/img/logos/logo-jarscr.png" width="182"></a></p>
