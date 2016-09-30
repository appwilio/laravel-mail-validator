<?php

namespace App\Http\Requests;

use App\Domain\Repository\ExcludeRepository;

class ExcludeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $typesList = implode(
            ",",
            (new ExcludeRepository())->getTypes()
        );
        return [
            "type" => "required|integer|in:{$typesList}",
            "value" => "required|string"
        ];
    }

}
