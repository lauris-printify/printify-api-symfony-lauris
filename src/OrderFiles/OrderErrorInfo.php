<?php


namespace App\OrderFiles;

/*
 * @desc Translate order related error codes into their meaning
*/

class OrderErrorInfo
{
    /*
     * @desc Translate order related error codes into their meaning
     * @param int $error - error code
     * @return string describing the error code
     */

    public function errorInfo(int $error): string {
        if ($error == 1){
            return "Name field not set";
        } elseif($error == 2){
            return "Street field not set";
        } elseif($error == 3){
            return "City field not set";
        } elseif($error == 4){
            return "Country field not set";
        } elseif($error == 5){
            return "Postal code field not set";
        } elseif($error == 6){
            return "Quantity must be defined within a string";
        } elseif ($error == 7){
            return "Invalid key for product id";
        } elseif ($error == 8){
            return "Invalid value for quantity";
        } elseif ($error == 9){
            return "Invalid product ID within the order";
        } elseif ($error == 10){
            return "There must be at least 1 product";
        } elseif ($error == 11){
            return "Order value must be at least 10";
        }
    }
}