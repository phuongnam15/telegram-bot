<?php

namespace App\Services\BitgetService;

use App\Services\_Abstract\BaseService;
use Illuminate\Support\Facades\Http;
use App\Services\_Exception\AppServiceException;

class BitgetService extends BaseService
{
    public function createOrderBitget($vol, $input, $client, $chatId, $apiKey, $secretKey, $passphrase)
    {
        try {
            $method = "POST";
            $api = "/api/v2/mix/order/place-order";
            $timestamp = round(microtime(true) * 1000);
            $lastPrice = $this->getLatestPriceOfCoin(strtoupper(preg_replace('/^(10+)\s*/', '', $input['coin'])) . "USDT");
            $size = ($vol * $input['leverage']) / $lastPrice;

            $data = [
                "symbol" => strtoupper($input['coin']) . "USDT",
                "productType" => "usdt-futures",
                "marginMode" => "crossed",
                "marginCoin" => "USDT",
                "size" => round($size, 4),
                "side" => $input['orderType'],
                "tradeSide" => "open",
                "orderType" => $input['isLimit'] ? "limit" : "market",
                "force" => "gtc",
                "clientOid" => uniqid(),
                "presetStopSurplusPrice" => $input['TP'],
                "presetStopLossPrice" => $input['SL'],
            ];

            if ($input['isLimit']) {
                $data['price'] = $input['ET'];
            }

            // logger($data);

            $body = json_encode($data);

            $accessSign = $this->generateSignature($timestamp, $method, $api, "", $body, $secretKey);

            $response = Http::withHeaders([
                'ACCESS-KEY' => $apiKey,
                'ACCESS-SIGN' => $accessSign,
                'ACCESS-PASSPHRASE' => $passphrase,
                'ACCESS-TIMESTAMP' => $timestamp,
                'locale' => 'en-US',
                'Content-Type' => 'application/json',

            ])->post("https://api.bitget.com{$api}", $data);

            $result = json_decode($response->body(), true);

            sendResponseTrading($client, $chatId, $input, $result, $lastPrice, "BITGET");

        } catch (AppServiceException | \Exception $error) {
            throw new AppServiceException($error->getMessage());
        }
    }
    public function generateSignature($timestamp, $method, $requestPath, $queryString, $body, $secretKey)
    {
        if (!empty($queryString)) {
            $stringToSign = $timestamp . strtoupper($method) . $requestPath . "?" . $queryString . $body;
        } else {
            $stringToSign = $timestamp . strtoupper($method) . $requestPath . $body;
        }

        return base64_encode(
            hash_hmac('sha256', $stringToSign, $secretKey, true)
        );
    }
    public function getLatestPriceOfCoin($symbol)
    {
        $response = Http::get("https://api.bitget.com/api/v2/spot/market/tickers?symbol={$symbol}");

        if (json_decode($response->body(), true)['code'] !== "00000") {
            throw new AppServiceException("Error get latest price of coin");
        }

        return json_decode($response->body(), true)['data'][0]['lastPr'];
    }
    public function setMaxLeverage($symbol, $apiKey, $secretKey, $passphrase, $leverageLevels)
    {
        $method = "POST";
        $api = "/api/v2/mix/account/set-leverage";
        foreach ($leverageLevels as $value) {
            $timestamp = round(microtime(true) * 1000);
            $data = [
                "symbol" => $symbol,
                "productType" => "USDT-FUTURES",
                "marginCoin" => "usdt",
                "leverage" => $value,
                "holdSide" => "long"
            ];
            $body = json_encode($data);

            $accessSign = $this->generateSignature($timestamp, $method, $api, "", $body, $secretKey);

            $response = Http::withHeaders([
                'ACCESS-KEY' => $apiKey,
                'ACCESS-SIGN' => $accessSign,
                'ACCESS-PASSPHRASE' => $passphrase,
                'ACCESS-TIMESTAMP' => $timestamp,
                'locale' => 'en-US',
                'Content-Type' => 'application/json',

            ])->post("https://api.bitget.com{$api}", $data);

            $result = json_decode($response->body(), true);

            if ($result['code'] === "00000") {
                return $result['data']['longLeverage'];
            }
        }

        return "20";
    }
}
