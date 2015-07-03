# SlimPHPConsole

PHP-Console log writer for Slim Framework

Use this custom log writer to output [Slim Framework](http://www.slimframework.com/)'s log messages
to your browser's console using [PHP-Console](https://github.com/barbushin/php-console).
 
### USAGE
 
  $logwriter = new \Amenadiel\SlimPHPConsole\Log\PHPConsoleWriter(true);
  $app = new \Slim\Slim(array(
    'log.enabled' => true,
    'log.level' => \Slim\Log::DEBUG,
    'log.writer' => $logwriter
  ));
 
  $app->log->debug('Debug called!');
  $app->log->info('This is just info');
  $app->log->info('This is just info');
  $app->log->warning('Heads Up! This is a warning');
 
You can pass custom tags to PHPConsole by using this adapter's debug method
 
  $app->log->getWriter()->debug('This has a custom tag', 'custom.tag');
 
 
### SETTINGS
 
You can use PHP-Console's configuration methods by getting a reference to the Handler instance or the Connector instance. For example:
 
  $handler = $logwriter->getHandler();
  $handler->setHandleErrors(false);  // disable errors handling
 
  $connector = $logwriter->getConnector();
  $connector->setPassword('macoy123'); //sets a very insecure passwd

