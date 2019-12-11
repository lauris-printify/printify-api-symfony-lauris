<?php


namespace App\ProductFiles;

/*
 * @desc Validates data describing a product.
 */

class ProductDataValidator
{
    /*
     * @desc Checks if required keys are set.
     * @param array $data_decoded - array containing JSON data for adding a product.
     * @return int - 0 stands for a valid product and any other code is an error
     */

    public function areSetKeys(array $data_decoded): int {
        if (!isset($data_decoded['price'])){
            return (1);
        } elseif (!isset($data_decoded['type'])){
            return (2);
        } elseif(!isset($data_decoded['color'])){
            return (3);
        } elseif(!isset($data_decoded['size'])){
            return (4);
        }
        return (0);
    }

    /*
     * @desc Launch functions to check values of keys.
     * @param array $data_decoded - array containing JSON data for adding a product.
     * @return int - 0 stands for a valid product and any other code is an error
     */

    public function validateKeys(array $data_decoded){
        if (!$this->validatePrice($data_decoded['price'])){
            return (5);
        } elseif (!$this->validateType($data_decoded['type'])){
            return (6);
        } elseif (!$this->validateColor($data_decoded['color'])){
            return (7);
        } elseif (!$this->validateSize($data_decoded['size'])){
            return (8);
        }
        return (0);
    }

    /*
     * @desc Check if price key's value is either integer e.g. 50 or a float e.g. 50.00.
     * @desc Turn the number into string and use regex to check input's validity.
     * Furthermore, price can't be less than 0.
     * @return int - 1 for valid price and 0 for invalid.
     */

    private function validatePrice($price): int{
        if (!is_integer($price) && !is_float($price)){
            return (0);
        }
        $price_string = strval($price);
        if (!preg_match('/^\d*\.?\d{0,2}$/', $price_string) || $price < 0){
            return (0);
        }
        return (1);
    }

    /*
     * @desc Check if type key's value is a string.
     * @desc The type must be one of the listed in the function.
     * @return int - 1 for valid type and 0 for invalid.
     */

    private function validateType($type): int{
        if (!is_string($type)){
            return (0);
        }
        if (strcmp("t-shirt", $type) && strcmp("socks", $type)
            && strcmp("hoodie", $type) && strcmp("beanie", $type)
            && strcmp("slippers", $type) && strcmp("jacket", $type)){
            return (0);
        }
        return (1);
    }

    /*
     * @desc Check if color key's value is a string.
     * @desc The color must be one of the listed in the function.
     * @return int - 1 for valid color and 0 for invalid.
     */

    private function validateColor($color): int{
        if (!is_string($color)){
            return (0);
        }
        if (strcmp("black", $color) && strcmp("white", $color)
            && strcmp("blue", $color) && strcmp("red", $color)
            && strcmp("yellow", $color) && strcmp("purple", $color)){
            return (0);
        }
        return (1);
    }

    /*
     * @desc Check if size key's value is a string.
     * @desc The size must be one of the listed in the function.
     * Size can also be denoted by a number.
     * @return int - 1 for valid size and 0 for invalid.
     */

    private function validateSize($size): int{
        if (!is_string($size)){
            return (0);
        }
        if (strcmp("XS", $size) && strcmp("S", $size)
            && strcmp("M", $size) && strcmp("L", $size)
            && strcmp("XL", $size) && strcmp("XXL", $size)
            && !intval($size) <= 0){
            return (0);
        }
        return (1);
    }
}