<?php

namespace Alba\Core\Utils;

class StringUtils {


    /**
     * Generates a GUID string
     *
     * @param $boolean $withSeparators If false, removes the characters { } - from the resulting GUID
     * @return string The GUID
     */
    public static function generateGuid($withSeparators = true) {

        if (function_exists('com_create_guid')){
            $token = com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = chr(123)// "{"
                    .substr($charid, 0, 8).$hyphen
                    .substr($charid, 8, 4).$hyphen
                    .substr($charid,12, 4).$hyphen
                    .substr($charid,16, 4).$hyphen
                    .substr($charid,20,12)
                    .chr(125);// "}"
            $token = $uuid;
        }
        
        if (!$withSeparators) {
            //remove the {, }, and - characters
            $token = str_replace('{', '', str_replace('}', '', str_replace('-', '', $token)));
        }

        return $token;

    }


}