# megan-mvc
# Overview
A simple MVC framework for building web pages, implemented in PHP.
I have started developing it in 2015 to collect a variety of best practices
that I developed over a decade or so of PHP development. I picked it up again
recently to refresh my skills.
# Framework
- The project is completely self-contained and does not rely on
any framework (Laravel, Zend, Symfony, Cake, etc.). Why write it all
from scratch? It's more fun and you learn more that way!
- There is only a single `php` file in the `doc root`, it is `index.php`, 
and this file only contains a few lines that invoke the framework (`fw.php`) which is located outside the document root for better isolation.
- The framework relies on the NGINX `try_files` directive to send all URIs
which don't correspond to an existing file to the `index.php`.
- The framework supports a `model-view-controller (MVC)` design pattern.
The control logic is in the `controller`, the data comes from the `model`, and 
the presentation of the web page is handled in the `view`.
- The framework parses the URI into one or more directory names and the
file name, which corresponds to a PHP class name.
For example: `FW_ROOT` is the directory containing the framework.
If the URI is `/xxx/yyy/zzz` then the
path to the class file is `FW_ROOT/controllers/xxx/yyy/zzz-controller.php`
and the class name is `XxxYyyZzzController`.
Depending on the controller, it may invoke a model. The
path to the class file is `FW_ROOT/models/xxx/yyy/zzz-model.php`
and the class name is `XxxYyyZzzModel`.
Similarly the controller may invoke a view. The
path to the class file is `FW_ROOT/views/xxx/yyy/zzz-views.php`
and the class name is `XxxYyyZzzView`.
- Supporting classes are found similarly. For example, the `Cookies` class
is in `FW_ROOT/classes/Cookies.php`.
- The PHP `autoloader` feature is used to find the class files, removing
the need for explicit `require` or `include` statements.
- The MVC classes rely heavily on object-oreiented class inheritance.
For example the `View` class does most of the work for generating a web 
page. Pages like the `/login` page are implemented by the `LoginView` class
which extends `View`.
# Database
- The framework supports CRUD database operations. Originally it
used `mysql` but I recently switched to `mariadb` with few changes required.
- There is a singleton `DbConn` class that allows multiple classes
within a page to share a common DB connection.
# Presentation
- The front end code is built upon [Bootstrap](https://getbootstrap.com/docs/3.4/css/) for CSS/presentation and [JQuery](https://jquery.com/) for javascript/interactivity. In this day and age modern front end projects tend to be built on a javascript framework such as `Angular` or `React` but I find these are overkill for a relatively simple set of web pages.
