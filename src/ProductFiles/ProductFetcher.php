<?php


namespace App\ProductFiles;


use App\Entity\Product;
use Doctrine\Common\Persistence\ObjectManager;

/*
 * @desc Fetch a product given a set of characteristics.
*/

class ProductFetcher
{
    private $em;

    public function __construct(ObjectManager $em) {
        $this->em = $em;
    }

    /*
     * @desc Fetch a product given a set of characteristics.
     * @param array $data - array containing description of a product
     * @return null if given product does not exist. Otherwise, return product.
     */

    public function fetchProduct(array $data){
        return $this->em->getRepository(Product::class)->findBy([
            'type' => $data['type'],
            'color' => $data['color'],
            'size' => $data['size'],
        ]);
    }
}