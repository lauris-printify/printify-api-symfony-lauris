<?php


namespace App;


use App\Entity\CountryCodes;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;

/*
 * @desc Custom service App\KernelRequest denoted in services.yaml.
 * It is started before controllers are launched.
 * It's purpose is to restrict requests from a country
 * as denoted by $requestsPerMinuteLimit under App\KernelRequest in services.yaml.
*/

class KernelRequest extends AbstractController
{
    private $requestsPerMinuteLimit;
    private $ipStackKey;
    private $ipUser;
    private $ipStackUrl;
    private $request;
    private $requestStack;
    private $countryCode;
    private $em;

    /*
     * @param RequestStack $requestStack - injecting service to access information about the request
     * @param ObjectManager $em - injecting service to access orders repository
     * @param int $requestsPerMinuteLimit - Variable denoting connections per minute from a country. It is passed as a
     * variable from services.yaml.
     * @param string $ipStackKey - API key used to access 'ip stack' geolocation service.
     */

    public function __construct(RequestStack $requestStack, ObjectManager $em, int $requestsPerMinuteLimit, string $ipStackKey) {
        $this->requestStack = $requestStack;
        $this->request = $this->requestStack->getCurrentRequest();
        $this->requestsPerMinuteLimit = $requestsPerMinuteLimit;
        $this->ipStackKey = $ipStackKey;
        $this->ipUser = $this->request->getClientIp();
        $this->ipStackUrl = "http://api.ipstack.com/" . $this->ipUser . "?access_key=" . $this->ipStackKey . "&fields=country_code";
        $this->em = $em;
    }

    /*
     * @desc Once kernel detects a request to our API, we get country of the request and
     * check in CountryCodes repository if within last minute there has not been more than $requestsPerMinuteLimit requests.
     * If there has not been, the request is passed on to controllers. Otherwise, message is displayed and API stopped.
     */

    public function onKernelRequest(): void {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->ipStackUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $responseDecoded = json_decode($response);
        $this->countryCode = $responseDecoded->country_code;
        if (strlen($this->countryCode) < 1)
            $this->countryCode = "US";
        $country = $this->em->getRepository(CountryCodes::class)->findOneBy([
            "country" => $this->countryCode
        ]);
        if ($country->getRequests() < $this->requestsPerMinuteLimit){
            $country->setRequests($country->getRequests() + 1);
            $this->em->persist($country);
            $this->em->flush();
        } else {
            die ($this->requestsPerMinuteLimit . " per minute are allowed");
        }
    }

    /*
     * @return Country code (two letters) of the incoming request. "US" by default.
     */

    public function getCountryCode() : string {
        return $this->countryCode;
    }
}