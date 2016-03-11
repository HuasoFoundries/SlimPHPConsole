# SlimPHPConsole

PHP-Console log writer for Slim Framework

[![Packagist](https://img.shields.io/packagist/dm/amenadiel/slim-phpconsole.svg)](https://packagist.org/packages/amenadiel/slim-phpconsole)

Use this custom log writer to output [Slim Framework](http://www.slimframework.com/)'s log messages
to your browser's console using [PHP-Console](https://github.com/barbushin/php-console).

### Installation

Just add `amenadiel/slim-phpconsole` to your `composer.json` file in the require or require-dev sections:

    {
        "require": {
            "amenadiel/slim-phpconsole":"~0.0.4"
        }
    }
 
### Usage
 
Instantiate the log writer. If you don't want to have the handler autostarted, pass `false` as a parameter, `true` is implied otherwise.

When the handler is started it will set itself as error and exception handler too, unless you set it otherwise.
 
```php
    $logwriter = new \Amenadiel\SlimPHPConsole\PHPConsoleWriter(true);

    $app = new \Slim\Slim(array(
        'log.enabled' => true,
        'log.level' => \Slim\Log::DEBUG,
        'log.writer' => $logwriter
    ));
```

Starting from version `0.0.6` this adapter extends Slim\Middleware. Therefore, you can also use the `add` method of your app

```php
    $app = new \Slim\Slim(array(
        'log.enabled' => true,
        'log.level' => \Slim\Log::DEBUG
    ));

    $app->add(new \Amenadiel\SlimPHPConsole\PHPConsoleWriter);
```

Both ways of setting PHP-Console as your logger are pretty much the same. Afterwards, you can send messages to your browser's console using `$app->log`'s methods.

 
```php
    $app->log->debug('Debug called!');
    $app->log->info('This is just info');
    $app->log->warning('Heads Up! This is a warning');
```
 
You can pass custom tags to PHPConsole by using this adapter's `debug` method which forwards its parameters to PHPConsole's `debug` method.

```php 
    $app->log->getWriter()->debug('This has a custom tag', 'custom.tag');
```

If you are using PHPConsole directly somewhere else in your app, remember not to start it twice, for it will throw an exception. Use its `isStarted` method to check if it's already started.

```php
    $myHandler = \PhpConsole\Handler::getInstance();
    
    if (!$myHandler->isStarted()) {
        $myHandler->start(); // Only start it if it hasn't been started yet
    }
```
 
### Optional Settings
 
You can use PHP-Console's configuration methods by getting a reference to the Handler instance or the Connector instance. For example:
 
 ```php
    $logwriter = new \Amenadiel\SlimPHPConsole\PHPConsoleWriter(false);
    $handler = $logwriter->getHandler();
    $handler->setHandleErrors(false);  // disable errors handling, must be done before 'start' method
    $handler->start();
 
    $connector = $logwriter->getConnector();
    $connector->setPassword('macoy123'); //sets a very insecure passwd
```


