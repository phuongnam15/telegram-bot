<?php

namespace App\Services\ContentConfigService;

use App\Models\ContentConfig;
use App\Services\_Abstract\BaseService;
use App\Services\_Trait\SaveFile;

class ContentConfigService extends BaseService
{
    use SaveFile;
    public function create($request) {
        return DbTransactions()->addCallBackJson(function () use ($request) {
            $input = $request->all();

            if ($request->hasFile('media')) {
                $input['media'] = $this->saveImage($input['media'], PATH_MEDIA, SOURCE_MEDIA);
            } else {
                $input['media'] = json_encode([]);
            }

            if($request->has('buttons')) {
                $input['buttons'] = json_encode($input['buttons']);
            } else {
                $input['buttons'] = json_encode([]);
            }

            return ContentConfig::create($input);
        });
    }
}
