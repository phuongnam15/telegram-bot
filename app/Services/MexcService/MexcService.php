<?php

namespace App\Services\MexcService;

use App\Services\_Abstract\BaseService;
use App\Services\_Exception\AppServiceException;
use Illuminate\Support\Facades\Http;

class MexcService extends BaseService
{
    public function createMexcOrder($vol, $input, $client, $chatId, $apiKey, $secretKey)
    {
        try{
            $lastPrice = $this->getLatestPrice(strtoupper($input['coin']) . "_USDT", $apiKey, $secretKey);
            $size = ($vol * $input['leverage']) / $lastPrice;

            $contractDetail = $this->contractDetail(strtoupper($input['coin']) . "_USDT", $apiKey, $secretKey);
            $lotSize = $contractDetail['lotSize'];
            $contractValue = $contractDetail['contractValue'];
            
            $decimalPlaces = strlen(substr(strrchr(rtrim($lotSize, '0'), '.'), 1));
            $quantity = round($size / $contractValue, $decimalPlaces);

            $uri = "/api/v1/private/order/submit";
            $method = "POST";
            $payload = [
                "symbol" => strtoupper($input['coin']) . "_USDT",
                "side" => $input['orderType'],
                "type" => $input['isLimit'] ? 'limit' : 'market',
                "price" => $input['ET'],
                "quantity" => strval($quantity),
                "leverage" => $input['leverage'],
                "tpTriggerPx" => $input['TP'],
                "slTriggerPx" => $input['SL'],
            ];

        }catch(AppServiceException $e) {
            logger($e->getMessage());
            throw new AppServiceException($e->getMessage());
        }
    }
    function doRequest($uri, $method, $payload, $apiKey, $secretKey)
    {
        try {
            $timestamp = (string) (time() * 1000);

            $queryString = '';
            if ($method === 'GET' || $method === "DELETE") {
                ksort($payload);
                $queryString = http_build_query($payload);
            } elseif ($method === 'POST') {
                $queryString = json_encode($payload);
            }

            $signatureTarget = $apiKey . $timestamp . $queryString;
            $signature = hash_hmac('sha256', $signatureTarget, $secretKey);

            $response = Http::withHeaders([
                'ApiKey' => $apiKey,
                'Request-Time' => $timestamp,
                'Signature' => $signature,
                'Content-Type' => 'application/json',
            ])->{$method}("https://contract.mexc.com$uri", $payload);

            return $response->json();
        } catch (AppServiceException $e) {
            logger($e->getMessage());
            throw new AppServiceException($e->getMessage());
        }
    }

    public function contractDetail($symbol, $apiKey, $secretKey)
    {
        $uri = "/api/v1/contract/detail";
        $method = 'GET';
        $payload = [
            'symbol' => $symbol, //BTC_USDT
        ];

        $result = $this->doRequest($uri, $method, $payload, $apiKey, $secretKey);
        return [
            'maxLeverage' => $result['data']['maxLeverage'],
            'lotSize' => $result['data']['priceUnit'],
            'contractValue' => $result['data']['contractSize'],
        ];
    }

    public function getLatestPrice($symbol, $apiKey, $secretKey)
    {
        $uri = "/api/v1/contract/ticker";
        $method = 'GET';
        $payload = [
            'symbol' => $symbol, //BTC_USDT
        ];

        return $this->doRequest($uri, $method, $payload, $apiKey, $secretKey)['data']['lastPrice'];
    }
    public function setLeverage($symbol, $leverage, $apiKey, $secretKey)
    {
        $uri = "/api/v1/private/position/change_leverage";
        $method = 'POST';
        $payload = [
            // "positionId" => 1, // 1:hedge，2:one-way
            "openType" => 1,
            'symbol' => $symbol, //BTC_USDT
            'leverage' => $leverage,
        ];

        $payload['positionType'] = 1; // required when there is no position, positionType: 1 Long 2:short
        $this->doRequest($uri, $method, $payload, $apiKey, $secretKey);
        $payload['positionType'] = 2; // required when there is no position, positionType: 1 Long 2:short
        $this->doRequest($uri, $method, $payload, $apiKey, $secretKey);

        return;
    }
}
