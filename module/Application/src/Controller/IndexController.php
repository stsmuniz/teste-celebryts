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
use ZendService\Twitter\Twitter;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function twitterAction()
    {
        $config = array(
            'access_token' => array(
                'token' => '480272359-1LmMO4LQVPCFjba9h2CBnUD7pfOc5hhtVBSFRRIU',
                'secret' => 'zxPcAS0a7HjcsBag4IEcUTVKYD2y7k3DNv5MuwGfe19Rk',
            ),
            'oauth_options' => array(
                'consumerKey' => 'iN9fspzncW5fRaXkG34CBvxig',
                'consumerSecret' => 'zw6C8G5ACXSnUP9U3XRMKaFYPKERPAAsCppEqyqRzIgkocJkKX',
            ),
            'http_client_options' => array(
                'adapter' => 'Zend\Http\Client\Adapter\Curl',
                'curloptions' => array(
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_SSL_VERIFYPEER => false,
                ),
            ),
        );

        $twitter = new Twitter($config);

        $response = $twitter->account->accountVerifyCredentials();
        if (!$response->isSuccess()) {
            die(var_dump($response->getErrors()));
        }
        $params = $this->params()->fromRoute();
        $profile = $params['profile'];


        $profile = $twitter->users->show($profile, ['include_entities' => false]);

//        die(json_encode($response));

        return new JsonModel((array)$profile
//            [
//                'placeName' => $profile->location,
//                'link' => 'https://www.google.com/maps/search/?api=1&query=' . $profile->location
//            ]
        );
    }
}
