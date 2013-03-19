<?php

/**
 * User: tarakanov
 */
namespace App;

class App {


    /**
     * @var Env|null
     */
    public $env = null;

    /**
     * @var  string  character set of input and output
     */
    public static $charset = 'utf-8';

    /**
     * @var
     */
    public static $profiling = FALSE;

    /**
     * @var  array  Types of errors to display at shutdown
     */
    public static $shutdown_errors = array(E_PARSE, E_ERROR, E_USER_ERROR);

    /**
     * @var  array   Currently active modules
     */
    protected static $_modules = array();

    private static $initialized = false;

    /**
     * @var null|\Monolog\Logger
     */
    public $logger = null;









    /**
     *  @var App
     */
    private static $Instance = null;


    /**
     * @return App
     */
    public static function getInstance() {
        if(!static::$Instance) {
             throw new Exception\AppNotInitialized();
        }
        return static::$Instance;
    }

    public function __construct($envAlias) {
        static::$Instance = $this;
        $this->env = new Env($envAlias);
    }

    /**
     * @return App
     */
    public function initialize() {
        date_default_timezone_set($this->config()->timezone);
        setlocale(LC_ALL, $this->config()->locale);

        $this->logger = $this->getLogger('app', new \Monolog\Handler\StreamHandler(WEB_ROOT . $this->config()->path->log . 'app.log'));

        set_exception_handler('\App\ErrorHandler::processException');

        if(!$this->env->isDev() && !$this->env->isTest()) {
            set_error_handler('\App\ErrorHandler::processError');
        }

        register_shutdown_function(array('\App\App', 'shutdownHandler'));

        static::$initialized = true;
    }

    public static function shutdownHandler() {
        if(!static::$initialized) {
            return false;
        }

        if ($error = error_get_last() AND in_array($error['type'], array(E_PARSE, E_ERROR, E_USER_ERROR))) {
            // Clean the output buffer
            ob_get_level() && ob_clean();

            // Fake an exception for nice debugging
            \App\ErrorHandler::processException(new \App\Exception\ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']));

            // Shutdown now to avoid a "death loop"
            exit(1);
        }
    }

    /**
     * @param $channel
     * @param null|\Monolog\Handler\AbstractProcessingHandler $handler
     * @return \Monolog\Logger
     */
    public function getLogger($channel, $handler = null) {
        $logger = new \Monolog\Logger($channel);
        $handler && $logger->pushHandler($handler);
        return $logger;
    }

    public static function auto_load($class, $directory = '') {
        error_log($class . " : " . $directory);

        // Transform the class name according to PSR-0
        $class     = ltrim($class, '\\');
        $file      = '';

        if ($last_namespace_position = strripos($class, '\\')) {
            $namespace = substr($class, 0, $last_namespace_position);
            $class     = substr($class, $last_namespace_position + 1);
            $file      = str_replace('\\', DIRECTORY_SEPARATOR, $namespace).DIRECTORY_SEPARATOR;
        }

        $file .= str_replace('_', DIRECTORY_SEPARATOR, $class);

        if ($path = self::find_file($file)) {
            // Load the class file
            require $path;

            // Class has been found
            return TRUE;
        }

        // Class is not in the filesystem
        return FALSE;
    }

    /**
     * @param string $name
     * @return Config
     */
    public function config($name = "default") {
        return $this->env->ConfigReader->get($name);
    }

    public static function find_file($file, $ext = NULL, $array = FALSE) {
        if ($ext === NULL) {
            // Use the default extension
            $ext = '.php';
        } elseif ($ext) {
            // Prefix the extension with a period
            $ext = "." . $ext;
        } else {
            // Use no extension
            $ext = '';
        }

        // Create a partial path of the filename
        $path = $file.$ext;

            // The file has not been found yet
        $found = FALSE;

        foreach (array(WEB_ROOT) as $dir) {
            if (is_file($dir.$path)) {
                // A path has been found
                $found = $dir.$path;

                // Stop searching
                break;
            }
        }

        return $found;
    }



}