<?php

namespace App\Services\ContentConfigService;

use App\Models\ContentConfig;
use App\Repositories\ContentConfigRepo\ContentConfigRepo;
use App\Services\_Abstract\BaseService;
use App\Services\_QueryFilter\Pipes\EqualFilter;
use App\Services\_Trait\SaveFile;

class ContentConfigService extends BaseService
{
    use SaveFile;

    protected $mainRepository;

    function __construct(ContentConfigRepo $contentConfigRepo)
    {
        $this->mainRepository = $contentConfigRepo;
    }
    public function create($request)
    {
        return DbTransactions()->addCallBackJson(function () use ($request) {
            $input = $request->all();

            if ($request->hasFile('media')) {
                $input['media'] = $this->saveImage($input['media'], PATH_MEDIA, SOURCE_MEDIA);
            }

            if ($request->has('content')) {
                $input['content'] = sanitizeHtml(html_entity_decode($input['content'], ENT_QUOTES, 'UTF-8'));
            }

            return ContentConfig::create($input);
        });
    }
    public function list($request)
    {
        $contents = ContentConfig::all();

        return response()->json($contents);
    }
}
