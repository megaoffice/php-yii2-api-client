<?php


namespace megaoffice\client\models;


use GuzzleHttp\Client;
use yii\base\Component;

class MOCommand extends Component
{
    public $moQuery;
    public $query;


    public function queryAll(){

        $client = new Client();
        $headers = [
            'X-Api-Key'     => '' .\Yii::$app->megaofficeClient->token,
            'Accept'        => 'application/json',
            'Cache-Control'        => '',
        ];
        $condition = $this->buildCondition();

        $delimiter = '?';
        $limit = '';

        if($this->moQuery->limit > 0){
            $limit = $delimiter.'per-page='.intval($this->moQuery->limit);
            $delimiter = '&';
        }

        $with = '';
        if(isset($this->moQuery->with)){
            if(is_array($this->moQuery->with)){
                $withArray = $this->moQuery->with;
            }else{
                $withArray = [$this->moQuery->with];
            }
            $with =  $limit.$delimiter.'with='.json_encode($withArray);
            $delimiter = '&';
        }

        if(is_array($condition) && count($condition)>0){
            $condString = $with.$delimiter.'filter='.json_encode($condition);
        }else if(is_string($condition) && strlen($condition) > 0){
            $condString = $with.$delimiter.'filter='.$condition;
        }else{
            $condString = $with.$delimiter;
        }
        $endpoint = $this->moQuery->modelClass::tableName();
        $url = \Yii::$app->megaofficeClient->url;

        $response = $client->request('GET', $url .'/'. $endpoint.$condString, [
            'headers' => $headers,
        ]);
        return  json_decode($response->getBody(), true) ?? [];

    }

    public function queryOne(){

        $client = new Client();
        $headers = [
            'X-Api-Key'     => '' .\Yii::$app->megaofficeClient->token,
            'Accept'        => 'application/json',
            'Cache-Control'        => '',
        ];
        $condition = $this->buildCondition();

        $delimiter = '&';

        $limit = '?per-page=1';

        $with = '';
        if(isset($this->moQuery->with)){
            if(is_array($this->moQuery->with)){
                $withArray = $this->moQuery->with;
            }else{
                $withArray = [$this->moQuery->with];
            }
            $with =  $limit.$delimiter.'with='.json_encode($withArray);
            $delimiter = '&';
        }


        if(is_array($condition) && count($condition)>0){
            $condString = $with.$delimiter.'filter='.json_encode($condition);
        }else if(is_string($condition) && strlen($condition) > 0){
            $condString = $with.$delimiter.'filter='.$condition;
        }else{
            $condString = $with.$delimiter;
        }
        $endpoint = $this->moQuery->modelClass::tableName();
        $url = \Yii::$app->megaofficeClient->url;

        $response = $client->request('GET', $url .'/'. $endpoint.$condString, [
            'headers' => $headers,
        ]);
        $parsed = json_decode($response->getBody(), true);

        if(is_array($parsed) && count($parsed) > 0){
            return  $parsed[0];
        }
        return false;

    }

    protected function buildCondition(){
        return $this->moQuery->where ?? [];
    }


}