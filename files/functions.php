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

///////////////////////////////////////////////////////////
//  utility functions
///////////////////////////////////////////////////////////

/**
 * pretty_print
 *
 * Prints an array in easy to read format
 *
 * @param array $array
 * @param bool $exit
 * @return mixed
 */
function pretty_print(array $array, $exit = true)
{
    if (!$array) {
        return false;
    }

    echo '<pre>';
    print_r($array);
    echo '</pre>';

    if ($exit) {
        exit;
    }
}

/**
 * dd
 *
 * var_dumps given data and dies
 *
 * @param $data
 * @return mixed
 */
function dd($data)
{
    if (!$data) {
        return false;
    }

    echo '<pre>';
    var_dump($data);
    echo '</pre>';

    exit;
}

/**
 * logConsole
 *
 * Logs messages/variables/data to browser console from within php
 *
 * @param $name
 * @param null $data
 * @param bool $jsEval
 * @return bool
 * @author Sarfraz
 */
function logConsole($name, $data = null, $jsEval = false)
{
    if (!$name) {
        return false;
    }

    $isevaled = false;
    $type = ($data || gettype($data)) ? 'Type: ' . gettype($data) : '';

    if ($jsEval && (is_array($data) || is_object($data))) {
        $data = 'eval(' . preg_replace('#[\s\r\n\t\0\x0B]+#', '', json_encode($data)) . ')';
        $isevaled = true;
    } else {
        $data = json_encode($data);
    }

    # sanitalize
    $data = $data ? $data : '';
    $search_array = array("#'#", '#""#', "#''#", "#\n#", "#\r\n#");
    $replace_array = array('"', '', '', '\\n', '\\n');
    $data = preg_replace($search_array, $replace_array, $data);
    $data = ltrim(rtrim($data, '"'), '"');
    $data = $isevaled ? $data : ($data[0] === "'") ? $data : "'" . $data . "'";

    $js = <<<JSCODE
\n<script>
     // fallback - to deal with IE (or browsers that don't have console)
     if (! window.console) console = {};
     console.log = console.log || function(name, data){};
     // end of fallback

     console.log('$name');
     console.log('------------------------------------------');
     console.log('$type');
     console.log($data);
     console.log('\\n');
</script>
JSCODE;

    //echo @$js;
}

/**
 * varLog
 *
 * Logs messages/variables/data to browser by creating custom console/floating window at bottom
 *
 * @param $name
 * @param $data
 * @author Sarfraz
 */
function varLog($name, $data)
{
    $type = ($data || gettype($data)) ? gettype($data) : '';

    $output = $data;
    if (is_array($data) || is_object($data)) {
        $output = '<table style="color:#fff; font-size:14px;" width="100%"><tr><td width="100" style="border:1px solid #ccc; border-bottom:0;"><strong>Propery</strong></td><td width="100" style="border:1px solid #ccc; border-bottom:0;"><strong>Value</strong></td></tr>';

        foreach ($data as $key => $value) {
            $key = preg_replace('~[\r\n]+~', '', $key);
            $value = preg_replace('~[\r\n]+~', '', $value);

            $output .= '<table style="color:#fff; font-size:13px;" width="100%"><tr><td width="100" style="border:1px solid #ccc; border-bottom:0;">' . $key . '</td><td width="100" style="border:1px solid #ccc; border-bottom:0;">' . $value . '</td></tr></table>';
        }
    }

    $js = <<< JSCODE
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        (function () {
            var varlog = document.createElement('div');
            var stylesObject = {
                "position": "fixed",
                "bottom": "5px",
                "right": "0",
                "left": "0",
                "min-height": "100px",
                "height": "auto",
                "width": "75%",
                "margin": "auto",
                "overflow": "auto",
                "background": "rgba(11,22,33, 0.7)",
                "box-shadow": "0 0 5px 5px gray",
                "color":"#fff",
                "padding": "5px",
                "font-family": "tahoma",
                "font-size": "12px",
                "border-radius": "10px",
                "border": "1px solid white"
            };

            // set styles
            for (var style in stylesObject) {
                if (stylesObject.hasOwnProperty(style)) {
                    varlog.style[style] = stylesObject[style];
                }
            }

            // set content
            varlog.innerHTML = '<strong style="font-size:16px;">$name (type: $type)</strong><hr style="margin:0;"><br>';
            varlog.innerHTML += '$output';

            // show now
            document.body.appendChild(varlog);

        }());
    });

    </script>
JSCODE;

    echo $js;
}

function getTimeDate()
{
    return date('Y-m-d h:i:s');
}

function in_multiarray($elem, $array)
{
    $top = sizeof($array) - 1;
    $bottom = 0;
    while ($bottom <= $top) {
        if ($array[$bottom] == $elem) {
            return true;
        } else {
            if (is_array($array[$bottom])) {
                if (in_multiarray($elem, ($array[$bottom]))) {
                    return true;
                }
            }
        }

        $bottom ++;
    }

    return false;
}

function dateFormat($date, $addTime = false)
{
    if (strtotime($date)) {

        if ($addTime) {
            return date('F d, Y h:i', strtotime($date));
        }

        return date('F d, Y', strtotime($date));
    }

    return '';
}

function getMysqlDate($date)
{
    if (!$date) {
        return false;
    }

    return date('Y-m-d', strtotime($date));
}

function getMysqlDateTime($datetime)
{
    return date('Y-m-d h:i:s', strtotime($datetime));
}

function checkDateInRange($startDate, $endDate, $userDate)
{
    $start_ts = strtotime($startDate);
    $end_ts = strtotime($endDate);
    $user_ts = strtotime($userDate);

    return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
}

function arrayFlatten(array $array)
{
    $flat = array(); // initialize return array
    $stack = array_values($array); // initialize stack
    while ($stack) // process stack until done
    {
        $value = array_shift($stack);
        if (is_array($value)) // a value to further process
        {
            $stack = array_merge(array_values($value), $stack);
        } else // a value to take
        {
            $flat[] = $value;
        }
    }

    return $flat;
}

function fullURL()
{
    $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
    $sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
    $protocol = substr($sp, 0, strpos($sp, "/")) . $s;
    $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER["SERVER_PORT"]);

    return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
}

function toggleEncryption($text, $key = '')
{
    // return text unaltered if the key is blank
    if ($key == '') {
        $key = '!@a7z%^&*|+';
    }

    // remove the spaces in the key
    $key = str_replace(' ', '', $key);
    if (strlen($key) < 8) {
        exit('key error');
    }
    // set key length to be no more than 32 characters
    $key_len = strlen($key);
    if ($key_len > 32) {
        $key_len = 32;
    }

    $k = array(); // key array
    // fill key array with the bitwise AND of the ith key character and 0x1F
    for ($i = 0; $i < $key_len; ++ $i) {
        $k[$i] = ord($key{$i}) & 0x1F;
    }

    // perform encryption/decryption
    for ($i = 0; $i < strlen($text); ++ $i) {
        $e = ord($text{$i});
        // if the bitwise AND of this character and 0xE0 is non-zero
        // set this character to the bitwise XOR of itself
        // and the ith key element, wrapping around key length
        // else leave this character alone
        if ($e & 0xE0) {
            $text{$i} = chr($e ^ $k[$i % $key_len]);
        }
    }

    return $text;
}

/*
    // usage example
    $results = array();
    $results[] = benchmark('myfunc', 'arg1', 10);
    $results[] = benchmark('myfunc2', array('arg1', 'arg2'));
    $results[] = benchmark('myfunc3');
    print_r($results);

 */
function benchmark($function, $args = null, $iterations = 1, $timeout = 0)
{
    set_time_limit($timeout);

    if (is_callable($function) === true) {
        list($usec, $sec) = explode(" ", microtime());
        $start = ((float) $usec + (float) $sec);

        for ($i = 1; $i <= $iterations; ++ $i) {
            call_user_func_array($function, (array) $args);
        }

        list($usec, $sec) = explode(" ", microtime());
        $end = ((float) $usec + (float) $sec);

        return round(($end - $start), 4);
    }

    return false;
}

function getFilterData($array)
{
    $data = '';

    $array = arrayFlatten($array);

    foreach ($array as $value) {
        $data .= '"' . $value . '",';
    }

    return rtrim($data, ',');
}

function getSlugName($string)
{
    $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $string);
    $clean = strtolower(trim($clean, '-'));
    $clean = preg_replace("/[\/_|+ -]+/", '-', $clean);
    $clean = strtolower(trim($clean, '-'));

    return $clean;
}

function copy_directory($source, $destination)
{
    if (is_dir($source)) {

        @mkdir($destination);
        $directory = dir($source);

        while (false !== ($readdirectory = $directory->read())) {
            if ($readdirectory == '.' || $readdirectory == '..') {
                continue;
            }

            $PathDir = $source . '/' . $readdirectory;

            if (is_dir($PathDir)) {
                copy_directory($PathDir, $destination . '/' . $readdirectory);
                continue;
            }

            copy($PathDir, $destination . '/' . $readdirectory);
        }

        $directory->close();

    } else {
        copy($source, $destination);
    }
}

function rrmdir($dir)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir . "/" . $object) == "dir") {
                    @rrmdir($dir . "/" . $object);
                } else {
                    @unlink($dir . "/" . $object);
                }
            }
        }
        reset($objects);
        rmdir($dir);
    }
}