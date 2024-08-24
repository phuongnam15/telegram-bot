<?php

namespace App\Services\BingxService;

use App\Services\_Abstract\BaseService;
use GuzzleHttp\Client;

class BingxService extends BaseService
{
    function getLatestPriceOfCoin($symbol, $apiKey, $secretKey)
    {
        $api = [
            "uri" => "/openApi/swap/v1/ticker/price",
            "method" => "GET",
            "payload" => [
                "symbol" => $symbol
            ],
        ];

        return $this->doRequest($api["uri"], $api["method"], $api["payload"],  $apiKey, $secretKey)['data']['price'];
    }
    public function getMaxLeverage($symbol, $apiKey, $secretKey)
    {
        $dataGet = [
            "uri" => "/openApi/swap/v2/trade/leverage",
            "method" => "GET",
            "payload" => [
                "symbol" => $symbol,
            ],
        ];

        return $this->doRequest($dataGet["uri"], $dataGet["method"], $dataGet["payload"], $apiKey, $secretKey)['data']['maxLongLeverage'];
        
    }
    public function setMaxLeverage($symbol, $leverage, $side, $apiKey, $secretKey)
    {
        $dataSet = [
            "uri" => "/openApi/swap/v2/trade/leverage",
            "method" => "POST",
            "payload" => [
                "symbol" => $symbol,
                "leverage" => $leverage,
                "side" => $side //SHORT or LONG
            ],
        ];

        return $this->doRequest($dataSet["uri"], $dataSet["method"], $dataSet["payload"], $apiKey, $secretKey)['data']['leverage'];
    }
    public function createOrderBingx($vol, $input, $client, $chatId, $apiKey, $secretKey)
    {
        $lastPrice = $this->getLatestPriceOfCoin(strtoupper($input['coin']) . "-USDT", $apiKey, $secretKey);
        $size = ($vol * $input['leverage']) / $lastPrice;
        $data = [
            "uri" => "/openApi/swap/v2/trade/order",
            "method" => "POST",
            "payload" => [
                "symbol" => strtoupper($input['coin']) . "-USDT",
                "side" => $input['orderType'] == 'buy' ? 'BUY' : 'SHELL',
                "positionSide" => $input['orderType'] == 'buy' ? 'LONG' : 'SHORT',
                "type" => $input['isLimit'] ? 'LIMIT' : 'MARKET',
                "quantity" => $size,
                "takeProfit" => json_encode([
                    "type" => "TAKE_PROFIT_MARKET",
                    "stopPrice" => floatval($input['TP']),
                    // "price" => 2000,
                    "workingType" => "MARK_PRICE"
                ]),
                "stopLoss" => json_encode([
                    "type" => "STOP_MARKET",
                    "stopPrice" => floatval($input['SL']),
                    // "price" => 2000,
                    "workingType" => "MARK_PRICE"
                ])
            ],
        ];

        if ($input['isLimit']) {
            $data['payload']['price'] = floatval($input['ET']);
        }

        $result = $this->doRequest($data["uri"], $data["method"], $data["payload"], $apiKey, $secretKey);

        sendResponseTrading($client, $chatId, $input, $result, $lastPrice, "BINGX");
    }

    public function doRequest($api, $method, $payload, $apiKey, $secretKey)
    {
        $timestamp = round(microtime(true) * 1000);
        $parameters = "timestamp=" . $timestamp;

        if ($payload != null) {
            foreach ($payload as $key => $value) {
                $parameters .= "&$key=$value";
            }
        }

        $sign = $this->calculateHmacSha256($parameters, $secretKey);
        $url = "https://open-api.bingx.com{$api}?{$parameters}&signature={$sign}";

        $client = new Client([
            'base_uri' => $url,
            'verify' => false,
        ]);

        $response = $client->request($method, '', [
            'headers' => [
                'X-BX-APIKEY' => $apiKey,
            ],
        ]);

        $result = json_decode($response->getBody()->getContents(), true);
        if($result['code'] != 0) {
            logger($result, [$api, $method]);
        }
        return $result;
    }

    public function calculateHmacSha256($input, $key)
    {
        $hash = hash_hmac("sha256", $input, $key, true);
        $hashHex = bin2hex($hash);
        return strtolower($hashHex);
    }
}
