<?php

namespace megaoffice\client\src;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Component extends \yii\base\Component
{
    public $token = '';

    public $pageSize = 20;
    public $url = 'https://api.megaoffice.pro';

    public function init()
    {
        parent::init();
    }
    public function test() {
        $a = 5;
    }

    /**
     * @param $endpoint
     * @param $condition
     * @return mixed
     * @throws GuzzleException
     */
    public function query($endpoint, $condition  = []){
        $client = new Client();
        $headers = [
            'X-Api-Key'     => '' .$this->token,
            'Accept'        => 'application/json',
            'Cache-Control'        => '',
        ];
        if(is_array($condition) && count($condition)>0){
            $condString = '?per-page=100&filter='.json_encode($condition);
        }else if(is_string($condition) && strlen($condition) > 0){
            $condString = '?per-page=100&filter='.$condition;
        }else{
            $condString = '';
        }
        $response = $client->request('GET', $this->url . $endpoint.$condString, [
            'headers' => $headers,
        ]);
        return  json_decode($response->getBody(), true);
    }
}