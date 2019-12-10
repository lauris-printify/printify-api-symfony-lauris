<?php

namespace App\Controller;

use App\DataGetter;
use App\ProductFiles\ProductDataStandardise;
use App\ProductFiles\ProductDataValidator;
use App\Entity\Product;
use App\ProductFiles\ProductErrorInfo;
use App\ProductFiles\ProductFetcher;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductsController extends AbstractController
{
    /** Add a product
     * @Route("/products", name="add_product", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */

    public function add_product(Request $request) : JsonResponse {
        /* Get data and see if it is valid JSON */
        $dataGetter = new DataGetter($request);
        $data = $dataGetter->get_data();
        if ($data == null){
            return $this->json(['error' => "Incorrect JSON data"], Response::HTTP_BAD_REQUEST);
        }
        /* Make sure keys and values are valid */
        $dataValidator = new ProductDataValidator();
        $dataStandardise = new ProductDataStandardise();
        $errorInfo = new ProductErrorInfo();
        $keysStatus = $dataValidator->areSetKeys($data);
        if ($keysStatus != 0){
            return $this->json(['error' => $errorInfo->errorInfo($keysStatus)], Response::HTTP_BAD_REQUEST);
        }
        /* Before validating value of the keys, standardise them */
        $dataStandardise->standardizeData($data);
        $keysValueStatus = $dataValidator->validateKeys($data);
        if ($keysValueStatus != 0){
            return $this->json(['error' => $errorInfo->errorInfo($keysValueStatus)], Response::HTTP_BAD_REQUEST);
        }
        /* Check if product with given type, color and size does not exist already */
        $em = $this->getDoctrine()->getManager();
        $productFetcher = new ProductFetcher($em);
        if ($productFetcher->fetchProduct($data)){
            return $this->json(['error' => "Product already exists"], Response::HTTP_BAD_REQUEST);
        }
        /* Create new product and insert it into database */
        $product = new Product($data['price'], $data['type'], $data['color'], $data['size'], new \DateTime('now'));
        $em->persist($product);
        $em->flush();
        return $this->json(['success' => 'Product successfully inserted'], Response::HTTP_CREATED);
    }

    /** View product by it's id or view all products if id is not provided
     * @Route("/products/{id?}", name="view_product", methods={"GET"})
     * @param $id
     * @return JsonResponse
     */

    public function view_product($id) : JsonResponse {
        /* If id is set, then display specific product. Otherwise, list all products */
        $em = $this->getDoctrine()->getManager();
        if (isset($id)){
            $product = $em->getRepository(Product::class)->findOneBy([
                "id" => $id
            ]);
            if (!$product)
                return $this->json(['Error' => 'Such product does not exist'], Response::HTTP_BAD_REQUEST);
            return $this->json([$product], Response::HTTP_OK);
        } else {
            $products = $em->getRepository(Product::class)->findAll();
            return $this->json([$products], Response::HTTP_OK);
        }
    }
}
