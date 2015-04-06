<?php
//////////////////////////////////////////////
// General Configuration
//////////////////////////////////////////////


$config['appname'] = 'BloggerCMS';

// date format used for posts and pages
$config['dateFormat'] = 'F d, Y h:i A';

/*
 * If debugging is enabled, Slim will use its built-in error handler to display
 * diagnostic information for uncaught Exceptions. If debugging is disabled,
 * Slim will instead invoke your custom error handler, passing it the otherwise
 * uncaught Exception as its first and only argument.
 *
 * Slim converts errors into `ErrorException` instances.
 * */
$config['debug'] = true;

/*
 *  mode is only for you to optionally invoke your own code for a given mode with
 *  the configureMode() application method.
 *
 * Set either "development" or "production"
 * */
$config['mode'] = 'development';

// Error Logging Directory Path
$config['log_path'] = '';

//////////////////////////////////////////////
// Database Configuration
//////////////////////////////////////////////
$config['database_enable'] = false; // set to true if app supports db
$config['database_type'] = 'mysql'; // database type either mysql or sqlite
$config['database_host'] = 'localhost';
$config['database_user'] = 'root';
$config['database_password'] = '';
$config['database_dbname'] = '';
