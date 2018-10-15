<?php

namespace Application\Model;
use Zend\Http\Client;

class GoogleMapsApi
{
    protected $client;
    protected $clientConfig;
    protected $gmapsKey;
    protected $location;
    protected $latitude;
    protected $longitude;
    protected $formattedAddress;

    public function __construct()
    {
        $this->gmapsKey = 'AIzaSyBZ5ai53Rhc6zM_x_1Dg0Iv8R-gWIaofd8';
        $this->client = new Client();
        $this->clientConfig = array(
            'adapter'   => 'Zend\Http\Client\Adapter\Curl',
            'curloptions' => array(
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_VERBOSE => 1,
                CURLOPT_STDERR => fopen('/tmp/curl.txt', 'a+')
            )
        );
    }

    public function getAddress($address)
    {

        $this->client->setUri('https://maps.google.com/maps/api/geocode/json', $this->clientConfig);

        if (preg_match('/[0-9]{2,3}.[0-9]{5,}[ ,]/', $address, $match)) {
            $address = preg_replace('/[^0-9,.-]/', '', $address);
        }

        $this->client->setParameterGet(array(
           'address'  => $address,
           'key' => $this->gmapsKey
        ));

        try {
            $response = $this->client->send();
        } catch(\Exception $e) {
            throw new \Exception("Error Processing Request: " . $e->getMessage(), 1);
        }

        $this->location = json_decode($response->getBody());

        $coordinates = $this->location->results[0]->geometry->location;

        $this->latitude = $coordinates->lat;
        $this->longitude = $coordinates->lng;
        $this->formattedAddress = $this->location->results[0]->formatted_address;

        return $this;
    }

    public function getFormattedAddress() {
        return $this->formattedAddress;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }
}
//
