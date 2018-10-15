<?php

namespace Application\Model;
use ZendService\Twitter\Twitter;

class TwitterApi
{
    protected $twitter;

    public function __construct()
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

        $this->twitter = new Twitter($config);
    }

    public function getUserData($userId)
    {
        try {
            $response = $this->twitter->account->accountVerifyCredentials();

            if (!$response->isSuccess()) {
                die(var_dump($response->getErrors()));
            }

            $profile = $this->twitter->users->show($userId, ['include_entities' => false]);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 1);
        }

        if ($profile->errors) {
            throw new \Exception($profile->errors[0]->message, $profile->errors[0]->code);
        }

        return $profile;
    }
}
