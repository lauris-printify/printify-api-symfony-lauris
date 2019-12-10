<?php


namespace App\ProductFiles;


class ProductDataStandardise
{
    /*
     * @desc Given a product to submit, make it's type and color lowercase and size uppercase.
     * @param array &$data - address of array containing product
     */

    public function standardizeData(array &$data): void {
        $data['type'] = strtolower($data['type']);
        $data['color'] = strtolower($data['color']);
        $data['size'] = strtoupper($data['size']);
    }
}