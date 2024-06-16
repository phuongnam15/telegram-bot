<?php

namespace App\Http\Requests;

use App\Http\Requests\_Abstracts\ApiBaseRequest;
class StoreContentConfigRequest extends ApiBaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "type" => "required|string",
            "content" => "required|string",
        ];
    }
}
