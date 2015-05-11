<?php
/**
 * BloggerCMS - Easiest Static Blog Generator
 *
 * @author      Sarfraz Ahmed <sarfraznawaz2005@gmail.com>
 * @copyright   2015 Sarfraz Ahmed
 * @link        https://bloggercms.github.io
 * @version     1.0.0
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
