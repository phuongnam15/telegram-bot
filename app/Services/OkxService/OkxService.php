<?php

namespace App\Services\OkxService;

use App\Services\_Abstract\BaseService;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use App\Services\_Exception\AppServiceException;
use Carbon\Carbon;

class OkxService extends BaseService
{
    public function createOrderOkx($vol, $input, $client, $chatId, $apiKey, $secretKey, $passphrase)
    {
        try{
            $lastPrice = $this->getLatestPrice(strtoupper($input['coin']) . "-USDT", $apiKey, $secretKey, $passphrase);
            $size = ($vol * $input['leverage']) / $lastPrice;
            $stepSize = $this->getInstrumentsInfo(strtoupper($input['coin']) . "-USDT", $apiKey, $secretKey, $passphrase)['stepSize'];
            $decimalPlaces = strlen(substr(strrchr(rtrim($stepSize, '0'), '.'), 1));
            $quantity = round($size, $decimalPlaces);
    
            $uri = "/api/v5/trade/order";
            $method = "POST";
            $payload = [
                "instId" => strtoupper($input['coin']) . "-USDT",
                "tdMode" => "isolated",
                "side" => $input['orderType'],
                "ordType" => $input['isLimit'] ? 'limit' : 'market',
                "sz" => strval($quantity),
                "tpTriggerPx" => $input['TP'],
                "tpOrdPx" => "-1",
                "slTriggerPx" => $input['SL'],
                "slOrdPx" => "-1",
            ];

            if ($input['isLimit']) {
                $payload['px'] = $input['ET'];
            }
    
            $result = $this->doRequest($uri, $method, $payload, $apiKey, $secretKey, $passphrase);

            sendResponseTrading($client, $chatId, $input, $result, $lastPrice, "OKX");

        }catch(AppServiceException $e) {
            logger($e->getMessage(), ['error']);
        }
    }
    protected function doRequest($requestPath, $method, $payload, $apiKey, $secretKey, $passphrase)
    {
        try {
            $timestamp = Carbon::now('UTC')->format('Y-m-d\TH:i:s.v\Z');
            $body = json_encode($payload);

            $prehashString = $timestamp . $method . $requestPath . $body;
            $signature = base64_encode(hash_hmac('sha256', $prehashString, $secretKey, true));

            $response = Http::timeout(30)->withHeaders([
                'Content-Type' => 'application/json',
                'OK-ACCESS-KEY' => $apiKey,
                'OK-ACCESS-SIGN' => $signature,
                'OK-ACCESS-TIMESTAMP' => $timestamp,
                'OK-ACCESS-PASSPHRASE' => $passphrase
            ])->{$method}("https://www.okx.com$requestPath", $payload);

            $data = $response->json();

            return $data;
        } catch (RequestException $e) {
            $errorMessage = $e->getMessage();
            logger($errorMessage);
            throw new AppServiceException($errorMessage);
        }
    }

    public function getLatestPrice($symbol, $apiKey, $secretKey, $passphrase)
    {
        $uri = "/api/v5/market/tickers";
        $method = 'GET';
        $payload = [
            'instType' => 'SWAP',
            'uly' => $symbol, //BTC-USDT
        ];

        return $this->doRequest($uri, $method, $payload, $apiKey, $secretKey, $passphrase)['data'][0]['last'];
    }
    public function getInstrumentsInfo($symbol, $apiKey, $secretKey, $passphrase)
    {
        $uri = "/api/v5/public/instruments";
        $method = 'GET';
        $payload = [
            'instType' => 'SWAP',
            'uly' => $symbol, //BTC-USDT
        ];

        $result = $this->doRequest($uri, $method, $payload, $apiKey, $secretKey, $passphrase)['data'][0];

        return [
            'maxLeverage' => $result['lever'],
            'stepSize' => $result['tickSz'],
        ];
    }
    public function setLeverage($symbol, $leverage, $apiKey, $secretKey, $passphrase)
    {
        $uri = "/api/v5/account/set-leverage";
        $method = 'POST';
        $payload = [
            'instId' => $symbol, //BTC-USDT-SWAP
            'lever' => $leverage,
            'mgnMode' => 'isolated',
        ];

        return $this->doRequest($uri, $method, $payload, $apiKey, $secretKey, $passphrase);
    }
}
