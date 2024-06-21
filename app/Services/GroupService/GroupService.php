<?php

namespace App\Services\GroupService;

use App\Models\TelegramGroup;
use App\Services\_Abstract\BaseService;
use App\Services\_Exception\AppServiceException;
use App\Services\_Trait\SaveFile;

class GroupService extends BaseService
{
    use SaveFile;
   
    public function list() {
        return TelegramGroup::paginate(DEFAULT_PAGINATE);
    }
    public function create($input) {
        $a = TelegramGroup::create($input);
        return response()->json($a);
    }
    public function detail($id) {
        $a = TelegramGroup::find($id);

        if(!$a){
            return response()->json([
                'message' => 'Group not found'
            ]);
        }

        return response()->json($a);
    }
    public function update($id, $input) {

        $a = TelegramGroup::find($id);

        if(!$a){
            return response()->json([
                'message' => 'Group not found'
            ]);
        }

        $a->update($input);

        return response()->json($a);
    }
    public function delete($id) {
        $a = TelegramGroup::find($id);

        if(!$a){
            return response()->json([
                'message' => 'Group not found'
            ]);
        }

        $a->delete();

        return response()->json([
            'message' => 'Group deleted'
        ]);
    }
}
