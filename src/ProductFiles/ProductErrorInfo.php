<?php


namespace App\ProductFiles;

/*
 * @desc Translate product related error codes into their meaning
*/

class ProductErrorInfo
{
    /*
     * @desc Translate product related error codes into their meaning
     * @param int $error - error code
     * @return string describing the error code
     */

    public function errorInfo(int $error): string {
        if ($error == 1){
            return "Price field not set";
        } elseif ($error == 2){
            return "Type field not set";;
        } elseif ($error == 3){
            return "Color field not set";
        } elseif ($error == 4){
            return "Size field not set";
        } elseif ($error == 5){
            return "Invalid price.";
        } elseif ($error == 6){
            return "Invalid type.";
        } elseif ($error == 7){
            return "Invalid color.";
        } elseif ($error == 8){
            return "Invalid size.";
        }
    }
}