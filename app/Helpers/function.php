<?php

use App\Models\Ticker;
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
        $result = $r / $leverage / (abs($et - $sl) / $et);
        return $result;
    }
}
if (!function_exists('parseOrder')) {
    function parseOrder($text)
    {
        // Chuyển đoạn văn bản về dạng chuẩn (lowercase, xóa các khoảng trắng thừa)
        $normalizedText = strtolower(trim($text));

        // Định nghĩa các mẫu Regular Expressions
        $coinPattern = '/^([a-zA-Z0-9]+)([\s\-])/i'; // Lấy tên đồng coin từ đầu văn bản
        $orderTypePattern = '/(short|long|buy|sell)\s*(limit)?/i'; // Kiểu lệnh và "limit"
        $etPattern = '/et[\s:]*([\d\.\-\s]+)/i'; // Giá vào lệnh (có thể cách nhau bởi dấu " " hoặc "-")
        $slPattern = '/stl[\s:]*([\d\.]+)|sl[\s:]*([\d\.]+)/i'; // Giá stop loss hoặc stop loss viết tắt
        $tpPattern = '/tp[\s:]*([\d\.]+)/i'; // Giá take profit
        $leveragePattern = '/(\d+)x|x(\d+)/i'; // Mẫu để tìm leverage

        // Kiểm tra các thông tin từ văn bản
        $coinMatch = preg_match($coinPattern, $normalizedText, $coinMatches);
        $orderTypeMatch = preg_match($orderTypePattern, $normalizedText, $orderTypeMatches);
        $etMatch = preg_match($etPattern, $normalizedText, $etMatches);
        $slMatch = preg_match($slPattern, $normalizedText, $slMatches);
        $tpMatch = preg_match($tpPattern, $normalizedText, $tpMatches);
        $leverageMatch = preg_match($leveragePattern, $normalizedText, $leverageMatches);

        // Lấy các thông tin từ kết quả khớp
        $coin = $coinMatch ? $coinMatches[1] : null;
        $orderType = $orderTypeMatch ? $orderTypeMatches[1] : null;
        $isLimit = isset($orderTypeMatches[2]) ? true : false;

        // Xử lý giá trị ET
        $et = [];
        if ($etMatch) {
            $etValues = preg_split('/[\s\-]+/', trim($etMatches[1]));
            $et = array_filter($etValues, fn($value) => !empty($value));
        }

        // Xử lý giá trị SL
        $sl = $slMatch ? trim($slMatches[1] ?: $slMatches[2]) : null;

        // Xử lý giá trị TP
        $tp = $tpMatch ? trim($tpMatches[1]) : null;

        // Lấy giá trị leverage (đòn bẩy)
        $leverage = null;
        if ($leverageMatch) {
            $leverage = $leverageMatches[1] ? $leverageMatches[1] : $leverageMatches[2];
        }

        // Kiểm tra nếu các thông tin cần thiết có mặt
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

        // Chuyển đổi kiểu lệnh nếu cần
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
    function restrictChatMember($listBan, $chatId, $userId, $untilDate)
    {
        $permissions = [];

        foreach (PERMISSIONS as $permission) {
            if (in_array($permission, $listBan)) {
                $permissions[$permission] = true;
            }
        }

        if ($permissions == []) {
            return "No permissions to update.";
        }

        $url = "https://api.telegram.org/bot6618205269:AAFKAsIcFvHyYAD6RLitdIq1mmr-l3HocTc/restrictChatMember";

        $response = Http::post($url, [
            'chat_id' => $chatId,
            'user_id' => $userId,
            'permissions' => json_encode($permissions),
            'use_independent_chat_permissions' => true,
            'until_date' => $untilDate
        ]);

        if ($response->successful()) {
            return "Permissions updated successfully for user.";
        } else {
            return "Failed to update permissions. Error: " . $response->body();
        }
    }
}
