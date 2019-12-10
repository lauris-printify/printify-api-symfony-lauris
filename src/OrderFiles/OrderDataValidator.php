<?php


namespace App\OrderFiles;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;

/*
 * @desc Validates data describing an order.
 */

class OrderDataValidator extends AbstractFOSRestController
{
    private $em;

    /*
     * @param ObjectManager $em - injecting service to access orders repository
     */
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    /*
     * @desc Checks if required keys are set and validates their values.
     * @param array $data_decoded - array containing JSON data for adding a product.
     * @return int - 0 stands for a valid product and any other code is an error
     */

    public function areKeysSet(array $data_decoded): int {
        if (!isset($data_decoded['name'])){
            return (1);
        } elseif (!isset($data_decoded['street'])){
            return (2);
        } elseif(!isset($data_decoded['city'])){
            return (3);
        } elseif(!isset($data_decoded['country'])){
            return (4);
        } elseif(!isset($data_decoded['postalcode'])){
            return (5);
        }
        return (0);
    }

    /*
     * @desc Validate keys' values and check if order has at least 1 product and is worth at least 10 currency units.
     * @param array $data_decoded - array containing JSON data for adding a product.
     * @return int - 0 stands for a valid product and any other code is an error
     */

    public function validateKeys(array $data_decoded): int {
        $products = 0;
        $productsValue = 0;
        foreach ($data_decoded as $key => $value){
            if ($key != 'name' && $key != 'street' && $key != 'city' && $key != 'country' && $key != 'postalcode'){
                /* Check if values of keys are in string format. Ignore address keys. */
                if (!is_string($value))
                    return (6);
                $key = intval($key);
                $value = intval($value);
                /* Check if orderId and it's quantity are valid integers. */
                if (!is_integer($key) || $key <= "0")
                    return (7);
                elseif (!is_integer($value) || $value <= "0" || $value > "1000")
                    return (8);
                /* Check if product id is valid */
                $product = $this->em->getRepository(Product::class)->findOneBy(["id" => intval($key)]);
                if (!$product)
                    return (9);
                $productsValue += $product->getPrice() * intval($value);
                $products++;
            }
        }
        /* Check if order consists of at least 1 product. */
        if ($products == 0)
            return (10);
        /* Check if order value is at least 10 currency units. */
        if ($productsValue < 10)
            return (11);
        return (0);
    }
}