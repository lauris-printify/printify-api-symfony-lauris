<?php

namespace App\Controller;

use App\DataGetter;
use App\Entity\Order;
use App\Entity\ProductOrderRelation;
use App\InvoiceFiles\Invoice;
use App\KernelRequest;
use App\OrderFiles\OrderDataValidator;
use App\OrderFiles\OrderErrorInfo;
use App\OrderFiles\OrdersRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class OrdersController extends AbstractController
{
    /** Add an order
     * @Route("/orders", name="add_order", methods={"POST"})
     * @param Request $request
     * @param KernelRequest $requestInfo
     * @param Environment $twig
     * @return JsonResponse|Response
     * @throws Exception
     */

    public function add_order(Request $request, KernelRequest $requestInfo, Environment $twig) : Response {
        /* Get data and see if it is valid JSON */
        $dataGetter = new DataGetter($request);
        $data = $dataGetter->get_data();
        if ($data == null){
            return new Response(json_encode(['error' => "Incorrect JSON data"]), Response::HTTP_BAD_REQUEST);
        }
        /* Make sure keys and values are valid */
        $errorInfo = new OrderErrorInfo();
        $em = $this->getDoctrine()->getManager();
        $dataValidator = new OrderDataValidator($em);
        $keysStatus = $dataValidator->areKeysSet($data);
        if ($keysStatus != 0){
            return new Response(json_encode(['error' => $errorInfo->errorInfo($keysStatus)]), Response::HTTP_BAD_REQUEST);
        }
        $keysValueStatus = $dataValidator->validateKeys($data);
        if ($keysValueStatus != 0)
            return $this->json(['error' => $errorInfo->errorInfo($keysValueStatus)], Response::HTTP_BAD_REQUEST);
        /* Insert new order ID in 'order' database */
        $order = new Order($data['name'], $data['street'], $data['city'], $data['country'], $data['postalcode'], new \DateTime('now'));
        $em->persist($order);
        $em->flush();
        $orderID = $order->getId();
        /* Insert each product within the order in 'product_order_relation' table to tie it to order id. Ignore shipping address data. */
        foreach($data as $key => $value){
            if ($key != 'name' && $key != 'street' && $key != 'city' && $key != 'country' && $key != 'postalcode') {
                $productionOrderRelation = new ProductOrderRelation(intval($key), intval($value), $orderID, new \DateTime('now'));
                $em->persist($productionOrderRelation);
                $em->flush();
            }
        }
        /* After order has been created, create invoice and display it */
        $invoiceGenerator = new Invoice($twig, $requestInfo);
        $ordersRepository = new OrdersRepository($em);
        $products = $ordersRepository->orderArray($orderID, '');
        $invoice = $invoiceGenerator->generateInvoiceHtml($products);
        return new Response($invoice, Response::HTTP_CREATED);
    }

    /** View order by it's id or view all products if id is not provided
     * @Route("/orders/{id?}", name="view_order", methods={"GET"})
     * @param $id
     * @param Request $request
     * @return Response
     */

    public function view_order($id, Request $request): Response {
        /* If id is set, then display specific order. Otherwise, list all orders */
        $em = $this->getDoctrine()->getManager();
        $ordersRepository = new OrdersRepository($em);
        if (isset($id) && !empty($ordersRepository->orderArray($id, ''))){
            return new Response(json_encode($ordersRepository->orderArray($id, '')), Response::HTTP_OK);
        } elseif (isset($id)){
            return new Response(json_encode(['Error' => 'Such order does not exist']), Response::HTTP_BAD_REQUEST);
        } else {
            $type = '';
            if ($request->query->get('type')){
                $type = $request->query->get('type');
            }
            $all_orders = array();
            $em = $this->getDoctrine()->getManager();
            $allIds = $em->getRepository(Order::class)->findAll();
            foreach ($allIds as $orderId){
                $order = $ordersRepository->orderArray($orderId->getId(), $type);
                if ($order)
                    array_push($all_orders, $order);
            }
            return new Response(json_encode($all_orders), Response::HTTP_OK);
        }
    }
}
