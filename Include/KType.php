<?php
namespace KKur;

class KType {

    public static function stringToBool(string $str) : bool {
        if (strcmp($str, "true") == 0) {
            return true;
        } else if (strcmp($str, "false") == 0) {
            return false;
        }
        return false;
    }

}