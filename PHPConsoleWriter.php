<?php

/**
 * PHP-Console log writer for Slim Framework
 *
 * Use this custom log writer to output Slim's log messages
 * to your browser's console using PHP-Console.
 *
 * USAGE
 *
 * Instantiate the log writer. If you don't want to have the handler
 * autostarted, pass false as a parameter. True is implied otherwise.
 * When the handler is started it will set itself as error and exception
 * handler too, unless you set it otherwise.
 *
 *     $logwriter = new \Amenadiel\SlimPHPConsole\PHPConsoleWriter(true);
 *     $app = new \Slim\Slim(array(
 *         'log.enabled' => true,
 *         'log.level' => \Slim\Log::DEBUG,
 *         'log.writer' => $logwriter
 *     ));
 *
 *     $app->log->debug('Debug called!');
 *     $app->log->info('This is just info');
 *     $app->log->warning('Heads Up! This is a warning');
 *
 * You can pass custom tags to PHPConsole by using this adapter's debug method
 *
 *     $app->log->getWriter()->debug('This has a custom tag', 'custom.tag');
 *
 * If you are using PHPConsole directly somewhere else in your app, remember not
 * to start it twice, for it will throw an exception:
 *
 *     $myHandler = \PhpConsole\Handler::getInstance();
 *
 *     if (!$myHandler->isStarted()) {
 *        $myHandler->start();
 *     }
 *
 *
 *
 * SETTINGS
 *
 * You can use PHPConsole's configuration methods by getting a reference
 * to the Handler instance or the Connector instance. For example:
 *
 *     $handler = $logwriter->getHandler();
 *     $handler->setHandleErrors(false);  // disable errors handling
 *
 *     $connector = $logwriter->getConnector();
 *     $connector->setPassword('macoy123'); //sets a very insecure passwd
 *
 *
 *
 * @author Felipe Figueroa <amenadiel@gmail.com>
 * @copyright 2015 Amenadiel
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Amenadiel\SlimPHPConsole;

class PHPConsoleWriter extends \Slim\Middleware
{
    /**
     * @var handler
     */
    protected $handler;

    /**
     * @var connector
     */
    protected $connector;

    /**
     * Converts Slim log level to its matching PHP-Console tag
     * @var array
     */
    protected $log_level = [
        \Slim\Log::EMERGENCY => 'EMERGENCY',
        \Slim\Log::ALERT => 'ALERT',
        \Slim\Log::CRITICAL => 'CRITICAL',
        \Slim\Log::ERROR => 'ERROR',
        \Slim\Log::WARN => 'WARN',
        \Slim\Log::NOTICE => 'NOTICE',
        \Slim\Log::INFO => 'INFO',
        \Slim\Log::DEBUG => 'DEBUG',
    ];

    /**
     * Constructs the writer, optionally starts the handler
     * @param boolean $autostart set to true to autostart the handler
     * @return  void
     */
    public function __construct($autostart = true)
    {

        $this->handler = \PhpConsole\Handler::getInstance();
        $this->connector = $this->handler->getConnector();
        if ($autostart && !$this->handler->isStarted()) {
            $this->handler->start();
        }

    }

    public function call()
    {
        $app = $this->app;

        $app->config('log.writer', $this);

        $this->next->call();
    }

    public function getHandler()
    {
        return $this->handler;
    }

    public function getConnector()
    {
        return $this->connector;
    }

    public function debug($data, $tags = null, $ignoreTraceCalls = 0)
    {
        $this->handler->debug($data, $tags, $ignoreTraceCalls);
    }

    /**
     * Write to log
     *
     * @param   mixed $object
     * @param   int   $level
     * @return  void
     */
    public function write($object, $level = \Slim\Log::DEBUG)
    {
        $this->handler->debug($object, $this->get_log_level($level));
    }

    /**
     * Converts Slim log level to matching PHPConsole's tag
     *
     * @param  int $slim_log_level   Slim log level we're converting from
     * @return string matching debug level
     */
    protected function get_log_level($slim_log_level)
    {

        return isset($this->log_level[$slim_log_level]) ?
        $this->log_level[$slim_log_level] :
        $slim_log_level;
    }
}
