<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LikeRequest extends FormRequest
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
        return [
            'user_id' => [
                'required',
                Rule::unique('likes')->where(function ($query) {
                    return $query->where('shop_id', $this->shop_id);
                })
            ],
            'shop_id' => [
                'required',
                Rule::unique('likes')->where(function ($query) {
                    return $query->where('user_id', $this->user_id);
                })
            ],
        ];
    }
}
