<?php

use App\Models\Ticker;
use App\Services\_Exception\AppServiceException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

if (!function_exists('DbTransactions')) {
    function DbTransactions()
    {
        return resolve("app.transactions");
    }
}
if (!function_exists('sanitizeHtml')) {
    function sanitizeHtml($html)
    {
        // Tạo mới một đối tượng DOMDocument với định dạng UTF-8
        $doc = new \DOMDocument('1.0', 'UTF-8');
        // Tắt cảnh báo và tải HTML
        libxml_use_internal_errors(true);
        @$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        // Danh sách các thẻ được phép
        $allowedTags = ['b', 'i', 'u', 's', 'code', 'pre', 'a', 'br'];
        // Mapping các thẻ tương đương
        $tagMapping = [
            'strong' => 'b',
            'em' => 'i',
            'ins' => 'u',
            'del' => 's',
            // 'br' => "\n" // Giữ nguyên chuyển đổi <br> thành xuống dòng
        ];

        // Duyệt qua tất cả các thẻ trong tài liệu
        $elements = $doc->getElementsByTagName('*');
        for ($i = $elements->length - 1; $i >= 0; $i--) {
            $element = $elements->item($i);
            $tagName = $element->nodeName;

            if (!in_array($tagName, $allowedTags)) {
                if (array_key_exists($tagName, $tagMapping)) {
                    if ($tagName == 'br') {
                        $element->parentNode->replaceChild($doc->createTextNode($tagMapping[$tagName]), $element);
                    } else {
                        $newElement = $doc->createElement($tagMapping[$tagName]);
                        while ($element->childNodes->length > 0) {
                            $newElement->appendChild($element->childNodes->item(0));
                        }
                        $element->parentNode->replaceChild($newElement, $element);
                    }
                } else {
                    // Di chuyển nội dung của các thẻ không được phép
                    while ($element->childNodes->length > 0) {
                        $element->parentNode->insertBefore($element->childNodes->item(0), $element);
                    }
                    // Xóa thẻ
                    $element->parentNode->removeChild($element);
                }
            }
        }

        // Xuất HTML đã được làm sạch
        $html = $doc->saveHTML();
        // Decode các HTML entities
        $html = html_entity_decode($html, ENT_QUOTES, 'UTF-8');
        // Cắt bỏ phần khai báo XML không cần thiết được thêm vào đầu tài liệu
        return str_replace('<?xml encoding="UTF-8">', '', $html);
    }
}
if (!function_exists('determineVol')) {
    function determineVol($et, $sl, $leverage, $r)
    {
        $result = floatval(str_replace(',', '.', $r)) / $leverage / (abs($et - $sl) / $et);
        return $result;
    }
}
if (!function_exists('parseOrder')) {
    function parseOrder($text)
    {
        // Normalize the text (lowercase, remove excess whitespace)
        $normalizedText = strtolower(trim($text));

        // Define Regular Expressions
        $coinPattern = '/^([a-zA-Z0-9]+)([\s\-])/i'; // Extract coin name from the beginning of the text
        $orderTypePattern = '/(short|long|buy|sell)/i'; // Order type (short, long, buy, sell)
        $limitPattern = '/\blimit\b/i'; // Separate pattern to check for "limit"
        $etPattern = '/et[\s:]*([\d\.,\- ]+)/i'; // Entry price (can be separated by " " or "-")
        $slPattern = '/stl[\s:]*([\d\.,]+)|sl[\s:]*([\d\.,]+)/i'; // Stop loss or short stop loss
        $tpPattern = '/tp[\s:]*([\d\.,]+)/i'; // Take profit price
        $leveragePattern = '/(\d+)x|x(\d+)/i'; // Pattern to find leverage

        // Extract information from the text
        $coinMatch = preg_match($coinPattern, $normalizedText, $coinMatches);
        $orderTypeMatch = preg_match($orderTypePattern, $normalizedText, $orderTypeMatches);
        $limitMatch = preg_match($limitPattern, $normalizedText, $limitMatches);
        $etMatch = preg_match($etPattern, $normalizedText, $etMatches);
        $slMatch = preg_match($slPattern, $normalizedText, $slMatches);
        $tpMatch = preg_match($tpPattern, $normalizedText, $tpMatches);
        $leverageMatch = preg_match($leveragePattern, $normalizedText, $leverageMatches);

        // Extract values from the matches
        $coin = $coinMatch ? $coinMatches[1] : null;
        $orderType = $orderTypeMatch ? $orderTypeMatches[1] : null;
        $isLimit = $limitMatch ? true : false;

        // Process ET values (multiple ETs allowed, separated by space or hyphen)
        $et = [];
        if ($etMatch) {
            $etValues = preg_split('/[\s\-]+/', trim($etMatches[1]));
            $et = array_map(fn($value) => str_replace(',', '.', trim($value)), array_filter($etValues, fn($value) => !empty($value)));
        }

        // Process SL value
        $sl = $slMatch ? str_replace(',', '.', trim($slMatches[1] ?: $slMatches[2])) : null;

        // Process TP value
        $tp = $tpMatch ? str_replace(',', '.', trim($tpMatches[1])) : null;

        // Extract leverage value
        $leverage = null;
        if ($leverageMatch) {
            $leverage = $leverageMatches[1] ? $leverageMatches[1] : $leverageMatches[2];
        }

        // Validate necessary information
        if (!$orderType || !$sl || !$tp || ($isLimit && empty($et)) || (count($et) > 3)) {
            return false;
        }
        if ($coin !== null) {
            if (
                Ticker::where('name', strtoupper($coin))->count() == 0 &&
                Ticker::where('usdt', strtoupper($coin) . 'USDT')->count() == 0 &&
                Ticker::where('usd', strtoupper($coin) . 'USD')->count() == 0 &&
                Ticker::where('perp', strtoupper($coin) . 'PERP')->count() == 0
            ) {
                return false;
            }
        }

        // Convert order type if needed
        if ($orderType == 'short') {
            $orderType = 'sell';
        } else if ($orderType == 'long') {
            $orderType = 'buy';
        }

        return [
            'coin' => $coin,
            'orderType' => $orderType,
            'isLimit' => $isLimit,
            'ET' => $et,
            'SL' => $sl,
            'TP' => $tp,
            'leverage' => $leverage
        ];
    }
}

if (!function_exists('restrictChatMember')) {
    function restrictChatMember($permissions, $chatId, $userId, $untilDate, $botToken)
    {
        $url = "https://api.telegram.org/bot{$botToken}/restrictChatMember";

        $response = Http::post($url, [
            'chat_id' => $chatId,
            'user_id' => $userId,
            'permissions' => json_encode($permissions),
            'use_independent_chat_permissions' => true,
            'until_date' => $untilDate
        ]);

        if ($response->successful()) {
            return true;
        } else {
            logger($response->body());
            return false;
        }
    }
}

if (!function_exists('banChatMember')) {
    function banChatMember($chatId, $userId, $untilDate, $botToken)
    {
        $url = "https://api.telegram.org/bot{$botToken}/banChatMember";

        $response = Http::post($url, [
            'chat_id' => $chatId,
            'user_id' => $userId,
            'until_date' => $untilDate
        ]);

        if ($response->successful()) {
            return true;
        } else {
            logger($response->body());
            return false;
        }
    }
}

if (!function_exists('unbanChatMember')) {
    function unbanChatMember($chatId, $userId, $botToken)
    {
        $url = "https://api.telegram.org/bot{$botToken}/unbanChatMember";

        $response = Http::post($url, [
            'chat_id' => $chatId,
            'user_id' => $userId
        ]);

        if ($response->successful()) {
            return true;
        } else {
            logger($response->body());
            return false;
        }
    }
}

if (!function_exists('sendMessage')) {
    function sendMessage($chatId, $botToken, $text)
    {
        $client = new Client([
            'base_uri' => "https://api.telegram.org/bot{$botToken}/",
        ]);

        $response = $client->post('sendMessage', [
            'json' => [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML'
            ],
        ]);

        if ($response->getStatusCode() == 200) {
            return true;
        } else {
            logger($response->getBody());
            return false;
        }
    }
}

if (!function_exists('convertBanExpireTime')) {
    function convertBanExpireTime($time)
    {
        try {

            if (preg_match('/(\d+)([smhd])/', $time, $matches)) {
                $value = $matches[1];
                $unit = $matches[2];

                switch ($unit) {
                    case 's':
                        $expiredAt = now()->addSeconds($value)->timestamp;
                        break;
                    case 'm':
                        $expiredAt = now()->addMinutes($value)->timestamp;
                        break;
                    case 'h':
                        $expiredAt = now()->addHours($value)->timestamp;
                        break;
                    case 'd':
                        $expiredAt = now()->addDays($value)->timestamp;
                        break;
                };

                return $expiredAt;
            }

            throw new AppServiceException('Invalid time format');
        } catch (AppServiceException $e) {
            throw new AppServiceException($e->getMessage());
        }
    }
}
