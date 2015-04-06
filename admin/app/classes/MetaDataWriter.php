<?php

/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 3/30/2015
 * Time: 4:50 PM
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
        $jsonPretty = new Camspiers\JsonPretty\JsonPretty;
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