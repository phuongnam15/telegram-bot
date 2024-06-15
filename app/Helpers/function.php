<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\GameFeesModel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Telegram\Bot\Laravel\Facades\Telegram;

if (!function_exists('DbTransactions')) {
    function DbTransactions()
    {
        return resolve("app.transactions");
    }
}

if (!function_exists('generateShortlink')) {
    function generateShortlink(string $url)
    {
        $response = Http::get($url);

        $data = $response->json();

        $shortUrl = isset($data['shortenedUrl']) ? $data['shortenedUrl'] : $data['url'];

        return $shortUrl;
    }
}

if (!function_exists('generateRandomString')) {
    function generateRandomString($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charLength - 1)];
        }
        return $randomString;
    }
}

if (!function_exists('responseData')) {
    function responseSuccess($data = [], $message = 'success')
    {
        $response = collect([
            (object)[
                'status' => true,
                'message' => $message,
                'data' => ""
            ]
        ])->first();

        $response->data = $data;

        return $response;
    }

    function responseError($message = '')
    {
        return collect([
            (object)[
                'status' => false,
                'message' => $message
            ]
        ])->first();
    }
}

if (!function_exists('processOfferwall')) {
    function processOfferwall($mission)
    {
        switch (Str::lower($mission->name)) {
            case 'bitlabs':
                return processBitlabs($mission);
                break;
        }
    }
}

if (!function_exists('processBitlabs')) {
    function processBitlabs($mission)
    {

        $alias = request()->get('user_id');
        $reward = request()->get('reward');
        $url = request()->fullUrlWithoutQuery(['hash']);
        $hash = request()->get('hash');

        if (hash_hmac("sha1", $url, $mission->secret_key) != $hash) {
            return responseError("Lỗi");
        }
        return responseSuccess([
            'alias' => $alias,
            'balance' => $reward
        ]);
    }
}
if(!function_exists('gameFeeOrReward')){
    function gameFeeOrReward($nameOfGame){
        $fee = GameFeesModel::where('name', $nameOfGame)->first();
        return $fee->amount;
    }
}

if (!function_exists('getImage')) {

    function getImage($file, $path = PATH_FILE_PROOF_APP)
    {
        $url = asset(Storage::url($path . '/' . $file));

        return $url;
    }
}

if (!function_exists('getImages')) {


    function getImages($files, $path = PATH_FILE_PROOF_APP)
    {
        $url = [];

        foreach ($files as $file) {
            $url[] = getImage($file, $path);
        }

        return $url;
    }
}

if (!function_exists('saveImage')) {

    function saveImages($image, $alias)
    {
        $images = [];

        if (!File::isDirectory(public_path(SOURCE_FILE_PROOF_APP . "/" . $alias))) {
            File::makeDirectory(public_path(SOURCE_FILE_PROOF_APP . "/" . $alias), 0777, true, true);
        }

        $path = Telegram::getFile([
            'file_id' => $image['file_id'],
        ]);

        $path = json_decode($path);

        $data = Http::get("https://api.telegram.org/file/bot".env("TELEGRAM_BOT_TOKEN")."/$path->file_path");

        File::put(public_path(SOURCE_FILE_PROOF_APP . "/" . $alias . "/" . $image['file_unique_id'] . ".png"), $data);

        $images[] = $image['file_unique_id'] . ".png";

        Log::info(collect($images));

        return collect($images);
    }
}

