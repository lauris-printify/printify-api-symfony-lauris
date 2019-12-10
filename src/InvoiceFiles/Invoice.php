<?php


namespace App\InvoiceFiles;
use App\KernelRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Environment;

/*
 * @desc Generates HTML invoice given an order.
*/

class Invoice extends AbstractController
{
    private $twig;
    private $requestInfo;

    /*
     * @param Environment $twig - injecting twig service to render html given data
     * @param KernelRequest $requestInfo - Custom class saving client's origin country for invoice.
     * Created using symfony events, thus exists even before controllers are launched.
     */

    public function __construct(Environment $twig, KernelRequest $requestInfo) {
        $this->twig = $twig;
        $this->requestInfo = $requestInfo;
    }

    /*
     * @desc - Generates HTML invoice given products of an order.
     * @param array $products - two dimensional array where each array stores ordered product
     * @return string containing HTML of the invoice
     */

    public function generateInvoiceHtml(array $products): string{

        return $this->twig->render('invoice.html.twig', [
            'orderId' => $products[0]['orderId'],
            'date' => $products[0]['createdAt'],
            'countryCode' => $this->requestInfo->getCountryCode(),
            'name' => $products[0]['name'],
            'street' => $products[0]['street'],
            'city' => $products[0]['city'],
            'countryName' => $products[0]['country'],
            'postalcode' => $products[0]['postalcode'],
            'totalOrderPrice' => $products[0]['totalOrderPrice'],
            'order_array' => $products,
        ]);
    }
}