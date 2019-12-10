<?php


namespace App;


use Symfony\Component\HttpFoundation\Request;

class DataGetter
{
    private $request;

    /*
     * @param Request $request - injecting request service to acquire data about request
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }
    /*
     * @desc Get POSTed JSON data.
     * @return JSON data as an associative array OR
     * @return null if an error occurred
     */
    public function get_data(): array {
        $data_json = $this->request->getContent();
        $data_decoded = json_decode($data_json, true);
        if (json_last_error() === JSON_ERROR_NONE){
            return ($data_decoded);
        }
        return (null);
    }
}