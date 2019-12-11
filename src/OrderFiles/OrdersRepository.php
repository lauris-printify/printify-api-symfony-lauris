<?php


namespace App\OrderFiles;


use App\Entity\Order;
use App\Entity\Product;
use App\Entity\ProductOrderRelation;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;

/*
 * @desc Useful functions related to products database
 */

class OrdersRepository
{
    private $em;

    /*
     * @param ObjectManager $em - injecting service to access orders repository
     */

    public function __construct(ObjectManager $em) {
        $this->em = $em;
    }

    /*
     * @desc Return order array where each array within it is product of the order
     * @param int $id - id of the order
     * @param string $type - optional, by default is empty '' but can denote a type of product within order.
     * In that case, we only list orders that contain a product of $type.
     * @return array with error message if invalid order id was passed in OR
     * @return array with message if no order with given type of product exists OR
     * @return array with products.
     */

    public function orderArray(int $id, string $type): array{
        /* Find order with given id */
        $order = $this->em->getRepository(Order::class)->findOneBy([
            "id" => $id
        ]);
        if (!$order)
            return array();
        /* Find all entries from ProductOrderRelation table aka products from given order id */
        $order_rows = $this->em->getRepository(ProductOrderRelation::class)->findBy([
            "orderId" => $id
        ]);
        /* If orders with certain type products in them are chosen, check if that order has that type of product */
        if ($type != ''){
            $orderContainsTypeProduct = $this->orderContainsTypeProduct($order_rows, $type);
            if ($orderContainsTypeProduct == 0)
                return array();
        }
        /* Iterate through each object to get product ID and retrieve product with such information from 'product' table */
        $all_products = array();
        $order_price = 0;
        foreach ($order_rows as $order_row){
            $product = array();
            $productId =  $order_row->getProductId();
            $product_object = $this->em->getRepository(Product::class)->findOneBy([
                "id" => $productId
            ]);
            $product['orderId'] = $id;
            $product['productId'] = $productId;
            $product['productPrice'] = $product_object->getPrice();
            $product['productQuantity'] = (string)$order_row->getQuantity();
            $product['totalProductPrice'] = (string)($product['productPrice'] * intval($product['productQuantity']));
            $product['productType'] = $product_object->getType();
            $product['productColor'] = $product_object->getColor();
            $product['productSize'] = $product_object->getSize();
            $product['name'] = $order->getName();
            $product['street'] = $order->getStreet();
            $product['city'] = $order->getCity();
            $product['country'] = $order->getCountry();
            $product['postalcode'] = $order->getPostalcode();
            $product['createdAt'] = $order->getCreatedAt()->format('Y-m-d');
            $order_price += intval($product['totalProductPrice']);
            array_push($all_products, $product);
        }
        foreach ($all_products as &$product){
            $product['totalOrderPrice'] = (string)$order_price;
        }
        return $all_products;
    }

    /*
     * @desc Check if order contains a product of a given type.
     * @param array $order_rows - array containing information about which products belong to given order
     * @param string $type - denotes a type of product within order.
     * @return int 0 if no product of $type exists in order's products
     * @return int 1 if product of $type exists in order
     */

    public function orderContainsTypeProduct(array $order_rows, string $type): int {
        $continue = 0;
        foreach ($order_rows as $order_row){
            $productId =  $order_row->getProductId();
            $product_object = $this->em->getRepository(Product::class)->findOneBy([
                "id" => $productId
            ]);
            if ($product_object->getType() != $type)
                continue;
            $continue = 1;
            break;
        }
        if ($continue == 0)
            return (0);
        return (1);
    }
}