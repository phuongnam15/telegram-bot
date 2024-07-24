<?php

namespace App\Services\ContentConfigService;

use App\Models\ContentConfig;
use App\Repositories\ContentConfigRepo\ContentConfigRepo;
use App\Services\_Abstract\BaseService;
use App\Services\_Exception\AppServiceException;
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

            $input['admin_id'] = auth()->user()->id;

            return ContentConfig::create($input);
        });
    }
    public function list($request)
    {
        $contents = ContentConfig::where('admin_id', auth()->user()->id)->paginate(DEFAULT_PAGINATE);

        return response()->json($contents);
    }
    public function delete($id)
    {
        return DbTransactions()->addCallBackJson(function () use ($id) {

            $contents = ContentConfig::where('id', $id)->first();
            
            if ($contents) {
                $contents->delete();
            }else{
                throw new AppServiceException('Content not found');
            }
            
            return 1;

        });

    }
    public function detail($id)
    {
        $content = ContentConfig::where('id', $id)->first();

        return response()->json($content);
    }
    public function update($id, $request)
    {
        return DbTransactions()->addCallBackJson(function () use ($id, $request) {
            
            $content = ContentConfig::where('id', $id)->first();
    
            if(!$content){
                throw new AppServiceException('Content not found');
            }

            $input = $request->all();

            if ($request->hasFile('media')) {
                $input['media'] = $this->saveImage($input['media'], PATH_MEDIA, SOURCE_MEDIA);
            }

            if ($request->has('content')) {
                $input['content'] = sanitizeHtml(html_entity_decode($input['content'], ENT_QUOTES, 'UTF-8'));
            }
            
            $content->update($input);

            return $content;

        });
    }
    public function setDefault($id) {

        $content = ContentConfig::where('id', $id)->first();
        
        if(!$content){
            return response()->json([
                'message' => 'Content not found'
            ]);
        }

        $content->update([
            'is_default' => true
        ]);

        ContentConfig::where('kind', $content->kind)->where('id', '!=', $id)->update([
            'is_default' => false
        ]);

        return response()->json($content);
    }
}
