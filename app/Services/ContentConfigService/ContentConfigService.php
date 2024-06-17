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
            }

            if ($request->has('content')) {
                $input['content'] = sanitizeHtml(html_entity_decode($input['content'], ENT_QUOTES, 'UTF-8'));
            }

            // if($request->has('buttons')) {
            //     $input['buttons'] = json_encode($input['buttons']);
            // } else {
            //     $input['buttons'] = json_encode([]);
            // }

            return ContentConfig::create($input);
        });
    }
}
