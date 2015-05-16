<?php namespace BloggerCMS;

use Camspiers\JsonPretty\JsonPretty;

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
class MetaDataWriter
{
    public static function getFileData($file)
    {
        $data = array();

        if (file_exists($file)) {
            $contents = file_get_contents(
               $file
            ) or die("Unable to open file " . $file);

            $data = json_decode($contents, true);
        }

        return $data;
    }

    public static function writeData($file, $data)
    {
        $jsonPretty = new JsonPretty();
        $data = $jsonPretty->prettify($data);

        if (!file_put_contents($file, $data)) {
            return false;
        }
    }

    public static function updateFileData($file, $post, $append = false)
    {
        // if file doesn't exists, save settings in new file
        if (!file_exists($file)) {
            $h = fopen(
               $file,
               "w"
            ) or die("Unable to open file " . $file);

            if ($append) {
                $data[] = $post;
            } else {
                $data = $post;
            }

            fwrite($h, json_encode($data));
            fclose($h);

        } // file exists, update settings with new options
        else {

            $contents = file_get_contents(
               $file
            ) or die("Unable to open file " . $file);

            if ($append) {
                $array = json_decode($contents, true);
                $array[] = $post;
            } else {
                $array = json_decode($contents, true);

                foreach ($array as $key => $value) {
                    $array[$key] = $post[$key];
                }
            }

            self::writeData($file, $array);
        }
    }

    public static function updateItemData($file, $id, $value, $sort = false)
    {
        $data = MetaDataWriter::getFileData($file);

        $array[] = $data;
        $array = arrayFlatten($array);
        $array = array_unique($array);

        if ($sort) {
            sort($array);
        }

        $array[$id] = $value;

        self::writeData($file, $array);
    }

    public static function removeItemData($file, $id, $sort = false)
    {
        $data = MetaDataWriter::getFileData($file);

        $array[] = $data;
        $array = arrayFlatten($array);
        $array = array_unique($array);

        if ($sort) {
            sort($array);
        }

        // remove item
        unset($array[$id]);

        self::writeData($file, $array);
    }

    public static function addDataFlat($file, $post)
    {
        // if file doesn't exists, save in new file
        if (!file_exists($file)) {
            $h = fopen(
               $file,
               "w"
            ) or die("Unable to open file " . $file);


            $data = array();
            foreach ($post as $value) {
                $data[0] = $value;
            }

            fwrite($h, json_encode($data));
            fclose($h);

        } // file exists, update with new options
        else {
            $data = MetaDataWriter::getFileData($file);

            $array[] = $data;
            $array[] = $post;
            $array = arrayFlatten($array);
            $array = array_unique($array);

            self::writeData($file, $array);
        }
    }

    public static function addData($file, $post)
    {
        // if file doesn't exists, save in new file
        if (!file_exists($file)) {
            $h = fopen(
               $file,
               "w"
            ) or die("Unable to open file " . $file);


            $data = array();
            foreach ($post as $value) {
                $data[] = $value;
            }

            fwrite($h, json_encode($data));
            fclose($h);

        } // file exists, update with new options
        else {
            $data = MetaDataWriter::getFileData($file);

            $array[] = $data;
            $array[] = $post;
            $array = arrayFlatten($array);
            $array = array_unique($array);

            self::writeData($file, $array);
        }
    }
}