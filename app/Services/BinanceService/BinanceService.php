<?php

namespace App\Services\BinanceService;

use App\Services\_Abstract\BaseService;
use App\Services\_Exception\AppServiceException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Str;

class BinanceService extends BaseService
{
    public function createOrderBinance($vol, $input, $client, $chatId, $apiKey, $secretKey)
    {
        try {
            $createdOrders = [];

            $lastPrice = $this->getLatestPriceOfCoin(strtoupper($input['coin']) . "USDT", $secretKey);
            $size = ($vol * $input['leverage']) / $lastPrice;
            $stepSize = $this->getStepSize(strtoupper($input['coin']) . "USDT");
            $decimalPlaces = strlen(substr(strrchr(rtrim($stepSize, '0'), '.'), 1));
            $quantity = round($size, $decimalPlaces);

            // logger($size);

            $symbol = strtoupper($input['coin']) . "USDT";
            $orderSide = $input['orderType'] == 'buy' ? 'BUY' : 'SELL';

            //gen 'newClientOrderId' value to identify orders (convinient for canceling)
            $clientOrderId1 = $this->createUniqueId();
            $clientOrderId2 = $this->createUniqueId();
            $clientOrderId3 = $this->createUniqueId();

            $api = "/fapi/v1/order";
            $method = "POST";

            // Payload for Primary Order (ET - Entry Trigger)
            $payload = [
                'symbol' => $symbol,
                'side' => $orderSide,
                'type' => $input['isLimit'] ? 'LIMIT' : 'MARKET',
                'quantity' => $quantity,
                'newClientOrderId' => $clientOrderId1,
            ];

            if ($input['isLimit']) {
                $payload['timeInForce'] = 'GTC';
                $payload['price'] = $input['ET'];
            }

            // Payload for Take Profit
            $payload2 = [
                'symbol' => $symbol,
                'side' => $orderSide,
                'type' => 'TAKE_PROFIT_MARKET',
                'stopPrice' => $input['TP'],
                'quantity' => $quantity,
                'newClientOrderId' => $clientOrderId2,
            ];

            // Payload for Stop Loss
            $payload3 = [
                'symbol' => $symbol,
                'side' => $orderSide,
                'type' => 'STOP_MARKET',
                'stopPrice' => $input['SL'],
                'quantity' => $quantity,
                'newClientOrderId' => $clientOrderId3,
            ];


            // perform Primary Order
            $this->doRequest($api, $method, $payload, $apiKey, $secretKey);
            $createdOrders[] = $clientOrderId1;

            // perform Take Profit
            $this->doRequest($api, $method, $payload2, $apiKey, $secretKey);
            $createdOrders[] = $clientOrderId2;

            // perform Stop Loss
            $this->doRequest($api, $method, $payload3, $apiKey, $secretKey);
            $createdOrders[] = $clientOrderId3;

            $client->post('sendMessage', [
                'json' => [
                    'text' => ($input['orderType'] === 'buy' ? "🟢 " : "🔴 ") . "[Đã vào lệnh]\n\n" . strtoupper($input['coin']) . " - " . strtoupper($input['orderType']) . ($input['isLimit'] ? " limit\n- ET: {$input['ET']}" : "\n- ET xấp xỉ {$lastPrice}") . "" . "\n- SL: {$input['SL']}\n- TP: {$input['TP']}\n- x{$input['leverage']}\n\nBINANCE",
                    'chat_id' => $chatId
                ]
            ]);
        } catch (AppServiceException $e) {

            //cancel order if error
            foreach ($createdOrders as $clientOrderId) {
                $this->cancelOrder(['symbol' => $symbol, 'origclientorderid' => $clientOrderId], $apiKey, $secretKey);
            }

            $errorMessage = $e->getMessage();
            $client->post('sendMessage', [
                'json' => [
                    'text' => "❌ [Tạo lệnh thất bại] ❌\n\n{$errorMessage}\n\n" . strtoupper($input['coin']) . " - " . strtoupper($input['orderType']) . " " . ($input['isLimit'] ? " limit\n- ET: {$input['ET']}" : "\n- ET xấp xỉ {$lastPrice}") . "\n- SL: {$input['SL']}\n- TP: {$input['TP']}\n- x{$input['leverage']}\n\nBINANCE",
                    'chat_id' => $chatId
                ]
            ]);
        }
    }
    public function cancelOrder($payload, $apiKey, $secretKey)
    {
        $orderStatus = $this->getOrderStatus($payload, $apiKey, $secretKey);

        if (!$orderStatus || !in_array($orderStatus['status'], ['NEW', 'PARTIALLY_FILLED'])) {
            return;
        }

        $api = "/fapi/v1/order";
        $method = "DELETE";

        return $this->doRequest($api, $method, $payload, $apiKey, $secretKey);
    }
    public function getLatestPriceOfCoin($symbol, $secretKey)
    {
        $uri = "/fapi/v1/ticker/price?symbol=$symbol";
        $method = 'GET';

        return $this->doRequest($uri, $method, [], "", $secretKey)['price'];
    }
    public function setLeverage($symbol, $leverage, $apiKey, $secretKey)
    {
        $uri = '/fapi/v1/leverage';
        $method = 'POST';
        $payload = [
            'symbol' => $symbol,
            'leverage' => $leverage,
        ];

        return $this->doRequest($uri, $method, $payload, $apiKey, $secretKey);
    }
    public function getMaxLeverage($symbol, $apiKey, $secretKey)
    {
        $uri = "/fapi/v1/leverageBracket";
        $method = 'GET';
        $payload = [
            'symbol' => $symbol,
        ];

        return $this->doRequest($uri, $method, $payload, $apiKey, $secretKey)[0]['brackets'][0]['initialLeverage'];
    }
    protected function doRequest($api, $method, $payload, $apiKey, $secretKey)
    {
        try {
            if ($payload != []) {
                $payload['timestamp'] = round(microtime(true) * 1000);
                $payload['recvWindow'] = 5000;

                $queryString = http_build_query($payload, '', '&');
                $signature = hash_hmac('sha256', $queryString, $secretKey);
                $url = "https://fapi.binance.com{$api}?{$queryString}&signature={$signature}";
            } else {
                $url = "https://fapi.binance.com{$api}";
            }

            $client = new Client([
                'base_uri' => $url,
                'verify' => false,
            ]);

            $response = $client->request($method, '', [
                'headers' => [
                    'X-MBX-APIKEY' => $apiKey,
                ],
            ]);

            $result = $response->getBody()->getContents();
            return json_decode($result, true);
        } catch (RequestException $e) {

            if ($e->hasResponse()) {
                $errorResponse = $e->getResponse();
                $errorData = json_decode($errorResponse->getBody()->getContents(), true);

                // logger($errorData, [$api, $method]);
                throw new AppServiceException($errorData['msg']);
            }
        }
    }
    public function getStepSize($symbol)
    {
        $uri = "/fapi/v1/exchangeInfo";
        $method = 'GET';

        $data = $this->doRequest($uri, $method, [], "", "");

        return collect($data['symbols'])->firstWhere('symbol', $symbol)['filters'][2]['stepSize'];
    }
    function createUniqueId()
    {
        $microTime = microtime(true);

        $randomString = Str::random(8);

        $combinedString = $microTime . $randomString;

        $hashed = hash('sha256', $combinedString);

        $uniqueId = substr($hashed, 0, 36);

        $validId = preg_replace('/[^a-zA-Z0-9._\-:]/', '', $uniqueId);

        return $validId;
    }
    public function getOrderStatus($payload, $apiKey, $secretKey)
    {
        try {
            $api = "/fapi/v1/order";
            $method = "GET";

            return $this->doRequest($api, $method, $payload, $apiKey, $secretKey);
        } catch (AppServiceException $e) {
            return false;
        }
    }
}
