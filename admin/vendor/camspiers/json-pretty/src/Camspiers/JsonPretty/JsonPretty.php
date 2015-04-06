<?php

namespace Camspiers\JsonPretty;

class JsonPretty
{
    /**
     * Checks if input is string, if so, straight runs
     * process, else it encodes the input as json then
     * runs prettify.
     *
     * @param  mixed  $json   The json string or object to prettify
     * @param  int    $flags  The flags to use in json encoding
     * @param  string $indent The indentation character string
     * @return string The prettified json
     */
    public function prettify($json, $flags = null, $indent = "\t")
    {
        if (!$this->isJson($json)) {
            return $this->process(json_encode($json, $flags), $indent);
        }

        return $this->process($json, $indent);
    }

    /**
     * Makes a json string pretty
     * Function adapted from umbrae [at] gmail [dot] com
     * at http://php.net/manual/en/function.json-encode.php
     *
     * @param  string $json   A json string to prettify
     * @param  string $indent A json string to prettify
     * @return string The prettified json string
     */
    protected function process($json, $indent = "\t")
    {
        $result = '';
        $indentCount = 0;
        $inString = false;
        $len = strlen($json);
        for ($c = 0; $c < $len; $c++) {
            $char = $json[$c];
            if ($char === '{' || $char === '[') {
                if (!$inString) {
                    $indentCount++;
                    if ($char === '[' && $json[$c+1]  == "]") {
                        $result .= $char . PHP_EOL;
                    } elseif ($char === '{' && $json[$c+1]  == "}") {
                        $result .= $char . PHP_EOL;
                    } else {
                        $result .= $char . PHP_EOL . str_repeat($indent, $indentCount);
                    }
                } else {
                    $result .= $char;
                }
            } elseif ($char === '}' || $char === ']') {
                if (!$inString) {
                    $indentCount--;
                    $result .= PHP_EOL . str_repeat($indent, $indentCount) . $char;
                } else {
                    $result .= $char;
                }
            } elseif ($char === ',') {
                if (!$inString) {
                    $result .= ',' . PHP_EOL . str_repeat($indent, $indentCount);
                } else {
                    $result .= $char;
                }
            } elseif ($char === ':') {
                if (!$inString) {
                    $result .= ': ';
                } else {
                    $result .= $char;
                }
            } elseif ($char === '"') {
                if ($c > 0 && $json[$c - 1] !== '\\') {
                    $inString = !$inString;
                }
                $result .= $char;
            } else {
                $result .= $char;
            }
        }
        return $result;
    }

    /**
     * Check if a string is a json or not
     *
     * @param  string  $string
     * @return boolean
     */
    protected function isJson($string)
    {
        if (!is_string($string)) {
            return false;
        }
        json_decode($string);

        return json_last_error() == JSON_ERROR_NONE;
    }
}