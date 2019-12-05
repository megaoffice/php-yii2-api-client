<?php

namespace megaoffice\client\src;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;

class Component extends \yii\base\Component
{
    public $token = '';

    public $pageSize = 20;
    public $url = 'https://api.megaoffice.pro';

    public function init()
    {
        if(\Yii::$app->params['domain']){
            $this->url      = 'http://api.balance.loc';
            $this->token    = 'testoken';
        }
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
            $condString = '?per-page=500&filter='.json_encode($condition);
        }else if(is_string($condition) && strlen($condition) > 0){
            $condString = '?per-page=500&filter='.$condition;
        }else{
            $condString = '';
        }
        $response = $client->request('GET', $this->url . $endpoint.$condString, [
            'headers' => $headers,
        ]);
        return  json_decode($response->getBody(), true);
    }

    public function insert($endpoint, $values){

        $client = new Client();
        $headers = [
            'X-Api-Key'     => '' .$this->token,
            'Accept'        => 'application/json',
            'Cache-Control'        => '',
        ];
//        if(is_array($condition) && count($condition)>0){
//            $condString = '?per-page=500&filter='.json_encode($condition);
//        }else if(is_string($condition) && strlen($condition) > 0){
//            $condString = '?per-page=500&filter='.$condition;
//        }else{
//            $condString = '';
//        }
        try {
            $response = $client->post($this->url . '/' . $endpoint, [
                'headers' => $headers,
                'form_params' => $values,
            ]);
            $res = json_decode($response->getBody(), true);
        }catch (RequestException $e){
            $res = json_decode($e->getResponse()->getBody(), true);
            return false;
        }
        return $res;
    }
}