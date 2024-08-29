<?php

namespace App\Services\BinanceService;

use App\Services\_Abstract\BaseService;
use App\Services\_Exception\AppServiceException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class BinanceService extends BaseService
{
    public function createOrderBinance($vol, $input, $client, $chatId, $apiKey, $secretKey)
    {
        try {
            $lastPrice = $this->getLatestPriceOfCoin(strtoupper($input['coin']) . "USDT", $secretKey);
            $size = ($vol * $input['leverage']) / $lastPrice;
            $stepSize = $this->getStepSize(strtoupper($input['coin']) . "USDT");
            $decimalPlaces = strlen(substr(strrchr(rtrim($stepSize, '0'), '.'), 1));
            $quantity = round($size, $decimalPlaces);

            // logger($size);
            
            $api = "/fapi/v1/order";
            $method = "POST";
            $payload = [
                'symbol' => strtoupper($input['coin']) . "USDT",
                'side' => $input['orderType'] == 'buy' ? 'BUY' : 'SELL',
                'type' => $input['isLimit'] ? 'LIMIT' : 'MARKET',
                'quantity' => $quantity,
            ];
            if ($input['isLimit']) {
                $payload['timeInForce'] = 'GTC';
                $payload['price'] = $input['ET'];
            }
            $payload2 = [
                'symbol' => strtoupper($input['coin']) . "USDT",
                'side' => $input['orderType'] == 'buy' ? 'BUY' : 'SELL',
                'type' => 'TAKE_PROFIT_MARKET',
                'stopPrice' => $input['TP'],
                'quantity' => $quantity,
            ];
            $payload3 = [
                'symbol' => strtoupper($input['coin']) . "USDT",
                'side' => $input['orderType'] == 'buy' ? 'BUY' : 'SELL',
                'type' => 'STOP_MARKET',
                'stopPrice' => $input['SL'],
                'quantity' => $quantity,
            ];

            $this->testOrder($payload, $apiKey, $secretKey);
            $this->testOrder($payload2, $apiKey, $secretKey);
            $this->testOrder($payload3, $apiKey, $secretKey);

            $this->doRequest($api, $method, $payload, $apiKey, $secretKey);
            $this->doRequest($api, $method, $payload2, $apiKey, $secretKey);
            $this->doRequest($api, $method, $payload3, $apiKey, $secretKey);

            $client->post('sendMessage', [
                'json' => [
                    'text' => ($input['orderType'] === 'buy' ? "🟢 " : "🔴 ") . "[Đã vào lệnh]\n\n" . strtoupper($input['coin']) . " - " . strtoupper($input['orderType']) . ($input['isLimit'] ? " limit\n- ET: {$input['ET']}" : "\n- ET xấp xỉ {$lastPrice}") . "" . "\n- SL: {$input['SL']}\n- TP: {$input['TP']}\n- x{$input['leverage']}\n\nBINANCE",
                    'chat_id' => $chatId
                ]
            ]);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $errorResponse = $e->getResponse();
                $errorData = json_decode($errorResponse->getBody()->getContents(), true);

                // logger($errorData);

                $client->post('sendMessage', [
                    'json' => [
                        'text' => "❌ [Tạo lệnh thất bại] ❌\n\n{$errorData['msg']}\n\n" . strtoupper($input['coin']) . " - " . strtoupper($input['orderType']) . " " . ($input['isLimit'] ? " limit\n- ET: {$input['ET']}" : "\n- ET xấp xỉ {$lastPrice}") . "\n- SL: {$input['SL']}\n- TP: {$input['TP']}\n- x{$input['leverage']}\n\nBINANCE",
                        'chat_id' => $chatId
                    ]
                ]);
            }
        }
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

        return json_decode($response->getBody()->getContents(), true);
    }
    public function getStepSize($symbol) {
        $uri = "/fapi/v1/exchangeInfo";
        $method = 'GET';

        $data = $this->doRequest($uri, $method, [], "", "");

        return collect($data['symbols'])->firstWhere('symbol', $symbol)['filters'][2]['stepSize'];
    }
    public function testOrder($payload, $apiKey, $secretKey)
    {
        $uri = "/fapi/v1/order/test";
        $method = "POST";

        return $this->doRequest($uri, $method, $payload, $apiKey, $secretKey);
    }
}
