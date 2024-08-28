<?php

namespace App\Services\BinanceService;

use App\Services\_Abstract\BaseService;
use App\Services\_Exception\AppServiceException;
use GuzzleHttp\Client;

class BinanceService extends BaseService
{
    public function callBinanceApi()
    {
        
        $apiKey = 'uP5M27SjNz7ZyCDOdX23QhUYf5EXHb0dAsVGmadEKkEJEdNtQ98J44blK2hWg4sk';
        $secretKey = 'GCYpyu242aG3SJJ6US9w6pw46Dmsce1uHXibZhMvobqgFvL9M2MovAfD8dgBDtZd';

        $params = [
            'symbol' => 'BTCUSDT',
            'side' => 'BUY',
            'type' => 'LIMIT',
            'timeInForce' => 'GTC',
            'quantity' => 1, // size
            'price' => 9000, //ET
        ];

        $client = new Client([
            'base_uri' => 'https://fapi.binance.com',
        ]);

        $params['timestamp'] = round(microtime(true) * 1000);

        $params['recvWindow'] = 5000;

        $queryString = http_build_query($params, '', '&');

        $signature = hash_hmac('sha256', $queryString, $secretKey);

        $url = '/fapi/v1/order?' . $queryString . '&signature=' . $signature;

        $response = $client->request('POST', $url, [
            'headers' => [
                'X-MBX-APIKEY' => $apiKey,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
    public function getLastestPriceOfCoin($symbol, $secretKey)
    {
        $uri = "/fapi/v1/ticker/price?symbol=$symbol";
        $method = 'GET';

        return $this->doRequest($uri, $method, [], "", $secretKey);
    }
    public function setLeverage()
    {
        $uri = '/fapi/v1/leverage';
        $method = 'POST';
    }
    protected function doRequest($api, $method, $payload, $apiKey, $secretKey)
    {
        $payload['timestamp'] = round(microtime(true) * 1000);
        $payload['recvWindow'] = 5000;

        $queryString = http_build_query($payload, '', '&');

        $signature = hash_hmac('sha256', $queryString, $secretKey);
        $url = "https://fapi.binance.com{$api}?{$queryString}&signature={$signature}";

        $client = new Client([
            'base_uri' => $url,
            'verify' => false,
        ]);

        $response = $client->request($method, '', [
            'headers' => [
                'X-MBX-APIKEY' => $apiKey,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
