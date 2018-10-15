<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Application\Model\TwiterApi;
use Application\Model\GoogleMapsApi;

class IndexController extends AbstractActionController
{

    protected $twitter;

     public function __construct()
     {
        $this->twitter = new \Application\Model\TwitterApi();
     }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function twitterAction()
    {
        try {
            $params = $this->params()->fromRoute();
            $userId = $params['profile'];
        } catch (Exception $e) {
            die($e->getMessage);
        }

        try {
            $profile = $this->twitter->getUserData($userId);
        } catch (\Exception $e) {
            $response = $this->getResponse();
            $response->setCustomStatusCode(404);
            $response->setReasonPhrase('Error on searching twitter user:' . $e->getMessage());

            return $response;
        }

        $location = (new GoogleMapsAPI())->getAddress($profile->location);

        $latitude = $location->getLatitude();
        $longitude = $location->getLongitude();

        return new JsonModel(
           [
               'placeName' => $location->getFormattedAddress(),
               'link' => 'https://www.google.com/maps/place/'.$latitude.','.$longitude
           ]
        );
    }
}
