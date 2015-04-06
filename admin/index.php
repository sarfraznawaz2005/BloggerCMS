<?php
// autoload dependencies automatically via magical composer autoload
require_once 'vendor/autoload.php';

// website configuration file
require_once 'config.php';

// set error reporting
if ($config['mode'] === 'development') {
    ini_set('display_errors', true);
    error_reporting(1);
}

// path to save logs to
$logWriter = new \Slim\LogWriter(fopen($config['log_path'] . 'applog.log', 'a+'));

// instantiate slim framework
$options = array(
   'debug' => $config['debug'],
   'templates.path' => 'app/views/',
   'mode' => $config['mode'],
   'log.writer' => $logWriter,
   'cookies.encrypt' => true,
   'cookies.cipher' => MCRYPT_RIJNDAEL_256,
   'cookies.secret_key' => md5('@!secret!@'),
   'cookies.lifetime' => '20 minutes'
);

$app = new \Slim\Slim($options);
$app->setName($config['appname']); // later in view for example: $app->getName()

$app->hook(
   'slim.before.router',
   function () use ($app, $config) {
       $app->view()->setData('app', $app); // we can now use $app in views
       $app->view()->setData('root', dirname($_SERVER['SCRIPT_NAME']));
       $app->view()->setData('layoutsDir', dirname(dirname(__FILE__)) . '\\layouts\\');
       $app->view()->setData('dateFormat', $config['dateFormat']);
   }
);

// slim environment
$environment = \Slim\Environment::getInstance();

// logging
$log = $app->getLog();
$log->setEnabled(false);

if ($config['mode'] === 'development') {
    $app->configureMode(
       'development',
       function () use ($log) {
           $log->setLevel(\Slim\Log::DEBUG);
           $log->setEnabled(true);
           $log->debug("Application Started...");
       }
    );
}

// database configuration
if ($config['database_enable']) {
    if ($config['database_type'] === 'mysql') {
        ORM::configure('mysql:host=' . $config['database_host'] . ';dbname=' . $config['database_dbname']);
        ORM::configure('username', $config['database_user']);
        ORM::configure('password', $config['database_password']);
    } elseif ($config['database_type'] === 'sqlite') {
        ORM::configure('sqlite:./db.sqlite');
        $db = ORM::get_db();
        //$db->exec("create database schema here...");
    }
}

// general functions file
require_once 'functions.php';
// routes file
require_once 'routes.php';

$app->run();