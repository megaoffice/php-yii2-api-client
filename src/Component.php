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
            $res = [
                'status' => 'ok',
                'response' => json_decode($response->getBody(), true)
            ];
        }catch (RequestException $e){
            $res = [
                'status' => 'error',
                'response' => json_decode($e->getResponse()->getBody(), true)
            ];
        }

        return $res;
    }

    public function update($id, $endpoint, $values){

        $client = new Client();
        $headers = [
            'X-Api-Key'     => '' .$this->token,
            'Accept'        => 'application/json',
            'Cache-Control'        => '',
        ];
        try {
            $response = $client->put($this->url . '/' . $endpoint.'/'.$id, [
                'headers' => $headers,
                'form_params' => $values,
            ]);
            $res = [
                'status' => 'ok',
                'response' => json_decode($response->getBody(), true)
            ];
        }catch (RequestException $e){
            $res = [
                'status' => 'error',
                'response' => json_decode($e->getResponse()->getBody(), true)
            ];
        }

        return $res;
    }

    public function applyPromocode($sale, $promocode){
        $client = new Client();
        $headers = [
            'X-Api-Key'     => '' .$this->token,
            'Accept'        => 'application/json',
            'Cache-Control'        => '',
        ];
        try {
            $response = $client->post($this->url . '/promo/code/apply-to-sale', [
                'headers' => $headers,
                'form_params' => [
                    'sale' => is_object($sale) ? $sale->toArray() : $sale,
                    'code' => $promocode,
                    ],
            ]);
            $res = [
                'status' => 'ok',
                'response' => json_decode($response->getBody(), true)
            ];
        }catch (RequestException $e){
            $res = [
                'status' => 'error',
                'response' => json_decode($e->getResponse()->getBody(), true)
            ];
        }

        return $res;

    }

}