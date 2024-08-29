<?php

namespace App\Services\BybitService;

use App\Services\_Abstract\BaseService;
use App\Services\_Exception\AppServiceException;
use Illuminate\Http\Client\RequestException as Http_RequestException;
use Illuminate\Support\Facades\Http;

class BybitService extends BaseService
{
    public function createOrderBybit($vol, $input, $client, $chatId, $apiKey, $secretKey)
    {
        try {
            $lastPrice = $this->getLatestPrice(strtoupper($input['coin']) . "USDT", $apiKey, $secretKey);
            $size = ($vol * $input['leverage']) / $lastPrice;
            $stepSize = $this->getInstrumentsInfo(strtoupper($input['coin']) . "USDT", $apiKey, $secretKey)['qtyStep'];
            $decimalPlaces = strlen(substr(strrchr(rtrim($stepSize, '0'), '.'), 1));
            $quantity = round($size, $decimalPlaces);

            $uri = "/v5/order/create";
            $method = "POST";
            $payload = [
                "symbol" => strtoupper($input['coin']) . "USDT",
                "side" => $input['orderType'] == 'buy' ? 'Buy' : 'Sell',
                "orderType" => $input['isLimit'] ? 'Limit' : 'Market',
                "qty" => $quantity,
                "takeProfit" => $input['TP'],
                "stopLoss" => $input['SL'],
            ];

            if ($input['isLimit']) {
                $payload['price'] = $input['ET'];
            }

            $this->doRequest($uri, $method, $payload, $apiKey, $secretKey);

            $client->post('sendMessage', [
                'json' => [
                    'text' => ($input['orderType'] === 'buy' ? "🟢 " : "🔴 ") . "[Đã vào lệnh]\n\n" . strtoupper($input['coin']) . " - " . strtoupper($input['orderType']) . ($input['isLimit'] ? " limit\n- ET: {$input['ET']}" : "\n- ET xấp xỉ {$lastPrice}") . "" . "\n- SL: {$input['SL']}\n- TP: {$input['TP']}\n- x{$input['leverage']}\n\nBYBIT",
                    'chat_id' => $chatId
                ]
            ]);
        } catch (AppServiceException $e) {
            $errorMessage = $e->getMessage();
            $client->post('sendMessage', [
                'json' => [
                    'text' => "❌ [Tạo lệnh thất bại] ❌\n\n{$errorMessage}\n\n" . strtoupper($input['coin']) . " - " . strtoupper($input['orderType']) . " " . ($input['isLimit'] ? " limit\n- ET: {$input['ET']}" : "\n- ET xấp xỉ {$lastPrice}") . "\n- SL: {$input['SL']}\n- TP: {$input['TP']}\n- x{$input['leverage']}\n\nBYBIT",
                    'chat_id' => $chatId
                ]
            ]);
            return;
        }
    }
    protected function doRequest($uri, $method, $payload, $apiKey, $secretKey)
    {
        try {
            $timestamp = round(microtime(true) * 1000);
            $recvWindow = '5000';

            $plainText = $timestamp . $apiKey . $recvWindow . json_encode($payload);

            $signature = strtolower(hash_hmac('sha256', $plainText, $secretKey));

            $url = "https://api.bybit.com{$uri}";

            $response = Http::withHeaders([
                'X-BAPI-SIGN' => $signature,
                'X-BAPI-API-KEY' => $apiKey,
                'X-BAPI-TIMESTAMP' => $timestamp,
                'X-BAPI-RECV-WINDOW' => $recvWindow,
                'Content-Type' => 'application/json',
            ])->{$method}($url, $payload);

            $data = $response->json();

            if ($data['retCode'] == 0) {
                return $data;
            } else {
                logger($data, [$uri, $method]);
                throw new AppServiceException($data['retMsg']);
            }
        } catch (Http_RequestException $e) {
            $errorString = $e->getMessage();
            preg_match('/\d{3} (.+?)`/', $errorString, $matches);

            $errorMessage = isset($matches[1]) ? $matches[1] : 'Unknown error';

            throw new AppServiceException($errorMessage);
        }
    }
    public function getInstrumentsInfo($symbol, $apiKey, $secretKey)
    {
        $uri = "/v5/market/instruments-info";
        $method = 'GET';
        $payload = [
            'category' => 'linear',
            'symbol' => $symbol,
        ];

        $result = $this->doRequest($uri, $method, $payload, $apiKey, $secretKey);
        if (isset($result['result']['list'][0]['leverageFilter']['maxLeverage']) && isset($result['result']['list'][0]['lotSizeFilter']['qtyStep'])) {
            $maxLeverage = $result['result']['list'][0]['leverageFilter']['maxLeverage'];
            $qtyStep = $result['result']['list'][0]['lotSizeFilter']['qtyStep'];

            return [
                'maxLeverage' => $maxLeverage,
                'qtyStep' => $qtyStep,
            ];
        }
    }
    public function getLatestPrice($symbol, $apiKey, $secretKey)
    {
        $uri = "/v5/market/tickers";
        $method = 'GET';
        $payload = [
            'category' => 'linear',
            'symbol' => $symbol,
        ];

        $result = $this->doRequest($uri, $method, $payload, $apiKey, $secretKey);
        return $result['result']['list'][0]['lastPrice'];
    }
    public function setLeverage($symbol, $leverage, $apiKey, $secretKey)
    {
        $uri = "/v5/position/set-leverage";
        $method = 'POST';
        $payload = [
            'category' => 'linear',
            'symbol' => $symbol,
            'buyLeverage' => $leverage,
            'sellLeverage' => $leverage,
        ];

        return $this->doRequest($uri, $method, $payload, $apiKey, $secretKey);
    }
}
