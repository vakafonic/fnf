<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetHeaderModalRequest extends FormRequest
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
        $rules = [];
        if ($this->type == 'girls' || $this->type == 'boys' || $this->type == 'kids') {
            $rules = [
                'type' => ['required', 'string'],
                'locale_id' => ['required', 'integer', 'exists:languages,id'],
                'page_id' => ['required', 'integer', 'exists:pages,id'],
                'items_type' => ['required', 'string'],
                ];
        } else {
            $rules = [
                'type' => ['required', 'string'],
                'locale_id' => ['required', 'integer', 'exists:languages,id'],
            ];
        }
        return $rules;
    }
}
