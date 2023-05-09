# megan-mvc
# Overview
A simple MVC framework for building web pages, implemented in PHP.
I have started developing it in 2015 to collect a variety of best practices
that I developed over a decade or so of PHP development. I picked it up again
recently to refresh my skills.
# Features
- There is only a single `php` file in the `doc root`, it is `index.php`, 
and this file only contains a few lines that invoke the framework (`fw.php`) which is located outside the document root for better isolation.
- The framework relies on the NGINX `try_files` directive to send all URIs
which don't correspond to an existing file to the `index.php`.
- The framework parses the URI into one or more directory names and the
file name, which corresponds to a PHP class name.
For example: `FW_ROOT` is the directory containing the framework.
If the URI is `/xxx/yyy/zzz` then the
path to the class file is `FW_ROOT/controllers/xxx/yyy/zzz-controller.php`
and the class name is `XxxYyyZzzController`.
Depending on the controller it may invoke a model. The
path to the class file is `FW_ROOT/models/xxx/yyy/zzz-model.php`
and the class name is `XxxYyyZzzModel`.
Similarly the controller it may invoke a view. The
path to the class file is `FW_ROOT/views/xxx/yyy/zzz-views.php`
and the class name is `XxxYyyZzzView`.
